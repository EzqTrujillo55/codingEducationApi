<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
   
    public function createPayment(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'invoice' => 'required|string',
                'amount' => 'required|integer',
                'event_id' => 'required|exists:events,id',
                'student_id' => 'required|exists:students,id',
            ]);

            $payment = Payment::create([
                'user_id' => auth()->user()->id,
                'invoice' => $validatedData['invoice'],
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
            $payments = Payment::with(['user.familyParents.students'])->get();
            
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

}
