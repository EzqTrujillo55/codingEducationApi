<?php

namespace App\Http\Controllers;

use App\Models\SchoolHasEvent;
use App\Http\Requests\StoreSchoolHasEventRequest;
use App\Http\Requests\UpdateSchoolHasEventRequest;
use Illuminate\Http\Request;

class SchoolHasEventController extends Controller
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

    public function assignEvent(Request $request)
    {
        try {

            $fields = $request->validate([
                'school_id' => 'required|exists:schools,id',
                'event_id' => 'required|exists:events,id',
            ]);

            SchoolHasEvent::create([
                'school_id' => $fields['school_id'],
                'event_id' => $fields['event_id'],
            ]);

            $response = [
                'data' => true,
                'message' => 'Event assign to school succefully',
                'staus_code' => 201,
            ];

            return response($response, 201);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'Ha ocurrido un error al intentar iniciar sesion',
                'error' => $exception->getMessage(),
                'staus_code' => 500,
            ];

            return response($response, 500);
        }
    }
}
