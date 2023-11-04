<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace' => 'Api'], function () {

    Route::post('user/login', [ApiController::class, 'userLogin']);
    Route::post('user/register', [ApiController::class, 'userRegistration']);
    Route::post('user/new/signup', [ApiController::class, 'userNewRegistration']);
    Route::post('user/forget/password', [ApiController::class, 'userForgetPassword']);
    Route::post('user/change/password', [ApiController::class, 'userChangePassword']);
    Route::post('user/profile/update', [ApiController::class, 'userProfileUpdate']);
    Route::post('user/profile/info', [ApiController::class, 'userProfileInfo']);

    Route::get('mining/packages', [ApiController::class, 'miningPackages']);
    Route::get('get/payment/types', [ApiController::class, 'getPaymentTypes']);
    Route::post('get/payment/info', [ApiController::class, 'getPaymentInfo']);
    Route::get('get/settings', [ApiController::class, 'settings']);
    Route::post('subscribe/package', [ApiController::class, 'subscribePackage']);
    Route::post('user/subscribed/packages', [ApiController::class, 'userSubscribePackages']);

    Route::post('user/earning/history', [ApiController::class, 'userEarningHistory']);
    Route::post('user/refferal/earning', [ApiController::class, 'userRefferalEarning']);
    Route::post('user/extra/earning/submit', [ApiController::class, 'userExtraEarningSubmit']);
    Route::get('quiz/questions', [ApiController::class, 'quizQuestions']);
    Route::get('fb/ad/config', [ApiController::class, 'fbAdConfig']);
    Route::post('start/mining', [ApiController::class, 'startMining']);
    Route::post('mining/status', [ApiController::class, 'miningStatus']);
    Route::post('submit/withdraw', [ApiController::class, 'submitWithdraw']);
    Route::post('withdraw/request/history', [ApiController::class, 'withdrawHistory']);

    Route::post('spin/buy', [ApiController::class, 'spinBuy']);
    Route::post('submit/purchase/spin', [ApiController::class, 'submitPurchaseSpin']);
    Route::post('mining/claim', [ApiController::class, 'miningClaim']);
    Route::post('get/website/link', [ApiController::class, 'getWebsiteLink']);
    Route::post('get/website/link/new', [ApiController::class, 'getWebsiteLinkNew']);
    Route::post('get/refferal/incomes', [ApiController::class, 'getRefferalIncome']);

    Route::post('top/refferal/list', [ApiController::class, 'topRefferalList']);

    Route::post('package/request/submit', [ApiController::class, 'accountStatusSubmit']);
    Route::post('package/request/history', [ApiController::class, 'accountSubmissionHistory']);
    Route::post('transfer/deposite/balance', [ApiController::class, 'transferDepositeBalance']);
    Route::post('update/device/token', [ApiController::class, 'updateDeviceToken']);

    //Route::post('account/auto/denied', [ApiController::class, 'accountAutoDenied']);
    //Route::post('account/status/submit/updated', [ApiController::class, 'accountStatusSubmitUpdated']);
});
