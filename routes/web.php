<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaserController;
use App\Http\Controllers\IT\TicketController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PurchaseRequestController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Route::get(
//     'notifications/get',
//     [App\Http\Controllers\NotificationsController::class, 'getNotificationsData']
// )->name('notifications.get');

// Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
// Route::get('/notifications/fetch', [App\Http\Controllers\NotificationController::class, 'fetch'])->name('notifications.fetch');
// Route::get('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

Route::get('/notifications/show', [NotificationController::class, 'show'])->name('notifications.show');
Route::get('/notifications/get', [NotificationController::class, 'get'])->name('notifications.get');
Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::get('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/ticket/{ticket}', [TicketController::class, 'show'])->name('ticket.show');
});
// Route::get('/notifications/get', [App\Http\Controllers\NotificationController::class, 'get'])->name('notifications.get');

Route::get('/check-role', function () {
    $user = auth()->user();
    dd($user->roles, $user->hasRole('Administrator'));
});



Route::middleware(['auth'])->group(function () {
    Route::get('admin/dashboard', [HomeController::class, 'home'])->name('admin.dashboard');
    Route::get('/engineer/home', [App\Http\Controllers\Engineer\EngineerController::class, 'index'])->name('engineer.home');
    Route::get('/purchaser/home', [App\Http\Controllers\Purchaser\PurchaserController::class, 'home'])->name('purchaser.home');
    Route::get('/staff/home', [App\Http\Controllers\Staff\StaffController::class, 'index'])->name('staff.home');
    Route::get('/head/home', [App\Http\Controllers\Head\HeadController::class, 'home'])->name('head.home'); // Added new route for Head role
    Route::get('/IT/home', [App\Http\Controllers\IT\ITController::class, 'index'])->name('IT.home');
    Route::get('/mmo/dashboard', [App\Http\Controllers\MMO\MMOController::class, 'index'])->name('mmo.dashboard');
});

        // -----------------------------------------------------
            // Administrator Sidebar Items (Only for Administrators)
            // -----------------------------------------------------
