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
    public function index()
    {
        //
    }

    //no termianda
    public function createNewStudentAndEventWithUserLoggedInfo(Request $request)
    {
        try {
            // Obtener el usuario en sesión
            $user = auth()->user();


            // Validar los datos de entrada
            $fields = $request->validate([
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'birthdate' => 'required|date',
                'nationality' => 'required|max:255',
                'passport' => 'required|max:255',
                'valid_visa' => 'required|max:255',
                'end_of_validity' => 'required|date',
                'student_email' => 'required|email|unique:students',
                'residence_country' => 'required|max:255',
                'city' => 'required|max:255',
                'postal_code' => 'required|integer',
                'emergency_contact_full_name' => 'required|max:255',
                'emergency_contact_relationship' => 'required|max:255',
                'emergency_contact_email' => 'required|email',
                'emergency_contact_phone_number' => 'required|max:255',
                'event_id' => 'required|integer|exists:events,id',
                'school_id' => 'required|integer|exists:schools,id',
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
            $students = $user->students;



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
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'birthdate' => 'date',
                'nationality' => 'string|max:255',
                'passport' => 'string|max:255',
                'valid_visa' => 'string|max:255',
                'end_of_validity' => 'date',
                'student_email' => 'string|email|unique:students',
                'residence_country' => 'string|max:255',
                'city' => 'string|max:255',
                'postal_code' => 'integer',
                'emergency_contact_full_name' => 'string|max:255',
                'emergency_contact_relationship' => 'string|max:255',
                'emergency_contact_email' => 'string|email',
                'emergency_contact_phone_number' => 'string|max:255',
                'parents_id' => 'integer|exists:familyparents,id',
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

}
