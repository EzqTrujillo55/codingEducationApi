<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Representative;
use App\Models\SchoolHasEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Controllers\SchoolHasEventController;


class SchoolController extends Controller
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

    public function createSchoolAndRepresentative(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'name' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'event_id' => 'required|exists:events,id',
            ]);

            // Creación de un nuevo representante en la base de datos
            $representative = new Representative();
            $representative->full_name = $request->full_name;
            $representative->phone = $request->phone;
            $representative->save();

            // Creación de una nueva escuela en la base de datos y asociación con el representante y el evento
            $school = new School();
            $school->name = $request->name;
            $school->country = $request->country;
            $school->representative_id = $representative->id;
            $school->save();

            SchoolHasEvent::create([
                'school_id' =>  $school->id,
                'event_id' => $request['event_id'],
            ]);

            $response = [
                'data' => $school,
                'message' => 'School created successfully',
                'status_code' => 201,
            ];

            return response($response, 201);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while creating the school',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function deleteSchoolAndRepresentativeBySchoolId($id)
    {
        try {
            $school = School::find($id);

            if(!$school){
                $response = [
                    'data' => null,
                    'message' => 'The school with the ID '.$id.' could not be found.',
                    'status_code' => 404,
                ];

            return response($response, 404);
            }
            $representative_id = $school->representative_id;
            $school->delete();

            $representative = Representative::findOrFail($representative_id);
            $representative->delete();

            $response = [
                'data' => $id,
                'message' => 'School and representative deleted successfully',
                'status_code' => 200,
            ];

            return response($response, 200);

        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while deleting the school and representative',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showAllSchoolAndRepresentative()
    {
        try {
            $schools = School::with(['events','representative'])->get();

            $response = [
                'data' => $schools,
                'message' => 'Schools and representatives shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the schools and representatives',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showSchoolAndRepresentativeBySchoolId($id)
    {
        try {
            $school = School::with('representative')->find($id);

            if(!$school){
                $response = [
                    'data' => null,
                    'message' => 'The school with the ID '.$id.' could not be found.',
                    'status_code' => 404,
                ];
            return response($response, 404);
            }
            
            $response = [
                'data' => $school,
                'message' => 'School show successfully',
                'status_code' => 200,
            ];
            return response($response, 200);
        
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while retrieving the school and representative',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function editSchoolAndRepresentativeBySchoolId(Request $request, $id)
    {
        try {
            $school = School::find($id);

            if(!$school){
                $response = [
                    'data' => null,
                    'message' => 'The school with the ID '.$id.' could not be found.',
                    'status_code' => 404,
                ];

                return response($response, 404);
            }
                
            $representative_id = $school->representative_id;
            $representative = Representative::findOrFail($representative_id);

            // Actualizamos los valores de la escuela
            $school->name = $request->input('name', $school->name);
            $school->country = $request->input('country', $school->country);
            $school->representative_id = $request->input('representative_id', $school->representative_id);
            $school->save();

            // Actualizamos los valores del representante
            $representative->full_name = $request->input('full_name', $representative->full_name);
            $representative->phone = $request->input('phone', $representative->phone);
            $representative->save();

            $response = [
                'data' => $school,
                'message' => 'School and representative edited successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while editing the school and representative',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showAllSchoolsWithEvents($id)
    {
        try {
            $events = SchoolHasEvent::where('school_id', '=', $id)
            ->with('event')
            ->get();

            $data = array();
            foreach($events as $item) {
                $data[] = $item['event'];
            }

            $response = [
                'data' => $data,
                'message' => 'Schools with events show successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the schools with events',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

}
