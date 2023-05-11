<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function createEvent(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'name' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'payment_limit' => 'required|date',
                'price' => 'required|numeric',
                'initial_fee' => 'required|numeric',
                'terms_and_conditions' => 'required',
                'program_id' => 'required|exists:programs,id',
            ]);

            // Creación de un nuevo evento en la base de datos
            $event = new Event();
            $event->name = $request->name;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->payment_limit = $request->payment_limit;
            $event->price = $request->price;
            $event->initial_fee = $request->initial_fee;
            $event->terms_and_conditions = $request->terms_and_conditions;
            $event->program_id = $request->program_id;
            $event->save();

            $response = [
                'data' => $event,
                'message' => 'Event created successfully',
                'status_code' => 201,
            ];

            return response($response, 201);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error ocurred while trying to create the event',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function deleteEvent($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                $response = [
                    'data' => null,
                    'message' => 'Event not found',
                    'staus_code' => 404,
                ];

                return response($response, 404);
            }

            $event->delete();

            $response = [
                'data' => $id,
                'message' => 'Event deleted successfully',
                'staus_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while deleting the event',
                'error' => $exception->getMessage(),
                'staus_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showAllEvents()
    {
        try {
            $events = Event::all();

            $response = [
                'data' => $events,
                'message' => 'Events shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the events',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showEventById($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                $response = [
                    'data' => null,
                    'message' => 'Event not found',
                    'status_code' => 404,
                ];

                return response($response, 404);
            }

            $response = [
                'data' => $event,
                'message' => 'Event shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the event',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function editEvent(Request $request, $id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                $response = [
                    'data' => null,
                    'message' => 'Event not found',
                    'status_code' => 404,
                ];

                return response($response, 404);
            }

            $validator = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'payment_limit' => 'required|date',
                'price' => 'required|numeric',
                'initial_fee' => 'required|numeric',
                'terms_and_conditions' => 'required|string',
                'program_id' => 'required|exists:programs,id',
            ]);

            $event->start_date = $request->input('start_date');
            $event->end_date = $request->input('end_date');
            $event->payment_limit = $request->input('payment_limit');
            $event->price = $request->input('price');
            $event->initial_fee = $request->input('initial_fee');
            $event->terms_and_conditions = $request->input('terms_and_conditions');
            $event->program_id = $request->input('program_id');

            $event->save();

            $response = [
                'data' => $id,
                'message' => 'Event edited successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while updating the event',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }
    
}
