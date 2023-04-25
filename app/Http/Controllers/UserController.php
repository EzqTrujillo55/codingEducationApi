<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Familyparent;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function showUserAndParentsInfo()
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

    public function editUserAndParentsInfo(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                
                'mothers_name' => 'required|string|max:255',
                'mothers_phone' => 'required|string|max:255',
                'mothers_email' => 'required|string|email|unique:familyparents,mothers_email',
                'fathers_name' => 'required|string|max:255',
                'fathers_phone' => 'required|string|max:255',
                'fathers_email' => 'required|string|email|unique:familyparents,fathers_email',
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
            $user->email = $request->input('email', $user->email);
            $user->password = $request->input('password', $user->password);
            $user->save();

            // Actualizamos los valores de los padres
            $familyParents->mothers_name = $request->input('mothers_name', $familyParents->mothers_name);
            $familyParents->mothers_phone = $request->input('mothers_phone', $familyParents->mothers_phone);
            $familyParents->mothers_email = $request->input('mothers_email', $familyParents->mothers_email);
            
            $familyParents->fathers_name = $request->input('fathers_name', $familyParents->fathers_name);
            $familyParents->fathers_phone = $request->input('fathers_phone', $familyParents->fathers_phone);
            $familyParents->fathers_email = $request->input('fathers_email', $familyParents->fathers_email);
            $familyParents->save();

            $response = [
                'data' => $user,
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

}
