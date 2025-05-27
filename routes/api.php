<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SendSmsMessageController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AothController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 

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
// Public route
 //Route::get('/reg',[AuthController::class, 'getReg']);
 Route::get('/tes',[AothController::class, 'testing']); 
 Route::post('/register',[AuthController::class, 'createRegister']);  
 Route::post('/login',[AuthController::class, 'TestData']); 
 Route::get('/login',[AuthController::class, 'TestData']); 
 
 Route::post('/account/verify/{token}',[AuthController::class, 'verifyAccount'])->name('verify.email');

 Route::post('/reset',[AuthController::class, 'reset']);
  Route::get('/password/reset/{token}',[AuthController::class, 'resetAccount'])->name('reset.password');
 Route::post('/password/reset',[AuthController::class, 'saveResetAccount']);
 
 //Monetize
 Route::get('/monetize-by-appid/{id}', [AppController::class, 'monetizeByAppId']);
 
 //Domain and Slug
 Route::get('/getapp-by-slug/{slug}', [AppController::class, 'getAppBySlug']);
 Route::get('/getapp-by-domain/{site}', [AppController::class, 'getAppByDomain']);
 
 //google key
 Route::get('/get-google-key/{id}', [SettingsController::class, 'getGoogleKey']);
 
 //Push notification
 Route::post('/save-push-notifacation-token', [PaymentController::class, 'updateToken']);
 
 //App Config
 Route::post('/app-config', [AppController::class, 'saveAppDetails']);
 
 //Stripe
 Route::post('/stripe-config/{uid}', [PaymentController::class, 'payWithStripe']); 
 
 //sub user
Route::post('/add-sub-user', [SettingsController::class, 'addSubscriberUser']); 
     

