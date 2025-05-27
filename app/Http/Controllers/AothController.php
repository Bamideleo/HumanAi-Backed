<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use DB;


class AothController extends Controller
{


public function testing(){
dd('working...');
}
   
    






public function getReg(){
    echo "true";
}

public function createRegister(Request $request){ 
   $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|string|unique:users,email',
        'password' => 'required|string|confirmed',
     ]);
if ($validator->fails()) {
    $response = [
    'status' => 'error',
    'message' => $validator->errors(),
  ];
return response($response, 200);
            }
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
       'password' => bcrypt($request->password),
       'fe' => 1,
    ]);

  Settings::create([
    'user_id' => $user->id,
   ]);
   
 $token = $user->CreateToken('myapptoken')->plainTextToken;

    $response = [
    'status' => 'success',
    'message' => 'User Created Successfully',
    ];

    return response($response, 200);
}

 public function login(Request $request){

  dd('working...');
    $validator = Validator::make($request->all(), [
        'email' => 'required|string',
        'password' => 'required|string',
     ]);
if ($validator->fails()) {
    $response = [
    'status' => 'error',
    'message' => $validator->errors(),
  ];
return response($response, 200);
            }

    //check user email exist
    $user = User::where('email', $request->email)->first();

    if(!$user || !Hash::check($request->password, $user->password)){

        return response([
      'status' => 'error',
      'message' => 'Invalid Login Details',
        ], 200);
    }

    $token = $user->CreateToken('myapptoken')->plainTextToken;

    $response = [
    'status' => 'success',
    'message' => 'Login Successful',
        'user' => $user,
        'token' => $token
    ];

    return response($response, 200);
}

public function verifyAccount($token){
    $verifyUser = UserVerify::where('token', $token)->first();
    if(!is_null($verifyUser)){
        $user = $verifyUser->user;
        if(!$user->is_email_verified){
            $verifyUser->user->is_email_verified = 1;
            $verifyUser->user->save();
            $response = [
            'status' => 'success',    
        'message' => 'Email Verified, You can now login.',
     ];

    return response($response, 200);
        }else{
         $response = [
        'status' => 'success',     
        'message' => 'Email Alrady Verified.',
     ]; 
     return response($response, 200);  
        }
    }
}

public function reset(Request $request){
$validator = Validator::make($request->all(), [
       
        'email' => 'required|string',

     ]);
if ($validator->fails()) {
    $response = [
    'status' => 'error',
    'message' => $validator->errors(),
  ];
return response($response, 200);
            }
$count = DB::table('users')->where('email', $request->email)->count();
if($count != 0){
$token = Str::random(64);
$reset = DB::table('password_resets')->where('email', $request->email)->count();
if($reset == 0){
DB::table('password_resets')->insert([
          'email'=> $request->email,
           'token' => $token,
           ]);
}else{
DB::table('password_resets')->where('email', $request->email)->update([
          'token' => $token,
          'status' => 0,
           ]); 
}

$this->composeEmail($request->email,$token);
$response = [
        'status' => 'success',
        'message' => 'Reset Password Link sent to your email',
    ];

    return response($response, 200);       
}else{
return response([
            'status' => 'error',
            'message' => 'User Not Found',
        ], 200); 
}
  
}

public function resetAccount($token)
{
$reset = DB::table('password_resets')->where('token', $token)->where('status', 0)->first();
if(!$reset){
 return response([
            'status' => 'error',
            'message' => 'Token Not Found',
        ], 200); 
}     
else{
$response = [
        'status' => 'success',
        'message' => 'successful',
        'reset' => $reset,
    ];

    return response($response, 200);
}
}

