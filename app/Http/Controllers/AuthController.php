<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Student;
use App\Models\FamilyParent;
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
            
            // $user->getRoleNames();
            // $user->getPermissionsViaRoles();
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
        try {
            $data = json_decode($request->getContent(), true);
            $request = new Request($data);
            $validatedData = $request->validate([
                'school_id' => 'required|integer',
                'event_id' => 'required|integer',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'nationality' => 'required|string|max:255',
                'passport' => 'required|string|max:255|unique:students,passport',
                'valid_visa' => 'required|boolean',
                'end_of_validity' => 'required|date',
                'student_email' => 'required|string|email|unique:students,student_email',
                'residence_country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|integer',
                'emergency_contact_full_name' => 'required|string|max:255',
                'emergency_contact_relationship' => 'required|string|max:255',
                'emergency_contact_email' => 'required|string|email|max:255',
                'emergency_contact_phone_number' => 'required|string|max:255',
                'mothers_name' => 'required|string|max:255',
                'mothers_phone' => 'required|string|max:255',
                'mothers_email' => 'required|string|email|unique:familyparents,mothers_email',
                'fathers_name' => 'required|string|max:255',
                'fathers_phone' => 'required|string|max:255',
                'fathers_email' => 'required|string|email|unique:familyparents,fathers_email',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);
        
            // Crear los modelos correspondientes
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);

            $role = Role::where('name', 'Tutor')->first();
            $user->assignRole($role);
            $permissions = Permission::whereIn('name', ['create_student', 'edit_student'])->get();
            $user->givePermissionTo($permissions);
            

            $familyParents = FamilyParent::create([
                'mothers_name' => $validatedData['mothers_name'],
                'mothers_phone' => $validatedData['mothers_phone'],
                'mothers_email' => $validatedData['mothers_email'],
                'fathers_name' => $validatedData['fathers_name'],
                'fathers_phone' => $validatedData['fathers_phone'],
                'fathers_email' => $validatedData['fathers_email'],
                'user_id' => $user->id, //asociando el usuario a los padres
            ]);

            $student = Student::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'birthdate' => $validatedData['birthdate'],
                'nationality' => $validatedData['nationality'],
                'passport' => $validatedData['passport'],
                'valid_visa' => $validatedData['valid_visa'],
                'end_of_validity' => $validatedData['end_of_validity'],
                'student_email' => $validatedData['student_email'],
                'residence_country' => $validatedData['residence_country'],
                'city' => $validatedData['city'],
                'postal_code' => $validatedData['postal_code'],
                'emergency_contact_full_name' => $validatedData['emergency_contact_full_name'],
                'emergency_contact_relationship' => $validatedData['emergency_contact_relationship'],
                'emergency_contact_email' => $validatedData['emergency_contact_email'],
                'emergency_contact_phone_number' => $validatedData['emergency_contact_phone_number'],
                'parents_id' => $familyParents->id //asociando el estudiante a los padres
            ]);


            // Asociar el estudiante con la escuela correspondiente
            $schoolHasStudent = new SchoolHasStudent;
            $schoolHasStudent->school_id = $validatedData['school_id'];
            $schoolHasStudent->student_id = $student->id;
            $schoolHasStudent->event_id = $validatedData['event_id'];
            $schoolHasStudent->save();

            // Retornamos una respuesta con los datos del usuario creado
            return response()->json([
                'data' => $user,
                'message' => 'Usuario creado exitosamente.'
            ], 201);


        } catch (QueryException $exception) {
            $response = [
                'message' => "Ha ocurrido un error al registrarse",
                'error' => $exception->getMessage(),
                // 'code' => 500
            ];

            return response($response , 500);
        }
    }

}
