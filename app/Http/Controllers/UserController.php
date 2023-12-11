<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Familyparent;
use Illuminate\Http\Request;

use SendGrid\Mail\Mail;
use SendGrid;


class UserController extends Controller
{
    public function showAllUserAndParentsInfo()
    {
        try {
            $users = User::with('familyparents')->get();

            $response = [
                'data' => $users,
                'message' => 'users and family parents shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the users and family parents',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }
    public function showUserLoggedParentsInfo()
    {
        try {
            $user = auth()->user();

            $response = [
                'data' => $user->familyparents,
                'message' => 'family parents shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);
        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while fetching the users and family parents',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function editUserAndParentsInfo(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                
                'mothers_name' => 'required|string|max:255',
                'mothers_phone' => 'required|string|max:255',
                'mothers_email' => 'required|string|email',
                'fathers_name' => 'required|string|max:255',
                'fathers_phone' => 'required|string|max:255',
                'fathers_email' => 'required|string|email',
            ]);

            $user = User::find($id);

            if(!$user){
                $response = [
                    'data' => null,
                    'message' => 'The user with the ID '.$id.' could not be found.',
                    'status_code' => 404,
                ];

                return response($response, 404);
            }

            $familyParents = FamilyParent::where('user_id', $id)->firstOrFail();

            // Actualizamos los valores del usuario
            // $user->email = $request->input('email', $user->email);
            // $user->password = $request->input('password', $user->password);
            // $user->save();

            // Actualizamos los valores de los padres
            $familyParents->mothers_name = $request->input('mothers_name', $familyParents->mothers_name);
            $familyParents->mothers_phone = $request->input('mothers_phone', $familyParents->mothers_phone);
            $familyParents->mothers_email = $request->input('mothers_email', $familyParents->mothers_email);
            
            $familyParents->fathers_name = $request->input('fathers_name', $familyParents->fathers_name);
            $familyParents->fathers_phone = $request->input('fathers_phone', $familyParents->fathers_phone);
            $familyParents->fathers_email = $request->input('fathers_email', $familyParents->fathers_email);
            $familyParents->save();

            $familyParents = FamilyParent::where('user_id', $id)->firstOrFail();

            $response = [
                'data' => $familyParents,
                'message' => 'User and family parents edited successfully',
                'status_code' => 200,
            ];

            return response($response, 200);

        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while editing the user and family parents',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }


    public function updateRoleUser(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'role' => 'required|string|max:255',
            ]);

            $user = User::find($id);

            if(!$user){
                $response = [
                    'data' => null,
                    'message' => 'The user with the ID '.$id.' could not be found.',
                    'status_code' => 404,
                ];

                return response($response, 404);
            }


            $user->role = $request->input('role', $user->role);
            $user->save();
           
            $response = [
                'data' => $user,
                'message' => 'User role edited successfully',
                'status_code' => 200,
            ];

            return response($response, 200);

        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while editing the user and family parents',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }


    public function deleteUsers(Request $request)
    {
        try {
            $ids = $request->input('ids', []); // Obtén el arreglo de IDs desde la solicitud

            // Verifica si hay IDs proporcionados
            if (count($ids) > 0) {
                // Actualiza la columna 'deleted' a true para los usuarios con los IDs proporcionados
                User::whereIn('id', $ids)->update(['deleted' => true]);
    
                return response()->json(['message' => 'Usuarios actualizados con éxito', 'status_code' => 200], 200);
            } else {
                return response()->json(['message' => 'No se proporcionaron IDs válidos'], 422);
            }


        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while editing the user and family parents',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function sendRecoverEmail(Request $request)
    {
        $userId = $request->input('userId', '');

        $user = User::find($userId);

        if(!$user){
            $response = [
                'data' => null,
                'message' => 'The user with the ID '.$userId.' could not be found.',
                'status_code' => 404,
            ];

            return response($response, 404);
        }

        $email = new Mail();
        $email->setFrom("codingeducationdev@gmail.com", "Coding Education");
        $email->addTo($user->email, $user->email);
        $email->setTemplateId("d-3ddeebd1675c48859d102c51e83aa4d2");

        $datos = [
            'password' => $user->password,
        ];

        foreach ($datos as $key => $value) {
            $email->addDynamicTemplateData($key, $value);
        }
        
        $sendgrid = new SendGrid('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');
        
        try {
            $response = $sendgrid->send($email);
            if ($response->statusCode() == 202) {
                return response()->json(['message' => 'Correo electrónico enviado con éxito']);
            } else {
                return response()->json(['message' => 'Error al enviar el correo electrónico'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar el correo electrónico: ' . $e->getMessage()], 500);
        }

    }



}
