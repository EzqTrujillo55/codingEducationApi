<?php

namespace App\Http\Controllers;

use App\Models\SchoolHasPrograms;
use App\Http\Requests\StoreSchoolHasEventRequest;
use App\Http\Requests\UpdateSchoolHasEventRequest;
use Illuminate\Http\Request;

class SchoolHasProgramController extends Controller
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

    public function assignProgram(Request $request)
    {
        try {
            

            $fields = $request->validate([
                'school_id' => 'required|exists:schools,id',
                'program_id' => 'required|exists:programs,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'payment_limit' => 'required|date',
                'price' => 'required|numeric',
                'initial_fee' => 'required|numeric'
            ]);

            
            SchoolHasPrograms::create([
                'school_id' => $fields['school_id'],
                'program_id' => $fields['program_id'],
                'start_date' => $fields['start_date'],
                'end_date' => $fields['end_date'],
                'payment_limit' => $fields['payment_limit'],
                'price' => $fields['price'],
                'initial_fee' => $fields['initial_fee']                
            ]);

            
            $response = [
                'data' => true,
                'message' => 'Program assign to school succesfully',
                'status_code' => 201,
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
