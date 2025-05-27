<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Notifications\SendPushNotification;
use Stripe;
use Notification;
use DB;



class PaymentController extends Controller
{
 

 public function getStripeKeys(){
    $userId = auth()->user()->id;
    $stripe = DB::table('payment_gateway')->where('user_id', $userId)->where('type', 'stripe')->first();
    if($stripe){
        $response = [
        'status' => 'success',
        'message' => 'Stripe Account Activate',
        'stripe' => $stripe,
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'Stripe Account not found',
         ];
        return response($response, 200);
    }
 }

 public function getOrderHistory(){
    $userId = auth()->user()->id;
    $history = DB::table('payment_history')->where('user_id', $userId)->get();
    if($history){
        $response = [
        'status' => 'success',
        'message' => 'Successful',
        'orders' => $history,
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'No Data Found',
         ];
        return response($response, 200);
    }
 }

 public function deleteOrderHistory($id){
    
      if(DB::table('payment_history')->where('id', $id)->delete()){
       
        $response = [
        'status' => 'success',
        'message' => 'Deleted',
        ];
        return response($response, 200);
      }else{
        $response = [
        'status' => 'error',
        'message' => 'Do not exist',
        ];
        return response($response, 200);
      }
 }

 public function postStripeKeys(Request $request)
 {
    $validator = Validator::make($request->all(), [
            'stripe_key' => 'required|string',
            'public_key'  => 'required|string',
         ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }

  $userId = auth()->user()->id; 
  $count = DB::table('payment_gateway')->where('user_id', $userId)->where('type', 'stripe')->count();
  if($count == 0){
$stripe = DB::table('payment_gateway')->insert([
              'user_id'=>  $userId,
               'type' => 'stripe',
               'public_key' => $request->public_key,
               'stripe_key' => $request->stripe_key,
              ]);
     
         $response = [
        'status' => 'success',
        'message' => 'Stripe Account Added',
        
      ];
    return response($response, 200);
}else{
    $stripe = DB::table('payment_gateway')->where('user_id', $userId)->where('type', 'stripe')->update([
              'public_key' => $request->public_key,
               'stripe_key' => $request->stripe_key,
              ]);
     
         $response = [
        'status' => 'success',
        'message' => 'Stripe Account Added',
       
      ];
    return response($response, 200);
}

 }



    public function stripe()
    {
        return view('stripe');
    }

    public function stripePaymentPost(Request $request)
    {      
//         $validator = Validator::make($request->all(), [
//             'amount' => 'required',
//             //'stripeToken' => 'required',
//          ]);
//     if ($validator->fails()) {
//         $response = [
//         'status' => 'error',
//         'message' => $validator->errors(),
//       ];
//     return response($response, 200);
//   }
        // $amount = $request->amount ?? 1;
        // $cur = $request->cur ?? 'USD';
        // $des = $request->description ?? 'Stripe Payment';
        // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // Stripe\Charge::create ([
        //         "amount" => $amount*100,
        //         "currency" => $cur,
        //         "source" => $request->stripeToken,
        //         "description" => $des,
        // ]);
         
          $pay = DB::table('payment_history')->insert([
              'user_id'=>  auth()->user()->id ?? $request->uid,
               'type' => 'stripe',
               'title' => $request->product_name ?? "",
               'trans_id' => Str::random(16),
               'amount' => $request->amount,
               'description' => $request->description,
               
               'status' => 1,
              ]);
          if($pay){
            $response = [
        'status' => 'success',
        'message' => 'Payment Successful',
        
      ];
    return response($response, 200);
          }
        
    }
    
     public function payWithStripe(Request $request,$uid)
    {
        $userId = $uid ?? 0;
        $stripe = DB::table('payment_gateway')->where('user_id', $userId)->where('type', 'stripe')->first();
        if($stripe){
         
        // Replace with your secret key, found in your Stripe dashboard
        Stripe\Stripe::setApiKey($stripe->stripe_key); 

        // function calculateOrderAmount($items): int {
        //     return 499;
        // }

        header('Content-Type: application/json');

        try {

            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            $paymentIntent = Stripe\PaymentIntent::create([
                'amount' => $request->amount,
                'currency' => 'usd', // Replace with your country's primary currency
                 'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                // Remove if you don't want to send automatic email receipts after successful payment
                //"receipt_email" => $request->email 
            ]);

            // $output = [
            //     'clientSecret' => $paymentIntent->client_secret,
            // ];
            $response = [
        'status' => 'success',
        'message' => 'successful',
        'clientSecret' => $paymentIntent->client_secret,
         ];
        return response($response, 200);
            //echo json_encode($output);
        } catch (Exception $e) {
            $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
        
         ];
         return response($response, 200);
           // return back()->with(['error' => $e->getMessage()]);
        }
        }else{
           $response = [
        'status' => 'error',
        'message' => "Stripe Secret Key Not Found",
        
         ];
         return response($response, 200);  
        }
    }

public function getPaypayEmail(){
     $userId = auth()->user()->id;
    $paypal = DB::table('payment_gateway')->where('user_id', $userId)->where('type', 'paypal')->first();
    if($paypal){
        $response = [
        'status' => 'success',
        'message' => 'Paypal Account Activate',
        'paypal' => $paypal->email,
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'Paypal Account not found',
         ];
        return response($response, 200);
    }
}

public function postPaypalEmail(Request $request){
     $validator = Validator::make($request->all(), [
            'paypal_key' => 'required|string',
         ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
  }

  $userId = auth()->user()->id; 
  $count = DB::table('payment_gateway')
            ->where('user_id', $userId)
            ->where('type', 'paypal')
            ->count();
  if($count == 0){
 $pay = DB::table('payment_gateway')->insert([
              'user_id'=>  $userId,
               'type' => 'paypal',
               'email' => $request->paypal_key,
              ]);
     
         $response = [
        'status' => 'success',
        'message' => 'Paypal Account Added',
        
      ];
    return response($response, 200);
  }else{
      
    $pay = DB::table('payment_gateway')->where('user_id', $userId)->where('type', 'paypal')->update([
             
               'email' => $request->paypal_key,
              ]);
     
         $response = [
        'status' => 'success',
        'message' => 'Paypal Account Updated',
        
      ];
    return response($response, 200);  
  }
  
}

public function payWithPaypal(Request $request)
{
    $pay = DB::table('payment_history')->insert([
              'user_id'=>  auth()->user()->id ?? $request->uid,
               'type' => 'paypal',
               'title' => $request->product_name ?? "",
               'trans_id' => Str::random(16),
               'amount' => $request->amount,
               'description' => $request->description,
              ]);
          if($pay){
            $response = [
        'status' => 'success',
        'message' => 'Payment Successful',
      
      ];
    return response($response, 200);
          }
}



public function stripePost(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'stripeToken' => 'required|string',
         ]);
    if ($validator->fails()) {
       Session::flash('error', $validator->errors());
  }
        $amount = $request->amount ?? 1;
        $cur = $request->cur ?? 'USD';
        $des = $request->description ?? 'Stripe Payment';
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => $amount*100,
                "currency" => $cur,
                "source" => $request->stripeToken,
                "description" => $des,
        ]);
         
          $pay = DB::table('payment_history')->insert([
              'user_id'=>  4,
               'type' => 'stripe',
               'trans_id' => Str::random(16),
               'amount' => $request->amount,
               'description' => $request->description,
               'created_on' => date(),
               'status' => 1,
              ]);
          if($pay){
           Session::flash('success', 'Payment Successful !');
        return back(); 
          }
        
    }

    public function updateToken(Request $request){
      if(!empty($request->user_id)){ 
    try{
        $usr = User::where('id',$request->user_id)->first();
        $usr->fcm_token = $request->pin;
        $usr->save();
        // $request->user()->update(['fcm_token'=>$request->token]);
        $response = [
        'status' => 'success',
        'message' => 'fcm token saved!',
      ];
      return response($response, 200);
    }catch(\Exception $e){
        report($e);
       $response = [
        'status' => 'error',
        'message' => $e,
      ];
      return response($response, 200);
    }
 }else{
     $response = [
        'status' => 'success',
        'message' => 'user ID not sent',
      ];
      return response($response, 200);
 }
}

