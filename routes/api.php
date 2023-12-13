<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SchoolHasEventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SchoolHasProgramController;
use App\Http\Controllers\ResetPasswordController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route::get('/checkout', [CheckoutController::class, 'createCheckoutSession']);

// Show all school and representative
Route::get('/show-all-school-representative', [SchoolController::class, 'showAllSchoolAndRepresentative']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Program routes
    // Create Program
    Route::post('/create-program', [ProgramController::class, 'createProgram']);
    // Delete Program
    Route::delete('/delete-program/{id}', [ProgramController::class, 'deleteProgram']);
    // Show all programs
    Route::get('/show-all-programs', [ProgramController::class, 'showAllPrograms']);
    // Show program by id
    Route::get('/show-program/{id}', [ProgramController::class, 'showProgramById']);
    // Edit program
    Route::put('/edit-program/{id}', [ProgramController::class, 'editProgram']);
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    // Event routes
    // Create event
    Route::post('/create-event', [EventController::class, 'createEvent']);

    // Delete Event
    Route::delete('/delete-event/{id}', [EventController::class, 'deleteEvent']);

    // Show all events
    Route::get('/show-all-events', [EventController::class, 'showAllEvents']);

    // Show event by id
    Route::get('/show-event/{id}', [EventController::class, 'showEventById']);
    
    // Edit event
    Route::put('/edit-event/{id}', [EventController::class, 'editEvent']);

    Route::get('/events/{eventId}/students', [EventController::class, 'getStudentsByEventId']);

});

Route::group(['middleware' => 'auth:sanctum'], function () {
    // School and representative routes
    // Create school and representative
    Route::post('/create-school-representative', [SchoolController::class, 'createSchoolAndRepresentative']);
    // Delete school and representative
    Route::delete('/delete-school-representative/{id}', [SchoolController::class, 'deleteSchoolAndRepresentativeBySchoolId']);
    // Show school and representative by school id
    Route::get('/show-school-representative/{id}', [SchoolController::class, 'showSchoolAndRepresentativeBySchoolId']);
    // Edit school and representative
    Route::put('/edit-school-representative/{id}', [SchoolController::class, 'editSchoolAndRepresentativeBySchoolId']);
    // Show all school and events
    Route::get('/show-all-school-events/{id}', [SchoolController::class, 'showAllSchoolsWithEvents']);

    Route::get('/show-all-school-students/{id}', [SchoolController::class, 'showAllSchoolWithStudents']);

    // Assign event to school 
    Route::post('/assign-event-to-school', [SchoolHasEventController::class, 'assignEvent']);
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    // Payment routes
    // Create payment
    Route::post('/create-payment', [PaymentController::class, 'createPayment']);
    // Show payments
    Route::get('/show-all-payment/student/{student_id}/event/{event_id}', [PaymentController::class, 'showAllPaymentsByStudentAndEvent']);
    // Show all payments to admin
    Route::get('/show-all-payments-to-admin', [PaymentController::class, 'showAllPaymentsToAdmin']);

    Route::get('/show-all-payment/user/{userId}', [PaymentController::class, 'showAllPaymentsByUser']); 
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    // User routes
    Route::put('update-role-user/{id}', [UserController::class, 'updateRoleUser' ]);
    // Show user and parents info
    Route::get('/show-all-user-parents-info', [UserController::class, 'showAllUserAndParentsInfo']);
    Route::get('/show-user-logged-parents-info', [UserController::class, 'showUserLoggedParentsInfo']);
    // Edit user and parents info
    Route::patch('/edit-user-parents-info/{id}', [UserController::class, 'editUserAndParentsInfo']);
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    // Student routes
    // Show all students
    Route::get('/show-all-students', [StudentController::class, 'showStudents']);

    // Show all students logged
    Route::get('/show-students-logged', [StudentController::class, 'showStudentsByUserLogged']);

    // Create new student and event with user logged info
    Route::post('/create-student-with-event', [StudentController::class, 'createNewStudentAndEventWithUserLoggedInfo']);

    // Create new student and assign to a exist acount
    Route::post('/create-student-assign-to-user', [StudentController::class, 'assigningAstudenttoAuser']);

    // Show students by id for current user logged
    Route::get('/show-students-current-user-looged/{id}', [StudentController::class, 'showStudentById']);

    // Edit students by id for current user logged
    Route::patch('/edit-students-current-user-looged/{id}', [StudentController::class, 'editStudentById']);

    // Edit students by id for current user logged
    Route::delete('/delete-student/{id}', [StudentController::class, 'deleteStudentById']);
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    // Assign program to school 
    Route::post('/assign-program-to-school', [SchoolHasProgramController::class, 'assignProgram']);

    Route::post('/registerAdmin', [AuthController::class, 'registerAdmin']);

    Route::post('/deleteUsers', [UserController::class, 'deleteUsers']);

    Route::post('/sendRecoverEmail', [UserController::class, 'sendRecoverEmail']);

});

Route::post('/sendRecoverEmail', [ResetPasswordController::class, 'sendRecoverEmail']);
Route::post('/resetPassword', [ResetPasswordController::class, 'resetPassword']);

Route::get('/migrate', function () {
    defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
    Artisan::call('migrate');
    dd('migrated!');
});


Route::post('/generate-pdf', [PaymentController::class, 'generatePDF']);
