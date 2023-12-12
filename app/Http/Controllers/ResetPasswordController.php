<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;

use SendGrid\Mail\Mail;
use SendGrid;

class ResetPasswordController extends Controller
{
    

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return $randomString;
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


        // Obtener un string de la hora actual
        $timeString = date('YmdHis');
        $randomString = $this->generateRandomString(12);
        $combinedString = $timeString . $randomString;

        $localUrl = "http://localhost:5173/forgot-password/";
        $remoteUrl = "https://codingeducationadmin.web.app/";

        $url = $localUrl . $combinedString; 
        $datos = [
            'password' => $url,
        ];


        $passwordReset = PasswordReset::where('email', $user->email)->first();

        if ($passwordReset) {
            $passwordReset->delete();
            PasswordReset::create([
                'email' => $user->email,
                'token' => $combinedString,
            ]); 
        }else{
            PasswordReset::create([
                'email' => $user->email,
                'token' => $combinedString,
            ]);
        }

        foreach ($datos as $key => $value) {
            $email->addDynamicTemplateData($key, $value);
        }
        
        $sendgrid = new SendGrid('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');
        
        try {
            $response = $sendgrid->send($email);
            if ($response->statusCode() == 202) {
                return response()->json(['message' => 'Correo electrónico enviado con éxito', 'status_code' => 200]);
            } else {
                return response()->json(['message' => 'Error al enviar el correo electrónico', 'status_code' => 500], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar el correo electrónico: ' . $e->getMessage()], 500);
        }

    }


    public function resetPassword(Request $request)
    {
        $newPass = $request->input('password', '');
        $token = $request->input('token', '');
        
        // Buscar un registro por el campo 'token'
        $passwordReset = PasswordReset::where('token', $token)->first();

        if ($passwordReset) {

            $user = User::where('email', $passwordReset->email)->first();
        
            if(!$user){
                $response = [
                    'data' => null,
                    'message' => 'The user with the ID '.$userId.' could not be found.',
                    'status_code' => 404,
                ];

                return response($response, 404);
            }


            $user->password = bcrypt($newPass);
            $user->save();


            $passwordReset->delete();

            $response = [
                'data' => null,
                'message' => 'Usuario actualizado exitosamente',
                'status_code' => 200,
            ];
           
            return response($response, 200);            

        } else {
            $response = [
                'data' => null,
                'message' => 'Token inválido',
                'status_code' => 400,
            ];
            
            return response($response, 400);            

        }

    }
}