public function indexN(){
    return view('home');
}

public function sendNotification(Request $request)
    {
       
       try{

        $fcmTokens = User::where('id', auth()->user()->id)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
        
     // Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));

        // Larafirebase::withTitle($request->title)
        //     ->withBody($request->message)
        //     ->sendMessage($fcmTokens);
       
        //return redirect()->back()->with('success','Notification Sent Successfully!!');
        
        if($request->hasFile('push_image')){
      $file = $request->push_image;
      $filename = time().'.'.$file->getClientOriginalExtension();
      $file->move(public_path('images/user_'.$userId.'/notification/image'), $filename);
      
        $path = url('public/images/user_'.$userId.'/notification/image').'/'.$filename;
  } 
  
  //dd($fcmTokens);

    $SERVER_API_KEY = 'AAAAIlZc8sU:APA91bGtRAgp8FwZSa3cQewcmi7XkRIbvs2z5p9xcasNC8BNuBgo7ZpHkxm2oyX8AfE1L86QkUCzgZZt0vd0N-PJCTnLl63bGTjkrEXYvJ8kr5dKLWowbMeF9XTqhupBBIpyixlmGY5l'; 
  $extraNotificationData = ['one'=>1,'two'=>2];
        $data = [
            "registration_ids" => $fcmTokens,
            //"to" => $fcmTokens,
            //"data" => $extraNotificationData,
            "notification" => [
                'title' => $request->title,
                'body' => $request->body,
                'sound' => true,
            'priority' => "high",
            'vibration'=>true,
            'sound'=> "Enabled",
            'badge'=>4,
            'id'=> auth()->user()->id ?? 4,
            'image'	=> $path ?? '',
            ]
        ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
        
        $response = [
        'status' => 'success',
        'message' => 'Notification Sent',
      ];
      return response($response, 200);

        }catch(\Exception $e){
        report($e);
       $response = [
        'status' => 'error',
        'message' => $e,
      ];
      return response($response, 200);
    }

    }
public function pushNotificationSettings(Request $request)
{
        $validator = Validator::make($request->all(), [
            'apiKey' => 'required',
            'authDomain' => 'required|string',
            'databaseURL' => 'required|string',
            'projectId' => 'required',
            'storageBucket' => 'required|string',
            'messagingSenderId' => 'required',
            'appId' => 'required',
            'measurementId' => 'required',
            'server_key' => 'required|string',
         ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
  }

   DB::table('push_notification')->insert([
              'user_id'=>  auth()->user()->id,
               'apiKey' => $request->apiKey,
               'authDomain' => $request->authDomain,
               'databaseURL' => $request->databaseURL,
               'projectId' => $request->projectId,
               'storageBucket' => $request->storageBucket,
               'messagingSenderId' => $request->messagingSenderId,
               'appId' => $request->appId,
               'measurementId' => $request->measurementId,
               'server_key' => $request->server_key,
              ]);

   $response = [
        'status' => 'success',
        'message' => 'saved',
      ];
      return response($response, 200);

}

public function getPushNotificationSettings(){
  $fb = DB::table('push_notification')->where('user_id', auth()->user()->id)->first();
  if($fb){
    $response = [
        'status' => 'success',
        'firebase' => $fb,
      ];
      return response($response, 200);

  }else{
    $response = [
        'status' => 'error',
        'message' => 'Settings not found',
      ];
      return response($response, 200);
  }
}


}