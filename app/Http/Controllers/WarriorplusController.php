<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use Mail;

class WarriorplusController extends Controller
{
    public function warriorPlusIpnsecure(){
     if(!isset($_POST['WP_ACTION'])){
			die('unathorized access.');
	}
	
	 if($_POST['WP_ACTION'] == 'SALE' || $_POST['WP_ACTION'] == 'sale')
   {
      // Get all data information
      $name = $_POST["WP_BUYER_NAME"];
      $email = $_POST["WP_BUYER_EMAIL"];
      $ProductName = $_POST["WP_ITEM_NUMBER"];
      $password = 'demo111';
      //get User;
     $user = User::where('email', $email)->first();
     // FE Package
     if($ProductName == "wso_n7lrlx" || $ProductName == "wso_tyf0fr" || $ProductName == "wso_myzgzy" ||$ProductName == "wso_xwd48g"){
         //check if user exist
         if(isset($user->email) != $email){
             //Register a new User
             $user = User::create([
    		'name' => $name,
    		'email' => $email,
           'password' => bcrypt($password),
           'fe' => 1,
    	]);

      Settings::create([
        'user_id' => $user->id,
       ]);
       
     $token = $user->CreateToken('myapptoken')->plainTextToken;
     
     //  for sending mail
        Mail::send('email.warriorpluslogin',  $inputs, function ($message) use ($email,$password,$name,$ProductName)  {
                        
            $message->from('support@mycryptopayz.com', $name ='CryptoPayz');
            $message->subject("Congrats! Here Is Your CryptoPayz Login Details", $name = null);
            $message->to($email, $name = null);
            
       
    });
     // if($rr){
    	$response = [
        'status' => 'success',
    	'message' => 'User Created Successfully',
    		// 'token' => $token
    	];

    	return response($response, 200);
     // }
         }
     }
     
   }
	
	
    }
}
