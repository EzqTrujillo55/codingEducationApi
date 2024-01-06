<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function createProgram(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'name' => 'required|max:255',
                'description' => 'required',
                'image_url' => 'required',
            ]);

            // Creación de un nuevo programa en la base de datos
            $program = new Program();
            $program->name = $request->name;
            $program->description = $request->description;
            $program->image_url = $request->image_url;
            //$program->image_url = "img_url_aws";
            $program->save();

            $response = [
                'data' => $program,
                'message' => 'Program created succefully',
                'status_code' => 201,
            ];

            return response($response, 201);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'Ha ocurrido un error al intentar iniciar sesion',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function deleteProgram($id)
    {
        try {
            $program = Program::find($id);

            if (!$program) {
                $response = [
                    'data' => null,
                    'message' => 'program not found',
                    'staus_code' => 404,
                ];

                return response($response, 404);
            }

            $program->delete();

            $response = [
                'data' => $id,
                'message' => 'Program deleted successfully',
                'staus_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while deleting the program',
                'error' => $exception->getMessage(),
                'staus_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showAllPrograms()
    {
        try {
            $programs = Program::with('events')->get();

            $response = [
                'data' => $programs,
                'message' => 'Programs show successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the programs',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showProgramById($id)
    {
        try {
            $program = Program::with('events')->find($id);

            if (!$program) {
                $response = [
                    'data' => null,
                    'message' => 'Program not found',
                    'staus_code' => 404,
                ];

                return response($response, 404);
            }

            $response = [
                'data' => $program,
                'message' => 'Program show successfully',
                'staus_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the program',
                'error' => $exception->getMessage(),
                'staus_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function editProgram(Request $request, $id)
    {
        try {
            $program = Program::find($id);

            if (!$program) {
                $response = [
                    'data' => null,
                    'message' => 'program not found',
                    'staus_code' => 404,
                ];

                return response($response, 404);
            }

            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image_url' => 'required|nullable|url',
            ]);

            $program->name = $request->input('name');
            $program->description = $request->input('description');
            $program->image_url = $request->input('image_url');

            $program->save();

            $response = [
                'data' => $id,
                'message' => 'program edited succefully',
                'staus_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while updating the program',
                'error' => $exception->getMessage(),
                'staus_code' => 500,
            ];

            return response($response, 500);
        }
    }
}
