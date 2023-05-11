<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Event;
use App\Models\SchoolHasStudent;
use Illuminate\Http\Request;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showStudents()
    {
        try {
            $student = Student::with(['familyParents', 'schools', 'events'])->get();

            $response = [
                'data' => $student,
                'message' => 'Student retrived successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while geting students',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    //no termianda
    public function createNewStudentAndEventWithUserLoggedInfo(Request $request)
    {
        try {
            // Obtener el usuario en sesión
            $user = auth()->user();


            // Validar los datos de entrada
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
            ]);

            // Crear un nuevo estudiante en la base de datos y asociarlo con el usuario en sesión
            $student = new Student();
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->birthdate = $request->birthdate;
            $student->nationality = $request->nationality;
            $student->passport = $request->passport;
            $student->valid_visa = $request->valid_visa;
            $student->end_of_validity = $request->end_of_validity;
            $student->student_email = $request->student_email;
            $student->residence_country = $request->residence_country;
            $student->city = $request->city;
            $student->postal_code = $request->postal_code;
            $student->emergency_contact_full_name = $request->emergency_contact_full_name;
            $student->emergency_contact_relationship = $request->emergency_contact_relationship;
            $student->emergency_contact_email = $request->emergency_contact_email;
            $student->emergency_contact_phone_number = $request->emergency_contact_phone_number;
            $student->parents_id = $user->familyParents->id;
            $student->save();

            // Asignar el evento al nuevo estudiante
            SchoolHasStudent::create([
                'school_id' => $fields['school_id'],
                'student_id' => $student->id,
                'event_id' => $fields['event_id']
            ]);

            $response = [
                'data' => $student,
                'message' => 'Student created successfully',
                'status_code' => 201,
            ];

            return response($response, 201);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while creating the student',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    //pendiente por probar esta funcion
    public function showStudentsByUserLogged()
    {
        try {
            // Obtenemos el usuario en sesión
            $user = auth()->user();
            
            // Obtenemos los estudiantes del usuario en sesión
            $students = Student::with('events.program')->where('parents_id', '=', $user->familyparents->id)->get(['id','first_name','last_name']);

            $response = [
                'data' => $students,
                'message' => 'Students of logged in user shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the students of logged in user',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showStudentById($id)
    {
        try {
            $student = Student::findOrFail($id);

            if(!$student){
                $response = [
                    'message' => 'Student not found',
                    'error' => $exception->getMessage(),
                    'status_code' => 404,
                ];   
                return response($response, 404);
            }
            

            $response = [
                'data' => $student,
                'message' => 'Student show successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        }  catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the student',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function editStudentById(Request $request, $id)
    {
        try {
            // Buscar el estudiante por su id
            $student = Student::find($id);

            // Si no se encuentra el estudiante, devolver un error 404
            if (!$student) {
                $response = [
                    'data' => null,
                    'message' => 'Student not found',
                    'status_code' => 404,
                ];
                return response($response, 404);
            }

            // Validar los datos de entrada
            $validator = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'nationality' => 'required|string|max:255',
                'passport' => 'required|string|max:255',
                'valid_visa' => 'required|boolean',
                'end_of_validity' => 'required|date',
                'student_email' => 'required|string|email',
                'residence_country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|numeric',
                'emergency_contact_full_name' => 'required|string|max:255',
                'emergency_contact_relationship' => 'required|string|max:255',
                'emergency_contact_email' => 'required|string|email',
                'emergency_contact_phone_number' => 'required|string|max:255',
            ]);

            // Actualizar los datos del estudiante con los nuevos datos
            $student->fill($request->all());
            $student->save();

            $response = [
                'data' => $student,
                'message' => 'Student updated successfully',
                'status_code' => 200,
            ];
            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while updating the student',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];
            return response($response, 500);
        }
    }

    public function deleteStudentById($id)
    {
        try {
            // Buscar el estudiante por su id
            $student = Student::find($id);

            if (!$student) {
                $response = [
                    'data' => null,
                    'message' => 'student not found',
                    'staus_code' => 404,
                ];

                return response($response, 404);
            }

            $student->delete();

            $response = [
                'data' => $id,
                'message' => 'student deleted successfully',
                'staus_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while deleting the student',
                'error' => $exception->getMessage(),
                'staus_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function assigningAstudenttoAuser(Request $request) {
        $data = json_decode($request->getContent(), true);
        $request = new Request($data);
    try {

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
            'family_parents_id' => 'required|exists:familyparents,id',
        ]);
    
        // Crear los modelos correspondientes
  
        
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
            'parents_id' => $fields['family_parents_id'] //asociando el estudiante a los padres
        ]);


        // Asociar el estudiante con la escuela correspondiente
        $schoolHasStudent = new SchoolHasStudent;
        $schoolHasStudent->school_id = $fields['school_id'];
        $schoolHasStudent->student_id = $student->id;
        $schoolHasStudent->event_id = $fields['event_id'];
        $schoolHasStudent->save();

        // Retornamos una respuesta con los datos del usuario creado

        $response = [
            'data' => $student,
            'message' => 'Estudiante creado exitosamente.'
        ];

        return response($response, 201);

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