public function saveResetAccount(Request $request){ 
$this->validate($request, [
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|confirmed|min:4',
    ]);
$reset = DB::table('password_resets')->where('token', $request->token)->orderBy('created_at', 'desc')->first();
  
    if ($reset->status == 1) {
         return response([
            'status' => 'error',
            'message' => 'Invalid Token',
        ], 200); 
    }

    $password = bcrypt($request->password);
    DB::table('users')->where('email', $reset->email)->update([
          'password'=> $password,
         ]);
   DB::table('password_resets')->where('token', $request->token)->update([
          'status'=> 1,
         ]);
$response = [
        'status' => 'success',
        'message' => 'Password Reset Successfully',
    ];

    return response($response, 200);

}

public function resellerUsers(Request $request){
 
$user = User::where('reseller_id',auth()->user()->id)->get();
// if ($request->filled('search')) {
//             $user->where('name', 'like', '%' . $request->search . '%')
//                 ->orWhere('email', 'like', '%' . $request->search . '%');
//         }

//         $user = $user->get();
$response = [
    'status' => 'success',
    'message' => 'Success',
    'users' => $user,
  ];
return response($response, 200);

}


public function createReseller(Request $request){ 
   $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|string|unique:users,email',
        'password' => 'required|string|confirmed',
     ]);
if ($validator->fails()) {
    $response = [
    'status' => 'error',
    'message' => $validator->errors(),
  ];
return response($response, 200);
            }
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'fe' => 1,
        'reseller_id' => auth()->user()->id,
        'oto_1' => $request->oto1 ?? 0,
       'password' => bcrypt($request->password)
    ]);

  Settings::create([
    'user_id' => $user->id,
   ]);
   
 $token = $user->CreateToken('myapptoken')->plainTextToken;

    $response = [
    'status' => 'success',
    'message' => 'User Created Successfully',
        // 'token' => $token
    ];

    return response($response, 200);
}

public function getUser($id){
  $user = User::where('id',$id)->first();
  $response = [
    'status' => 'success',
    'message' => 'Success',
    'user' => $user,
  ];
return response($response, 200);
}

 public function updatedUser(Request $request, $id){
$this->validate($request, [
        'name' => 'required|string',
        'email' => 'required|email',
        
    ]);
 
 $profile = User::where('id', $id)->first(); 
 if($profile){
  if(!empty($request->password)){
   $profile->password = bcrypt($request->password); 
  }
  $profile->name = $request->name ?? $profile->name;
  $profile->email = $request->email ?? $profile->email;
  $profile->oto_1 = $request->oto1 ?? $profile->oto_1;
  $user = $profile->save();
  $response = [
    'status' => 'success',
    'message' => 'User Data Updated',
    
  ];
return response($response, 200);
 }else{
  $response = [
    'status' => 'error',
    'message' => 'User Not Found',
  ];
return response($response, 200);
 }
}

public function deleteUser($id)
{
User::where('id', $id)->delete();
$response = [
    'status' => 'success',
    'message' => 'User Deleted',
  ];
return response($response, 200);

}


public function logout(Request $request){
    auth()->user()->tokens()->delete();

    $response = [
        'status' => 'success',
        'message' => 'User Logout',
    ];

    return response($response, 200);
}


  // ========== [ Compose Email ] ================
public function composeEmail($to,$token) {

$subject =  "Reset Password Email";
$message = "<p style='font-size:18px;'>
<h1>Reset Password Email</h1>

<h2 style='color: red'>This is a one-time password reset link</h2>
<h3>Pls discard this email if you didn't request for this password reset</h3> 

<h2>Please Use The Link Bellow To Reset Your Password</h2>
<a href='https://myaiappz.com/reset-password/{$token}' style='font-size:20px'>Reset Password</a>
</p><br>
<p style='font-size:18px;'>Regards</p>
<p style='font-size:18px;'>Appz Support</p>
";  


$from = 'support@appz.com';
$headersfrom = 'MIME-Version: 1.0' . "\r\n";
$headersfrom .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headersfrom .= 'From: ' . $from . ' ' . "\r\n";

$sendMail = mail($to, $subject, $message, $headersfrom);


   
}




}  
