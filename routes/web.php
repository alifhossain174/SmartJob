<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Auth::routes();
Auth::routes([
    'login' => true,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('ckeditor', 'CkeditorController@index');
Route::post('ckeditor/upload', 'CkeditorController@upload')->name('ckeditor.upload');

Route::get('mining/process/start', [App\Http\Controllers\MiningController::class, 'startMining']);


Route::group(['middleware' => ['auth', 'CheckUser']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/users/lists', [App\Http\Controllers\HomeController::class, 'usersList'])->name('usersList');
    Route::post('/make/user/banned', [App\Http\Controllers\HomeController::class, 'bannedUsers']);
    Route::get('/unban/user/{id}', [App\Http\Controllers\HomeController::class, 'unbanUser']);
    Route::get('/configure/settings', [App\Http\Controllers\HomeController::class, 'configureSettings']);
    Route::post('/update/settings', [App\Http\Controllers\HomeController::class, 'updateSettings']);
    Route::post('/send/notification', [App\Http\Controllers\HomeController::class, 'sendNotification']);
    Route::get('change/account/password', [App\Http\Controllers\HomeController::class, 'changePasswordPage']);
    Route::post('chnage/my/password', [App\Http\Controllers\HomeController::class, 'changeMyPassword']);
    Route::post('count/website/visit', [App\Http\Controllers\HomeController::class, 'countWebsiteVisit']);


    Route::get('/package/page', [App\Http\Controllers\PackageController::class, 'packagePage']);
    Route::post('/add/new/package', [App\Http\Controllers\PackageController::class, 'addNewPackage']);
    Route::get('/delete/package/{id}', [App\Http\Controllers\PackageController::class,'deletePackage']);
    Route::get('/edit/package/{id}', [App\Http\Controllers\PackageController::class,'editPackage']);
    Route::post('/update/package', [App\Http\Controllers\PackageController::class,'updatePackage']);
    Route::get('/manage/package', [App\Http\Controllers\PackageController::class, 'managePakcage']);
    Route::get('/delete/package/request/{id}', [App\Http\Controllers\PackageController::class,'deletePackageRequest']);
    Route::get('/deny/package/request/{id}', [App\Http\Controllers\PackageController::class,'denyPackageRequest']);
    Route::get('/approve/package/request/{id}', [App\Http\Controllers\PackageController::class,'approvePackageRequest']);

    //payment
    Route::get('/payment/page', [App\Http\Controllers\PaymentController::class, 'paymentPage']);
    Route::post('/add/new/payment', [App\Http\Controllers\PaymentController::class, 'addNewPayment']);
    Route::get('/delete/payment/{id}', [App\Http\Controllers\PaymentController::class, 'deletePayment']);
    Route::get('/edit/payment/{id}', [App\Http\Controllers\PaymentController::class, 'editPayment']);
    Route::post('/update/payment', [App\Http\Controllers\PaymentController::class, 'updatePayment']);

    //payment type
    Route::get('/payment/type/page', [App\Http\Controllers\PaymentTypeController::class, 'paymentTypePage']);
    Route::post('/add/new/payment/type', [App\Http\Controllers\PaymentTypeController::class, 'addNewPaymentType']);
    Route::get('/delete/payment/type/{id}', [App\Http\Controllers\PaymentTypeController::class, 'deletePaymentType']);
    Route::get('/edit/payment/type/{id}', [App\Http\Controllers\PaymentTypeController::class, 'editPaymentType']);
    Route::post('/update/payment/type', [App\Http\Controllers\PaymentTypeController::class, 'updatePaymentChannel']);

    // withdraw amount
    Route::get('/withdraw/amount/page', [App\Http\Controllers\WithDrawController::class, 'withDrawAmountPage']);
    // Route::get('/get/withdraw/data/for/modal/{id}/edit', [App\Http\Controllers\WithDrawController::class, 'getDataForModalApproveWithDraw']);
    Route::get('/save/approve/data/wihdraw/{id}', [App\Http\Controllers\WithDrawController::class, 'saveTransactionId']);
    Route::get('/deny/withdraw/{id}', [App\Http\Controllers\WithDrawController::class, 'denyWithDraw']);


    // account submission history
    Route::get('/view/account/submission', [App\Http\Controllers\HomeController::class, 'viewAccountSubmissionPage']);
    Route::get('/approve/account/submission/{id}/{user_id}', [App\Http\Controllers\HomeController::class, 'approveAccountSubmission']);
    Route::get('/deny/account/submission/{id}/{user_id}', [App\Http\Controllers\HomeController::class, 'denyAccountSubmission']);


    Route::get('/add/new/quiz', [App\Http\Controllers\QuizController::class, 'addNewQuiz']);
    Route::post('/save/new/quiz', [App\Http\Controllers\QuizController::class, 'saveNewQuiz']);
    Route::get('/view/all/quiz', [App\Http\Controllers\QuizController::class, 'viewAllQuiz']);
    Route::get('/delete/quiz/{id}', [App\Http\Controllers\QuizController::class, 'deleteQuiz']);

    Route::get('/add/new/website', [App\Http\Controllers\WebsiteController::class, 'addNewWebsite']);
    Route::post('/save/new/website', [App\Http\Controllers\WebsiteController::class, 'saveNewWebsite']);
    Route::get('/view/all/websites', [App\Http\Controllers\WebsiteController::class, 'viewAllWebsite']);
    Route::get('/delete/website/{id}', [App\Http\Controllers\WebsiteController::class, 'deleteWebsite']);


    Route::get('/fb/ad/config', [App\Http\Controllers\FacebookAdConfigController::class, 'facebookAdConfigPage']);
    Route::post('/update/ad/info', [App\Http\Controllers\FacebookAdConfigController::class, 'updateAdInfo']);

    Route::get('user/refferal/bonus', [App\Http\Controllers\HomeController::class, 'userRefferalBonus']);
    Route::post('filter/for/refferal/bonus', [App\Http\Controllers\HomeController::class, 'getEligibleUsers']);
    Route::post('save/refferal/bonus', [App\Http\Controllers\HomeController::class, 'saveRefferalBonus']);

});