//Protected route
 Route::group(['middleware' => ['auth:sanctum']], function () {
 //Dashboad
 Route::get('/user-dashboard', [DashboardController::class, 'index']);
 
 	//Profile Route
 Route::get('/profile', [SettingsController::class, 'profile']);
 Route::post('/profile', [SettingsController::class, 'updateProfile']);	
 Route::post('/change-password', [SettingsController::class, 'changePassword']);
 Route::get('/profile-image', [SettingsController::class, 'getProfilePic']);
 Route::post('/profile-image', [SettingsController::class, 'profileImage']);	

 //Setting Route
 Route::get('/currency', [SettingsController::class, 'currency']);
 Route::post('/currency', [SettingsController::class, 'updateCurrency']);
 Route::get('/lang', [SettingsController::class, 'lang']);
 Route::post('/lang', [SettingsController::class, 'updateLang']);

 //Banks Route
 Route::get('/bank', [BankController::class, 'bank']);
 Route::post('/bank', [BankController::class, 'addBank']);
 Route::get('/editbank/{id}', [BankController::class, 'editBank']);
 Route::post('/updatebank/{id}', [BankController::class, 'updateBank']);
 Route::delete('/deletebank/{id}',[BankController::class, 'deleteBank']); 

 //User Route
 Route::group(['middleware' => ['is_verify_email']], function () {
 Route::get('/users', [SettingsController::class, 'users']);
 Route::post('/create-users', [SettingsController::class, 'createUserAdmin']);
 Route::get('/edituser/{id}', [SettingsController::class, 'EditUser']);
 Route::post('/update-user/{id}', [SettingsController::class, 'updatedUserAdmin']);
 Route::delete('/delete-user/{id}',[SettingsController::class, 'deleteUser']);
});
 //AutoResponder Route
 Route::get('/settings/optin', [SettingsController::class, 'getAllOptinAccount']);
 Route::get('/settings', [SettingsController::class, 'settings']);
 Route::post('/settings', [SettingsController::class, 'updateSetting']);
 Route::get('/settings/optin/{account}', [SettingsController::class, 'addOptinEmail']);
 Route::post('authorize/aweber', [SettingsController::class, 'authorizeApp']); 
 Route::post('authorize/mailchimp', [SettingsController::class, 'authorizeMailch']);
 Route::post('/settings/optin/getresponse', [SettingsController::class, 'getresponseApiKey']);
 Route::get('optin/delete/{type}', [SettingsController::class, 'deleteOptinEmail']);
 Route::get('optin/list/{account}', [SettingsController::class, 'optInAccountLists']);
 Route::get('optin-all', [SettingsController::class, 'getAllOptinAccount']);

 //Products Route
  Route::get('/products', [ProductController::class, 'index']);
 Route::get('/products/{id}', [ProductController::class, 'show']);
 Route::get('/products/search/{name}', [ProductController::class, 'search']);
  Route::post('/products',[ProductController::class, 'store']);
  Route::post('/products/{id}',[ProductController::class, 'update']);
  Route::delete('/products/{id}',[ProductController::class, 'destroy']);
  
  //Category
  Route::get('/category', [ProductController::class, 'getCategory']);
 Route::get('/category/{id}', [ProductController::class, 'editCategory']);
 Route::get('/category/search/{name}', [ProductController::class, 'searchCategory']);
  Route::post('/category',[ProductController::class, 'createCategory']);
  Route::post('/category/{id}',[ProductController::class, 'updateCategory']);
  Route::delete('/category/{id}',[ProductController::class, 'deleteCategory']);

  //Payment Route
  Route::get('/get-stripeKey', [PaymentController::class, 'getStripeKeys']);
  Route::post('/save-stripeKey',[PaymentController::class, 'postStripeKeys']);
  Route::post('/paywithstripe',[PaymentController::class, 'stripePaymentPost']);
  Route::get('/get-paypay', [PaymentController::class, 'getPaypayEmail']);
  Route::post('/post-paypalEmail',[PaymentController::class, 'postPaypalEmail']);
  Route::post('/paywithpaypal',[PaymentController::class, 'payWithPaypal']);
  Route::post('/stripeToken',[PaymentController::class, 'payWithStripe']);

  //SMS
  Route::get('/get-twilio', [SettingsController::class, 'getTwilio']);
  Route::post('/twilio-setting',[SettingsController::class, 'postTwilioSetting']);
  Route::get('/get-numbers', [SendSmsMessageController::class, 'show']);
  Route::post('/save-numbers', [SendSmsMessageController::class, 'storePhoneNumber']);
  Route::post('/send-sms', [SendSmsMessageController::class, 'sendCustomMessage']);

//Push Notification
  Route::get('/get-push-notifacation-setting', [PaymentController::class, 'getPushNotificationSettings']);
 
Route::post('/send-push-notifacation', [PaymentController::class, 'sendNotification']);
Route::post('/push-notifacation-setting', [PaymentController::class, 'pushNotificationSettings']);

// AI Content
Route::post('/post-ai-image', [AiController::class, 'postAiImage']);
Route::post('/post-ai-content', [AiController::class, 'aiTextGenerator']);

//Order History
Route::get('/order-history', [PaymentController::class, 'getOrderHistory']);
Route::delete('/delete-order-history/{id}', [PaymentController::class, 'deleteOrderHistory']);

//APP
Route::get('/apps', [AppController::class, 'getAllApp']);
Route::post('/create-app', [AppController::class, 'createApp']);
Route::get('/app/{id}', [AppController::class, 'getapp']);
Route::post('/update-app/{id}', [AppController::class, 'updateApp']); 
Route::delete('/delete-app/{id}', [AppController::class, 'deleteApp']);
Route::post('/create-exsting-app', [AppController::class, 'createExstingApp']);
Route::post('/publish-app/{id}', [AppController::class, 'publishApp']);

//Templates
Route::get('/templates', [AppController::class, 'getAllTemplates']);

//Upload Files
Route::post('/uploadfiles/{type}', [AppController::class, 'uploadFiles']);

//Coupon Route
Route::get('/coupon', [SettingsController::class, 'getCoupon']);
Route::get('/edit-coupon/{id}', [SettingsController::class, 'editCoupon']);
Route::post('/create-coupon', [SettingsController::class, 'createCoupon']);
Route::post('/update-coupon/{id}', [SettingsController::class, 'updateCoupon']);
Route::delete('/delete-coupon/{id}', [SettingsController::class, 'deleteCoupon']);

//Monetize
Route::get('/monetize', [AppController::class, 'monetize']);
Route::post('/add-monetize', [AppController::class, 'storeMonetize']);
Route::get('/monetize/{id}', [AppController::class, 'editMonetize']);

Route::post('/update-monetize/{id}', [AppController::class, 'updateMonetize']); 
Route::delete('/delete-monetize/{id}', [AppController::class, 'deleteMonetize']);

//Agency User
Route::get('/agency', [AuthController::class, 'resellerUsers']);
Route::post('/add-agency', [AuthController::class, 'createReseller']);
Route::get('/agency/{id}', [AuthController::class, 'getUser']);
Route::post('/update-agency/{id}', [AuthController::class, 'updatedUser']);
Route::delete('/delete-agency/{id}', [AuthController::class, 'deleteUser']);

//Google Key
Route::post('/save-google-key', [SettingsController::class, 'postGooglekey']);

//Automation
Route::get('/automation', [SettingsController::class, 'getAutomationUser']);
Route::post('/send-auto-email', [SettingsController::class, 'sendAutomationEmail']);


  //user logout
 Route::post('/logout',[AuthController::class, 'logout']);  
});




