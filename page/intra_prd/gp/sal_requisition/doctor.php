<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorSessionController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\HolidayContoller;
use App\Http\Controllers\LiveConsultationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\RequisitionController;
use Illuminate\Support\Facades\Route;

Route::prefix('doctors')->name('doctors.')->middleware('auth', 'xss', 'checkUserStatus', 'role:doctor')->group(function () {

    Route::get('/patients-detail/{patient}', [PatientController::class, 'show'])->name('patient.detail');

        //doctor dashboard route
    Route::get('/dashboard', [DashboardController::class, 'doctorDashboard'])->name('dashboard');
    Route::get('/doctor-dashboard',
            [DashboardController::class, 'getDoctorAppointment'])->name('appointment.dashboard');
	Route::post('/getdoctorschedule', [DashboardController::class, 'doctorSchedule'])->name('schedule');
    Route::post('attendance-track', [DashboardController::class, 'DoctorAttendanceTrack'])
    ->name('attendance.track');
	// Doctor Session Routes
    Route::resource('doctor-sessions', DoctorSessionController::class);
    Route::get('get-slot-by-gap', [DoctorSessionController::class, 'getSlotByGap'])->name('get.slot.by.gap');
    Route::get('doctor-schedule-edit',
            [DoctorSessionController::class, 'doctorScheduleEdit'])->name('doctor.schedule.edit');

    //Doctor Appointment route
    Route::get('appointments', [AppointmentController::class, 'doctorAppointment'])->name('appointments');
    Route::post('appointments', [AppointmentController::class, 'doctorAppointment'])->name('appointments');
    Route::get('total-appointments', [AppointmentController::class, 'doctorTotalAppointment'])->name('total-appointments');
    Route::get('today-appointments', [AppointmentController::class, 'doctorTodaysAppointment'])->name('today-appointments');
    Route::get('appointments-calendar',
            [AppointmentController::class, 'doctorAppointmentCalendar'])->name('appointments.calendar');
    Route::get('appointments/{appointment}',
            [AppointmentController::class, 'appointmentDetail'])->name('appointment.detail');

    //Visit route
    Route::resource('visits', VisitController::class);
    Route::post('add-problem', [VisitController::class, 'addProblem'])->name('visits.add.problem');
    Route::post('add-more-symtom', [VisitController::class, 'addMoreSymtom'])->name('visits.add.moresymptom');
    Route::post('add-more-medicine', [VisitController::class, 'addMoreMedicine'])->name('visits.add.moremedicine');    
    Route::post('delete-problem/{problem}',
            [VisitController::class, 'deleteProblem'])->name('visits.delete.problem');
    Route::post('add-observation-details', [VisitController::class, 'addObservationDetails'])->name('visits.add.observationdetails');
    Route::post('add-observation', [VisitController::class, 'addObservation'])->name('visits.add.observation');
    Route::post('delete-observation/{observation}',
            [VisitController::class, 'deleteObservation'])->name('visits.delete.observation');
    Route::post('add-note', [VisitController::class, 'addNote'])->name('visits.add.note');
    Route::post('add-diagnosis', [VisitController::class, 'addDiagnosis'])->name('visits.add.diagnosis');
    Route::post('delete-diagnosis/{id}', [VisitController::class, 'deleteDiagnosis'])->name('visits.delete.diagnosis');
    
    
    Route::get('show-medicine', [VisitController::class, 'ShowMedicine'])->name('show.medicine');
    Route::post('delete-note/{note}', [VisitController::class, 'deleteNote'])->name('visits.delete.note');
    Route::post('add-prescription', [VisitController::class, 'addPrescription'])->name('visits.add.prescription');
    Route::post('delete-prescription/{prescription}',
            [VisitController::class, 'deletePrescription'])->name('visits.delete.prescription');
    Route::get('edit-prescription/{prescription}',
            [VisitController::class, 'editPrescription'])->name('visits.edit.prescription');
    Route::get('pdf/{visitid}', [VisitController::class, 'generatePdf'])->name('visits.pdf');
    #Route::get('downloadpdf/{visitid}', [VisitController::class, 'downloadPdf'])->name('visits.downloadpdf');
    Route::post('appointments/{appointment}',
            [AppointmentController::class, 'changeStatus'])->name('change-status');
    Route::post('appointments-payment/{id}',
            [AppointmentController::class, 'changePaymentStatus'])->name('change-payment-status');
    Route::get('patient-appointments',
            [PatientController::class, 'patientAppointment'])->name('patients.appointment');
    Route::get('appointments/{appointment}',
            [AppointmentController::class, 'show'])->name('appointment.detail');
    Route::get('doctors/{doctor}', [UserController::class, 'show'])->name('doctors.detail');
    Route::get('doctors-appointment',
            [UserController::class, 'doctorAppointment'])->name('doctors.appointment');

    Route::get('connect-google-calendar',
            [GoogleCalendarController::class, 'googleCalendar'])->name('googleCalendar.index');
    Route::get('disconnect-google-calendar',
            [GoogleCalendarController::class, 'disconnectGoogleCalendar'])->name('disconnectCalendar.destroy');
    Route::post('appointment-google-calendar', [
        GoogleCalendarController::class, 'appointmentGoogleCalendarStore',
    ])->name('appointmentGoogleCalendar.store');

    //Zoom Route
    Route::resource('live-consultations', LiveConsultationController::class);
    Route::get('live-consultation-list',
            [LiveConsultationController::class, 'getTypeNumber'])->name('live.consultation.list');
    Route::post('live-consultation/change-status',
            [LiveConsultationController::class, 'getChangeStatus'])->name('live.consultation.change.status');
    Route::get('live-consultation/{liveConsultation}/start',
            [LiveConsultationController::class, 'getLiveStatus'])->name('live.consultation.get.live.status');
    Route::get('live-consultation/{liveConsultation}',
            [LiveConsultationController::class, 'show'])->name('live.consultation.show');
    Route::get('user-zoom-credential/{userZoomCredential}/fetch',
            [LiveConsultationController::class, 'zoomCredential'])->name('zoom.credential');
    Route::post('user-zoom-credential',
            [LiveConsultationController::class, 'zoomCredentialCreate'])->name('zoom.credential.create');

    //Transactions route
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    //Holiday Route
    Route::get('holidays', [HolidayContoller::class, 'holiday'])->name('holiday');
    Route::get('holidays/create', [HolidayContoller::class, 'doctorCreate'])->name('holiday-create');
    Route::post('holidays/create', [HolidayContoller::class, 'doctorStore'])->name('holiday-store');
    Route::delete('holidays/delete/{holiday}', [HolidayContoller::class, 'doctorDestroy'])->name('holiday-destroy');
    Route::get('requisition', [RequisitionController::class, 'index'])->name('requisition');
    Route::post('doctor-med-quantity', [RequisitionController::class, 'Medquantity'])->name('doctor-med-quantity');
    Route::post('req-send', [RequisitionController::class, 'RequisitionSend'])->name('req-send');
    Route::get('req-send-list', [RequisitionController::class, 'RequisitionSendList'])->name('req-send-list');
    Route::get('doctor-feedback', [TestController::class, 'DoctorFeedback'])->name('doctor-feedback');
	Route::post('doctor-feedback-submit', [TestController::class, 'submitDoctorFeedback'])->name('doctor-feedback-submit');
});

Route::prefix('doctors')->name('doctors.')->group(function () {
Route::get('downloadpdf/{visitid}', [VisitController::class, 'downloadPdf'])->name('visits.downloadpdf');
});