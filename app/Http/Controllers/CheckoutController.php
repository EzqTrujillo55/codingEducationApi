<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function createCheckoutSession()
    {
        // error_log("entra");
        // error_log(env('SECRET_KEY_STRIPE'));
        // try {
        //     $key_secret = env('SECRET_KEY_STRIPE');
        //     Stripe::setApiKey($key_secret);

        //     $session = Session::create([
        //         'payment_method_types' => ['card'],
        //         'line_items' => [[
        //             'price_data' => [
        //                 'currency' => 'usd',
        //                 'product_data' => [
        //                     'name' => 'Producto Ejemplo',
        //                 ],
        //                 'unit_amount' => 2000, // Monto en centavos (ejemplo: $20)
        //             ],
        //             'quantity' => 1,
        //         ]],
        //         'mode' => 'payment',
        //         'success_url' => 'https://tu-sitio.com/exito',
        //         'cancel_url' => 'https://tu-sitio.com/cancelado',
        //     ]);

        //     $sessionId = $session->id;

        //     return $sessionId;
        // } catch (ApiErrorException $e) {
        //     // Maneja los errores de Stripe
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
    }
}
