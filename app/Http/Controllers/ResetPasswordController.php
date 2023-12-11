<?php

namespace App\Http\Controllers;

use App\Models\User;
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


        $randomString = $this->generateRandomString(12);
        $url = "http://localhost:5173/forgot-password/" . $randomString; 
        $datos = [
            'password' => $url,
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
