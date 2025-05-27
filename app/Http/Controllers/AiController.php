<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use DB;

class AiController extends Controller
{


public function testing(){
    dd('working...');
}
   
    
//     public function postAiImage(Request $request)
//    {
    
// 	$max_results = $request->max_results ??'';
          

//             $prompt = $request->title ?? '';
            
//             if ($request->style != 'none') {
//                 $prompt .= ', ' . $request->style; 
//             } 
            
//             if ($request->lightning != 'none') {
//                 $prompt .= ', ' . $request->lightning; 
//             } 
            
//             if ($request->artist != 'none') {
//                 $prompt .= ', ' . $request->artist; 
//             }
            
//             if ($request->medium != 'none') {
//                 $prompt .= ', ' . $request->medium; 
//             }
            
//             if ($request->mood != 'none') {
//                 $prompt .= ', ' .$request->mood; 
//             }


//             $complete = [
//                     'prompt' => $prompt,
//                     'size' => $request->resolution,
//                     'n' => (int)$max_results,
//                 ];
//        $key = "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";         
//       $ch = curl_init();
//   $headers  = [
//         'Accept: application/json',
//         'Content-Type: application/json',
//         'Authorization: Bearer '.$key
//     ];

//      curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/images/generations');
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//   curl_setopt($ch, CURLOPT_POST, 1);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($complete)); 

//   $output = curl_exec($ch);
//  $err = curl_error($ch);
//   curl_close($ch);
//    # Print any errors, to help with debugging.
// if ($err) {
//    echo "cURL Error #:" . $err;
//   }
//  //var_dump($output);
//  //die(); 
//   file_put_contents("imageAiImage".auth()->user()->id.".data",$output);
// $foutput=file_get_contents("imageAiImage".auth()->user()->id.".data");
// $joutput=json_decode($foutput,true);

// if(isset($joutput)){
//      $path = 'public/images/user_'.auth()->user()->id.'/ai/';
//         if (!file_exists($path)) {
//     mkdir($path, 0777, true);
// }
//     $arr = [];
//     for($i = 0; $i < $max_results; $i++){
//    $temp = time();
//    $ipth = $path.$temp."image".$i.".png";
//   file_put_contents($ipth,file_get_contents($joutput["data"][$i]["url"]));
//    array_push($arr, $ipth);
//    DB::table('ai_content')->insert([
//            'user_id'=> auth()->user()->id,
//            //'name' =>  request("name"),
//            'image_url' => $ipth,
//            'resolution' => $request->resolution,
//            'type' => 'ai_image',
//        ]);
      
//  if (file_exists("imageAiImage".auth()->user()->id.".data")) {
//     unlink("imageAiImage".auth()->user()->id.".data");
//     }

   
// }

// $response = [
//     'status' => 'success',
//     'image_url' => $arr,
//     'resolution' => $request->resolution,
//     'message' => 'Image Generated',
//      ];
//     return response($response, 200);


  
// }


//  }

//  public function aiTextGenerator(Request $request){ 
//         /**
//          * Content Code
//          */
//         $validator = Validator::make($request->all(), [
//             'search_key_word' => 'required|string',
//          ]);
//     if ($validator->fails()) {
//         $response = [
//         'status' => 'error',
//         'message' => $validator->errors(),
//       ];
//     return response($response, 200);
//                 }

//         $search_keyword = '';
        
//         $search_keyword = $request->search_key_word;
       
//         $language = 'English';
//         $presence_penalty = 0.01;
//         $frequency_penalty = 0.01;
//         $best_of = 1;
//         $top_p = 0.01;
//         $getTemperature =0.5;
//         $getMaxTokens = 2000;
//         $model_option = 'gpt-3.5-turbo-instruct';
//         $key = "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";
//         $header = array( 
//             'Authorization: Bearer '.$key,
//             'Content-type: application/json; charset=utf-8',
//          );
//         $params = json_encode(array( 
//             'prompt' => "$language:$search_keyword",
//             'model'  => $model_option,
//             'temperature' => (float)$getTemperature,
//             'max_tokens' => (float)$getMaxTokens,
//             'top_p' => (float)$top_p,
//             'best_of' => (float)$best_of,
//             "frequency_penalty" => (float)$frequency_penalty,
//             "presence_penalty" => (float)$presence_penalty,
//           )); 

//         $curl = curl_init('https://api.openai.com/v1/completions');
//         $options = array(
//             CURLOPT_POST => true,
//             CURLOPT_HTTPHEADER =>$header,
//             CURLOPT_POSTFIELDS => $params,
//             CURLOPT_RETURNTRANSFER => true,
//         );
//         curl_setopt_array($curl, $options);
//         $response = curl_exec($curl);
//         $httpcode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        
//         if(200 == $httpcode){
//             $json_array = json_decode($response, true);
//             $choices = $json_array['choices'];
//             $postContent = $choices[0]["text"];
//             $result_data = array('status'=>'success',
//                                  'content_data'=>trim($postContent),
//                                  'message' =>'Successfully'
//                                 );

//     return response($result_data, 200);
//         }else{
//             $json_array = json_decode($response, true);
//             $choices = $json_array['choices'];
//             $postContent = $choices[0]["text"];
//             $result_data = array('status'=>'error',
//                                  'content_data'=>$postContent,
//                                  'message' => "Something went to wrong!"
//                                 );
//         }
       
//     return response($result_data, 200);   
//     }






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