// SMS routes 
    Route::get('admin/sms', [App\Http\Controllers\SMSController::class, 'index'])->name('admin.schedule.sms');
    Route::get('admin/sms/create', [App\Http\Controllers\SMSController::class, 'create'])->name('admin.schedule.create_sms');
    Route::post('admin/sms/store', [App\Http\Controllers\SMSController::class, 'store'])->name('admin.schedule.store_sms');
    Route::post('admin/sms/send', [App\Http\Controllers\SMSController::class, 'sendSMS'])->name('admin.schedule.send_sms');
    Route::post('/admin/schedule/send-sms', [App\Http\Controllers\SmsController::class, 'sendSms'])->name('admin.schedule.send_sms');
    Route::post('/send-sms/group', [App\Http\Controllers\SMSController::class, 'sendSMSToGroup'])->name('admin.schedule.send_sms_group');
    Route::post('/create-group', [App\Http\Controllers\SMSController::class, 'createGroup'])->name('admin.schedule.create_group');
    Route::post('/admin/sms-groups/store', [App\Http\Controllers\SMSController::class, 'createGroup'])->name('admin.sms_groups.store');
    Route::get('/admin/sms/get-recipients', [App\Http\Controllers\SMSController::class, 'getRecipients'])->name('admin.schedule.get_recipients');



    // calendar routes
    Route::get('admin/schedule', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.schedule.calendar');
    Route::post('/events', [App\Http\Controllers\HomeController::class, 'store'])->name('events.store');
    Route::get('/events', [App\Http\Controllers\HomeController::class, 'fetchEvents'])->name('events.fetch');
    Route::put('/events/{event}', [App\Http\Controllers\HomeController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\HomeController::class, 'destroy'])->name('events.destroy');   

// User Routes
    Route::get('admin/user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user.index');
    Route::get('admin/user/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
    Route::post('admin/user/create', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
    Route::get('admin/user/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('admin/user/{id}/update', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
    Route::delete('admin/user/{id}/delete', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.destroy');

// Purchaser Routes
    Route::post('admin/purchase/store', [App\Http\Controllers\PurchaserController::class, 'store'])->name('admin.purchase.store');
    Route::get('admin/purchase', [App\Http\Controllers\PurchaserController::class, 'index'])->name('admin.purchase.index');
    Route::get('admin/purchase/create', [App\Http\Controllers\PurchaserController::class, 'create'])->name('admin.purchase.create');
    Route::get('admin/purchase/{id}/edit', [App\Http\Controllers\PurchaserController::class, 'edit'])->name('admin.purchase.edit');
    Route::put('admin/purchase/{id}/update', [App\Http\Controllers\PurchaserController::class, 'update'])->name('admin.purchase.update');
    Route::post('admin/purchase/accept', [App\Http\Controllers\PurchaserController::class, 'accept'])->name('admin.purchase.accept');
    Route::post('/purchase/upload-and-accept', [PurchaserController::class, 'uploadAndAcceptOrder'])->name('purchase.uploadAndAcceptOrder');
    Route::post('admin/purchase/delete', [App\Http\Controllers\PurchaserController::class, 'delete'])->name('admin.purchase.delete');
    Route::post('admin/purchase/{id}/hold', [App\Http\Controllers\PurchaserController::class, 'hold'])->name('admin.purchase.hold');
    Route::get('admin/purchase/{id}', [App\Http\Controllers\PurchaserController::class, 'show'])->name('admin.purchase.show');

    


    // Report Routes
    Route::get('admin/reports/ticketing_report', [App\Http\Controllers\Admin\TicketReportController::class, 'index'])->name('admin.reports.ticketing_report.index');
    Route::get('admin/reports/purchase_request', [App\Http\Controllers\Admin\PRreportController::class, 'index'])->name('admin.reports.purchase_request');
    Route::get('admin/reports/purchase_order', [App\Http\Controllers\Admin\POreportController::class, 'index'])->name('admin.reports.purchase_order');

    // PR Routes
    Route::get('admin/purchase_request', [App\Http\Controllers\PurchaseRequestController::class, 'index'])->name('admin.purchase_request.index');
    Route::post('/purchase/accept', [App\Http\Controllers\PurchaseRequestController::class, 'accept'])->name('purchase.accept');
    Route::post('/purchase_requests/upload-and-accept', [PurchaseRequestController::class, 'uploadAndAccept'])->name('purchase.uploadAndAccept');

    Route::post('/purchase/delete', [App\Http\Controllers\PurchaseRequestController::class, 'delete'])->name('purchase.delete');
    Route::get('/purchase_requests/{id}', [App\Http\Controllers\PurchaseRequestController::class, 'show']);
    Route::post('/purchase_requests/{id}/hold', [App\Http\Controllers\PurchaseRequestController::class, 'hold']);


    // ticketing routes
    Route::get('admin/ticketing', [App\Http\Controllers\TicketController::class, 'index'])->name('admin.ticketing.index');
    Route::get('admin/ticketing/create', [App\Http\Controllers\TicketController::class, 'create'])->name('admin.ticketing.create');
    Route::post('admin/ticket/store', [App\Http\Controllers\TicketController::class, 'store'])->name('admin.ticketing.store');
    Route::get('/admin/ticketing/{id}', [App\Http\Controllers\TicketController::class, 'getTicketDetails'])->name('ticketing.details');
    Route::post('/tickets/{id}/accept', [App\Http\Controllers\TicketController::class, 'accept'])->name('tickets.accept');
    Route::get('admmin/ticketing/{id}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('admin.ticketing.edit');
    Route::put('admin/ticketing/{id}', [App\Http\Controllers\TicketController::class, 'update'])->name('admin.ticketing.update');
    Route::post('admin/ticketing/delete', [App\Http\Controllers\TicketController::class, 'delete'])->name('admin.ticketing.delete');
    // Route::delete('/admin/ticketing/{id}', [App\Http\Controllers\TicketController::class, 'destroy'])->name('admin.ticketing.destroy');


        // -----------------------------------------------------
            // IT Sidebar Items (Only for IT)
            // -----------------------------------------------------

            

    // User  Routes IT
    Route::get('IT/user', [App\Http\Controllers\IT\UserController::class, 'index'])->name('IT.user.index');
    Route::get('IT/user/create', [App\Http\Controllers\IT\UserController::class, 'create'])->name('IT.user.create');
    Route::post('IT/user/create', [App\Http\Controllers\IT\UserController::class, 'store'])->name('IT.user.store');
    Route::get('IT/user/{id}/edit', [App\Http\Controllers\IT\UserController::class, 'edit'])->name('IT.user.edit');
    Route::put('IT/user/{id}/update', [App\Http\Controllers\IT\UserController::class, 'update'])->name('IT.user.update');
    Route::delete('IT/user/{id}/delete', [App\Http\Controllers\IT\UserController::class, 'destroy'])->name('IT.user.destroy');
    Route::get('IT/user/{id}', [App\Http\Controllers\IT\UserController::class, 'show'])->name('IT.user.show');


    // PO Routes IT
    Route::get('IT/purchase_order', [App\Http\Controllers\IT\POController::class, 'index'])->name('IT.purchase_order.index');

    // Report Routes
    Route::get('IT/reports/ticketing_report', [App\Http\Controllers\IT\TicketReportController::class, 'index'])->name('IT.reports.ticketing_report.index');


    // Ticketing Routes IT
    Route::get('IT/ticketing', [App\Http\Controllers\IT\TicketController::class, 'index'])->name('IT.ticketing.index');
    Route::post('/tickets/delete', [App\Http\Controllers\IT\TicketController::class, 'delete'])->name('ticketing.delete');
    // Route::post('/IT/tickets/{id}/acceptTicket', [App\Http\Controllers\IT\TicketController::class, 'acceptTicket'])->name('IT.tickets.accept');
    Route::post('/tickets/{id}/acceptTicket', [TicketController::class, 'acceptTicket'])->name('tickets.accept');
    Route::post('/IT/tickets/{id}/complete', [TicketController::class, 'complete']);
    // Route::get('/IT/ticketing/{id}', [TicketController::class, 'TicketController@show']);
    Route::get('IT/ticketing/create', [App\Http\Controllers\IT\TicketController::class, 'create'])->name('IT.ticketing.create');
    Route::post('IT/ticketing', [App\Http\Controllers\IT\TicketController::class, 'store'])->name('IT.ticketing.store');
    Route::post('/IT/tickets/{id}/complete', [App\Http\Controllers\IT\TicketController::class, 'complete']);
    Route::get('/IT/ticketing/{id}', [App\Http\Controllers\IT\TicketController::class, 'getTicketDetails'])->name('ticketing.details');
    Route::post('/IT/tickets/delete', [App\Http\Controllers\IT\TicketController::class, 'delete'])->name('IT.tickets.delete');

    Route::get('/IT/borrower', [App\Http\Controllers\IT\BorrowerController::class, 'index'])->name('IT.borrower.index');

      // -----------------------------------------------------
            // Purchaser Sidebar Items (Only for Purchaser)
            // -----------------------------------------------------

// Purchaser ACCESS Routes
    Route::get('purchaser/purchase', [App\Http\Controllers\Purchaser\PurchaserController::class, 'index'])->name('purchaser.purchase.index');
    Route::get('purchase/create', [App\Http\Controllers\Purchaser\PurchaserController::class, 'create'])->name('purchaser.purchase.create');
    Route::post('purchaser/purchase/store', [App\Http\Controllers\Purchaser\PurchaserController::class, 'store'])->name('purchaser.purchase.store');
    Route::get('purchaser/{id}/edit', [App\Http\Controllers\Purchaser\PurchaserController::class, 'edit'])->name('purchaser.purchase.edit');
    Route::put('purchaser/{id}/update', [App\Http\Controllers\Purchaser\PurchaserController::class, 'update'])->name('purchaser.purchase.update');
    Route::get('/purchase_requests/{id}', [App\Http\Controllers\PurchaseRequestController::class, 'show']);

    // Route::delete('/purchaser/purchase/{id}', [PurchaserController::class, 'destroy'])->name('purchaser.purchase.destroy');
    // Route::post('/purchaser/purchase', [PurchaserController::class, 'destroy'])->name('purchaser.purchase.destroy');
    // Route::delete('/purchaser/purchase/{id}', [App\Http\Controllers\Purchaser\PurchaserController::class, 'destroy'])->name('purchaser.purchase.destroy');


// PR access route

    Route::get('purchaser/purchase_request', [App\Http\Controllers\Purchaser\PurchaseRequestController::class, 'index'])->name('purchaser.purchase_request.index');
    Route::get('purchaser/purchase_request/{id}/edit', [App\Http\Controllers\Purchaser\PurchaseRequestController::class, 'edit'])->name('purchaser.purchase_request.edit');
    Route::put('purchaser/purchase_request/{id}', [App\Http\Controllers\Purchaser\PurchaseRequestController::class, 'update'])->name('purchaser.purchase_request.update');

 
     // -----------------------------------------------------
            // Engineer Sidebar Items (Only for Engineers)
            // -----------------------------------------------------

// Engineer Routes Access
    Route::get('engineer/ticketing', [App\Http\Controllers\Engineer\TicketController::class, 'index'])->name('engineer.ticketing.index');
    // Route::post('/engineer/accept', [App\Http\Controllers\Engineer\PurchaseRequestController::class, 'accept'])->name('engineer.accept');
    Route::post('/engineer/tickets/{id}/accept', [App\Http\Controllers\Engineer\TicketController::class, 'accept'])->name('engineer.tickets.accept');
    Route::post('/engineer/tickets/{id}/complete', [App\Http\Controllers\Engineer\TicketController::class, 'complete']);
    Route::post('/engineer/tickets/delete', [App\Http\Controllers\Engineer\TicketController::class, 'delete'])->name('engineer.tickets.delete');
    Route::get('engineer/ticketing/create', [App\Http\Controllers\Engineer\TicketController::class, 'create'])->name('engineer.ticketing.create');
    Route::post('engineer/ticketing', [App\Http\Controllers\Engineer\TicketController::class, 'store'])->name('engineer.ticketing.store');
    // Engineer Report 
    Route::get('engineer/ticketing_report', [App\Http\Controllers\Engineer\TicketReportController::class, 'index'])->name('engineer.reports.ticketing_report.index');
    Route::get('engineer/ticketing_report', [App\Http\Controllers\Engineer\TicketReportController::class, 'index'])->name('engineer.reports.ticketing_report.index');

    // Engineer PMS
    Route::get('engineer/pms', [App\Http\Controllers\Engineer\PmsController::class, 'index'])->name('engineer.pms.index');
    Route::get('engineer/pms/create', [App\Http\Controllers\Engineer\PmsController::class, 'create'])->name('engineer.pms.create');
       // -----------------------------------------------------
            // Staff Sidebar Items (Only for Staff)
            // -----------------------------------------------------

//Staff Routes
    Route::get('staff/ticketing', [App\Http\Controllers\Staff\TicketController::class, 'index'])->name('staff.ticketing.index');
    Route::get('staff/ticketing/create', [App\Http\Controllers\Staff\TicketController::class, 'create'])->name('staff.ticketing.create');
    Route::post('staff/ticketing', [App\Http\Controllers\Staff\TicketController::class, 'store'])->name('staff.ticketing.store');

    Route::get('staff/ticketing/{id}/edit', [App\Http\Controllers\Staff\TicketController::class, 'edit'])->name('staff.ticketing.edit');
    Route::put('/staff/ticketing/{id}', [App\Http\Controllers\Staff\TicketController::class, 'update'])->name('staff.ticketing.update');
    Route::get('/tickets/{id}', [App\Http\Controllers\Staff\TicketController::class, 'show']);
    Route::get('/staff/ticketing/print/{id}', [App\Http\Controllers\Staff\TicketController::class, 'print'])->name('staff.ticketing.print');

    
  // -----------------------------------------------------
            // Head Sidebar Items (Only for Head Request)
            // -----------------------------------------------------

// calendar Route
    Route::get('head/calendar', [App\Http\Controllers\Head\CalendarController::class, 'index'])->name('head.calendar.index');
    Route::post('head/calendar/store', [App\Http\Controllers\Head\CalendarController::class, 'store'])->name('head.calendar.store');
    Route::get('head/calendar/fetch', [App\Http\Controllers\Head\CalendarController::class, 'fetchEvents'])->name('head.calendar.fetch');
    Route::put('head/calendar/{event}', [App\Http\Controllers\Head\CalendarController::class, 'update'])->name('head.calendar.update');
    Route::delete('head/calendar/{event}', [App\Http\Controllers\Head\CalendarController::class, 'destroy'])->name('head.calendar.destroy');

    // Purchase Request Route
    Route::get('head/purchase_request', [App\Http\Controllers\Head\PurchaseRequestController::class, 'index'])->name('head.purchase_request.index');
    Route::get('head/purchase_request/create', [App\Http\Controllers\Head\PurchaseRequestController::class, 'create'])->name('head.purchase_request.create');
    Route::post('head/purchase_request', [App\Http\Controllers\Head\PurchaseRequestController::class, 'store'])->name('head.purchase_request.store');
    Route::get('head/purchase_requests/{id}', [App\Http\Controllers\Head\PurchaseRequestController::class, 'show']);
    
    // ticketing routes
    Route::get('head/ticketing', [App\Http\Controllers\Head\TicketController::class, 'index'])->name('head.ticketing.index');
    Route::get('head/ticketing/create', [App\Http\Controllers\Head\TicketController::class, 'create'])->name('head.ticketing.create');
    Route::post('head/ticketing', [App\Http\Controllers\Head\TicketController::class, 'store'])->name('head.ticketing.store');
    Route::get('head/ticketing/{id}/edit', [App\Http\Controllers\Head\TicketController::class, 'edit'])->name('head.ticketing.edit');
    Route::put('head/ticketing/{id}', [App\Http\Controllers\Head\TicketController::class, 'update'])->name('head.ticketing.update');
    Route::get('head/ticketing/{id}', [App\Http\Controllers\Head\TicketController::class, 'show'])->name('head.ticketing.show');
    Route::post('head/ticketing/delete', [App\Http\Controllers\Head\TicketController::class, 'delete'])->name('head.ticketing.delete');
    Route::get('/head/ticketing/print/{id}', [App\Http\Controllers\Head\TicketController::class, 'print'])->name('head.ticketing.print');

    // borrow routes
    Route::get('head/borrow', [App\Http\Controllers\Head\BorrowController::class, 'index'])->name('head.borrow.index');
    Route::get('head/borrow/create', [App\Http\Controllers\Head\BorrowController::class, 'create'])->name('head.borrow.create');
    Route::post('head/borrow', [App\Http\Controllers\Head\BorrowController::class, 'store'])->name('head.borrow.store');
    Route::get('head/borrow/{id}/edit', [App\Http\Controllers\Head\BorrowController::class, 'edit'])->name('head.borrow.edit');
    Route::put('head/borrow/{id}', [App\Http\Controllers\Head\BorrowController::class, 'update'])->name('head.borrow.update');
    Route::get('head/borrow/{id}', [App\Http\Controllers\Head\BorrowController::class, 'show'])->name('head.borrow.show');
    Route::post('head/borrow/delete', [App\Http\Controllers\Head\BorrowController::class, 'delete'])->name('head.borrow.delete');
       // -----------------------------------------------------
            // MMO Sidebar Items (Only for MMO)
            // -----------------------------------------------------

            Route::get('mmo/ticketing', [App\Http\Controllers\MMO\TicketController::class, 'index'])->name('mmo.ticketing.index');
            Route::get('mmo/ticketing/create', [App\Http\Controllers\MMO\TicketController::class, 'create'])->name('mmo.ticketing.create');
            Route::post('mmo/ticketing', [App\Http\Controllers\MMO\TicketController::class, 'store'])->name('mmo.ticketing.store');
            Route::get('mmo/ticketing/{id}/edit', [App\Http\Controllers\MMO\TicketController::class, 'edit'])->name('mmo.ticketing.edit');
            Route::put('mmo/ticketing/{id}', [App\Http\Controllers\MMO\TicketController::class, 'update'])->name('mmo.ticketing.update');
            Route::get('mmo/schedule', [App\Http\Controllers\MMO\CalendarController::class, 'index'])->name('mmo.schedule.calendar');
            Route::get('mmo/reports/ticketing_report', [App\Http\Controllers\MMO\TicketReportController::class, 'index'])->name('mmo.reports.ticketing_report.index');
            Route::get('mmo/reports/purchase_request', [App\Http\Controllers\MMO\PRreportController::class, 'index'])->name('mmo.reports.purchase_request');
            Route::get('mmo/reports/purchase_order', [App\Http\Controllers\MMO\POreportController::class, 'index'])->name('mmo.reports.purchase_order');
        
            // PR Routes
                // Purchase Request Route
            Route::get('mmo/purchase_request', [App\Http\Controllers\MMO\PurchaseRequestController::class, 'index'])->name('mmo.purchase_request.index');
            Route::get('mmo/purchase_request/create', [App\Http\Controllers\MMO\PurchaseRequestController::class, 'create'])->name('mmo.purchase_request.create');
            Route::post('mmo/purchase_request', [App\Http\Controllers\MMO\PurchaseRequestController::class, 'store'])->name('mmo.purchase_request.store');
            Route::get('mmo/purchase_requests/{id}', [App\Http\Controllers\MMO\PurchaseRequestController::class, 'show']);
            