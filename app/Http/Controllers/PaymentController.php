<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Program;


use App\Models\Familyparent;


use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;
use Stripe\PaymentIntent;


use Dompdf\Dompdf;
use Dompdf\Options;

class PaymentController extends Controller
{
   
    public function createPayment(Request $request)
    {
        try {
            

            $validatedData = $request->validate([
                // 'invoice' => 'required|string',
                'amount' => 'required|integer',
                'event_id' => 'required|exists:events,id',
                'student_id' => 'required|exists:students,id',
            ]);

            $key_secret = env('SECRET_KEY_STRIPE');
            Stripe::setApiKey($key_secret);
            
            $paymentIntent = PaymentIntent::create([
                'amount' => $validatedData['amount'],
                'currency' => 'USD',
            ]);

            //SUCCESS
            if($paymentIntent){
                $payment = Payment::create([
                    'user_id' => auth()->user()->id,
                    // 'invoice' => $validatedData['invoice'],
                    'invoice' => $paymentIntent->id,
                    'amount' => $validatedData['amount'],
                    'event_id' => $validatedData['event_id'],
                    'student_id' => $validatedData['student_id'],
                ]);

                $response = [
                    'data' => $payment,
                    'message' => 'payment created successfully',
                    'status_code' => 201,
                ];
    
                return response($response, 201);
            }

            $response = [
                'data' => null,
                'message' => 'payment error',
                'status_code' => 400,
            ];

            return response($response, 400);

        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error ocurred while trying to create the payment',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showAllPaymentsByStudentAndEvent($student_id, $event_id)
    {
        try {
            // Obtener el usuario autenticado y su padre de familia
            $user = Auth::user();
            // $familyParent = $user->familyParents;

            // Obtener los estudiantes asociados al padre de familia
            // $students = $familyParent->students;
            
            // Obtener los pagos correspondientes al evento indicado para el estudiante especificado
            // $payments = Payment::whereIn('student_id', $students->pluck('id')->toArray())
            //                 ->where('event_id', $event_id)
            //                 ->where('student_id', $student_id)
            //                 ->get();
            error_log($user->id);
            $payments = Payment::where('user_id', '=', $user->id)
                            ->where('event_id', '=', $event_id)
                            ->where('student_id', '=', $student_id)
                            ->get();

            $response = [
                'data' => $payments,
                'message' => 'Payments shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);

        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while trying to retrieve the payments',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }

    public function showAllPaymentsToAdmin()
    {
        try {
            $payments = Payment::with(['user.familyParents', 'student', 'event.school'])->get();
            
            $response = [
                'data' => $payments,
                'message' => 'Payments shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);

        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while trying to retrieve the payments',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];

            return response($response, 500);
        }
    }



    public function showAllPaymentsByUser($userId)
    {
        try {
            $payments = Payment::where('user_id', '=', $userId)->get();
            $response = [
                'data' => $payments,
                'message' => 'Payments shown successfully',
                'status_code' => 200,
            ];

            return response($response, 200);

        } catch (QueryException $exception) {
            $response = [
                'message' => 'An error occurred while trying to retrieve the payments',
                'error' => $exception->getMessage(),
                'status_code' => 500,
            ];
            return response($response, 500);
        }
    }

    public function generatePDF(Request $request)
    {
        // $data = $request->all(); // Datos para el informe desde la solicitud API
        //dd($data);
        $payment = Payment::with([
            'user.familyParents', 
            'student',
            'event.school'
        ])->find($request->input('paymentId'));


        

        $parent = Familyparent::find($payment->student['parents_id']);

        $payment->student['parent'] = $parent;

        $program = Program::find($payment->event['program_id']);

        $payment->event['program'] = $program;

        if (!$payment) {
            $response = [
                    'data' => null,
                    'message' => 'Payment not found',
                    'status_code' => 404,
            ];

            return response($response, 404);
        }

        
        /*$response = [
            'data' => $payment,
            'message' => 'Payment not found',
            'status_code' => 404,
        ];
        return response($response, 200);
        */
        
        $pdf = $this->generatePDFContent($payment);

        // Devuelve el PDF como respuesta de la API
        return response($pdf)
            ->header('Content-Type', 'application/pdf');
    }

    private function generatePDFContent($data)
    {
        // Configuración de dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $aaa = "https://codingeducation.us/wp-content/uploads/2022/08/logo_coding.png";

        // Contenido HTML del PDF (puedes construirlo dinámicamente según tus necesidades)
        $html = '<html><body>';
        $html .= '<style>';
        $html .= '  h1 { color: red; text-align: center; }';
        $html .= '  p { font-size: 14px; }';
        $html .= '  #top-bar { width: 100%; background-color: purple; text-align: center; color: white; font-size: 20px; padding-top: 8px; padding-bottom: 8px; font-weight: bolder; position: absolute; top: 0px }';
        $html .= '  #logo-container { position: absolute; top: 50px; }';
        $html .= '  #address-container { position: absolute; top: 40px; right: 0px; }';
        $html .= '  #title-container { text-align: center; position: absolute; top: 100px; left: 42%; font-weight: bolder; }';
        $html .= '  #student-container { position: absolute; top: 140px; }';
        $html .= '  #payer-container { position: absolute; top: 160px; }';
        $html .= '  #receipt-container { right:120px; position: absolute; top: 140px; background-color: silver; border: 0.5 solid black; padding-left: 10px; padding-right: 30px; width: 80px;   }';
        $html .= '  #date-container { right:120px; position: absolute; top: 165px; background-color: silver; border: 0.5 solid black; padding-left: 10px; padding-right: 30px; width: 80px; }';
        $html .= '  #amount-container { right:120px; position: absolute; top: 190px; background-color: silver; border: 0.5 solid black; padding-left: 10px; padding-right: 30px; width: 80px; }';
        
        $html .= '  #receipt-value { position: absolute; top: 140px; right: 50px; }';
        $html .= '  #date-value { position: absolute; top: 165px; right: 30px; }';
        $html .= '  #amount-value { position: absolute; top: 190px; right: 35px; }';
        
        $html .= '  #table-container { position: absolute; top: 250px; width: 100%; }';
        
        $html .= '  #aditional-container { position: absolute; top: 360px; }';

        $html .= '  #total-container { position: absolute; top: 340px; right: 0px; }';
        
        $html .= '</style>';
        $html .= '<div id="top-bar">R E C E I P T</div>';
        $html .= '<div id="logo-container"> <img src="' . $aaa . '" alt="Imagen" style="width: 200px; height: auto;"> </div>';
        $html .= '<div id="address-container"> <h3>ADDRESS DATA</h3> </div>';
        $html .= '<div id="title-container"> R E C I P I E N T </div>';
        $html .= '<div id="student-container">Student:' . $data->student['first_name'] . ' ' . $data->student['last_name'] . '</div>';
        $html .= '<div id="payer-container">Payer:' . $data->student['parent']['fathers_name'] . '</div>';
        $html .= '<div id="receipt-container">Receipt #</div>';
        $html .= '<div id="date-container">Date</div>';
        $html .= '<div id="amount-container">Amount due</div>';


        $html .= '<div id="receipt-value">' . $data->invoice . '</div>';
        $html .= '<div id="date-value">' . $data->created_at . '</div>';
        $html .= '<div id="amount-value">$' . $data->amount . ' USD</div>';

        

        

        $html .= '<table id="table-container" border="1">
        <tr>
          <th>Quantity</th>
          <th>Description</th>
          <th>Price</th>
        </tr>
        <tr>
          <td>1</td>
          <td>' . $data->event['name'] . '</td>
          <td style="text-align: right" >$' . $data->event['price'] . 'USD</td>
        </tr>
      </table>';

        
        $html .= '<div id="aditional-container"> 
        <div style="font-weight: bolder; font-size: 14px;" >ADDITIONAL NOTES</div> 
        <div>Program:' . $data->event['program']['name'] . '</div>
        <div>Date:' . $data->event['program']['created_at'] . '</div>
        <div>School:' . $data->event['school']['name']  .'</div>
        </div>';

        $html .= '<div id="total-container">
            <table  style="border-spacing: 5px;
            border-collapse: separate;">
            <tr>
                <td style="background-color: silver; padding-right: 80px; border: 0.5 solid black; padding-top: 8px; padding-bottom: 4px; padding-left: 8px;">Total</td>
                <td style="text-align: right" >$' . $data->event['price'] .'USD</td>
            </tr>
            <tr>
                <td style="background-color: silver; padding-right: 80px; border: 0.5 solid black; padding-top: 8px; padding-bottom: 4px; padding-left: 8px;">Amount Paid</td>
                <td style="text-align: right" >$' . $data->amount . 'USD</td>
            </tr>
            <tr>
                <td style="background-color: silver; padding-right: 80px; border: 0.5 solid black; padding-top: 8px; padding-bottom: 4px; padding-left: 8px;">Balance Due</td>
                <td style="text-align: right" >$' . $data->event['price']-$data->amount . 'USD</td>
            </tr>
        </table>
        </div>';
        
        

        //$html .= '<p>Datos: ' . json_encode($data) . '</p>';
        $html .= '</body></html>';

        // Cargar contenido HTML en dompdf
        $dompdf->loadHtml($html);

        // Establecer tamaño del papel y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF (output)
        $dompdf->render();

        // Devuelve el contenido del PDF
        return $dompdf->output();
    }

}
