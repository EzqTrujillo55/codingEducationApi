<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Student;
use App\Models\Familyparent;
use App\Models\SchoolHasStudent;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Redirect;
// use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $data = json_decode($request->getContent(), true);
            $request = new Request($data);

            $fields = $request->validate([
                'email' => 'required',
                'password' => 'required|string',
            ]);

            // Check user
            $user = User::where('email', $fields['email'])->first();

            // Check password
            if(!$user || !Hash::check($fields['password'], $user->password)) {
                return response([
                    'message' => 'Usuario o contraseña inválida'
                ], 401);
            }
            
            $user->getRoleNames();
            $user->getPermissionsViaRoles();
            $token = $user->createToken('myapptoken')->plainTextToken;
            $response = [
                'data' => $user,
                'token' => $token
            ];

            return response($response, 200);

        } catch (QueryException $exception) {
            $response = [
                'message' => "Ha ocurrido un error al intentar iniciar sesion",
                'error' => $exception->getMessage(),
            ];

            return response($response , 500);
        }
    }

    public function register(Request $request) {
            $data = json_decode($request->getContent(), true);
            $request = new Request($data);
        try {

            error_log('Aca estoiy;');
            error_log('Aca estoiy2;');
            
            $fields = $request->validate([
                'school_id' => 'required|numeric|exists:schools,id',
                'event_id' => 'required|numeric|exists:events,id',

                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'nationality' => 'required|string|max:255',
                'passport' => 'required|string|max:255|unique:students,passport',
                'valid_visa' => 'required|boolean',
                'end_of_validity' => 'required|date',
                'student_email' => 'required|email|unique:students,student_email',
                'residence_country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:255',

                'emergency_contact_full_name' => 'required|string|max:255',
                'emergency_contact_relationship' => 'required|string|max:255',
                'emergency_contact_email' => 'required|email|max:255',
                'emergency_contact_phone_number' => 'required|string|max:255',

                'mothers_name' => 'required|string|max:255',
                'mothers_phone' => 'required|string|max:255',
                'mothers_email' => 'required|email|unique:familyparents,mothers_email',
                'fathers_name' => 'required|string|max:255',
                'fathers_phone' => 'required|string|max:255',
                'fathers_email' => 'required|email|unique:familyparents,fathers_email',

                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8'
            ]);


            error_log('Aca estoiy2;');
            
            // Crear los modelos correspondientes
            $user = User::create([
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
            ]);


            error_log('Aca estoiy3;');
            
            $role = Role::where('name', 'Tutor')->first();
            $user->assignRole($role);
            $permissions = Permission::whereIn('name', ['create_student', 'edit_student'])->get();
            $user->givePermissionTo($permissions);
            


            error_log('Aca estoiy4;');
            
            $familyParents = Familyparent::create([
                'mothers_name' => $fields['mothers_name'],
                'mothers_phone' => $fields['mothers_phone'],
                'mothers_email' => $fields['mothers_email'],
                'fathers_name' => $fields['fathers_name'],
                'fathers_phone' => $fields['fathers_phone'],
                'fathers_email' => $fields['fathers_email'],
                'user_id' => $user->id, //asociando el usuario a los padres
            ]);

            error_log('Aca estoiy5;');
            
            $student = Student::create([
                'first_name' => $fields['first_name'],
                'last_name' => $fields['last_name'],
                'birthdate' => $fields['birthdate'],
                'nationality' => $fields['nationality'],
                'passport' => $fields['passport'],
                'valid_visa' => $fields['valid_visa'],
                'end_of_validity' => $fields['end_of_validity'],
                'student_email' => $fields['student_email'],
                'residence_country' => $fields['residence_country'],
                'city' => $fields['city'],
                'postal_code' => $fields['postal_code'],
                'emergency_contact_full_name' => $fields['emergency_contact_full_name'],
                'emergency_contact_relationship' => $fields['emergency_contact_relationship'],
                'emergency_contact_email' => $fields['emergency_contact_email'],
                'emergency_contact_phone_number' => $fields['emergency_contact_phone_number'],
                'parents_id' => $familyParents->id //asociando el estudiante a los padres
            ]);



            error_log('Aca estoiy6;');
            
            // Asociar el estudiante con la escuela correspondiente
            $schoolHasStudent = new SchoolHasStudent;
            $schoolHasStudent->school_id = $fields['school_id'];
            $schoolHasStudent->student_id = $student->id;
            $schoolHasStudent->event_id = $fields['event_id'];
            $schoolHasStudent->save();

            // Retornamos una respuesta con los datos del usuario creado

            error_log('Aca estoiy7;');
            
            $response = [
                'data' => $user,
                'message' => 'Usuario creado exitosamente.'
            ];

            return response($response, 201);

        } catch (QueryException $exception) {
            $response = [
                'message' => "Ha ocurrido un error al registrarse",
                'error' => $exception->getMessage(),
                // 'code' => 500
            ];

            return response($response , 500);
        } catch (\Exception $e) {
            $response = [
                'message' => "Ha ocurrido un error al registrarse",
                'error' => $e->getMessage(),
                'code' => 500
            ];

            return response($response , 500);
        }
    }


    public function registerAdmin(Request $request) {
        $data = json_decode($request->getContent(), true);
        $request = new Request($data);
    try {

        
        $fields = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8'
        ]);


        
        // Crear los modelos correspondientes
        $user = User::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => 'ADMIN'
        ]);

        
        $response = [
            'data' => $user,
            'message' => 'Admin creado exitosamente.'
        ];

        return response($response, 201);

    } catch (QueryException $exception) {
        $response = [
            'message' => "Ha ocurrido un error al registrarse",
            'error' => $exception->getMessage(),
            // 'code' => 500
        ];

        return response($response , 500);
    } catch (\Exception $e) {
        $response = [
            'message' => "Ha ocurrido un error al registrarse",
            'error' => $e->getMessage(),
            'code' => 500
        ];

        return response($response , 500);
    }
}

}
