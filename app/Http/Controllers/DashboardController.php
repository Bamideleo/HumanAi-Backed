<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;


use Illuminate\Http\Response;
use DB;


class DashboardController extends Controller
{
    //
    
    public function index(){
      $userId = auth()->user()->id;
      $data = [];
      $data['total_app'] = DB::table('user_app')->where('user_id', $userId)->count();
      $data['total_autoresponder'] = DB::table('optin_email_accounts')->where('user_id', $userId)->count();
      $data['total_order'] = DB::table('payment_history')->where('user_id', $userId)->count();
      $data['total_payment_gateway'] = DB::table('payment_gateway')->where('user_id', $userId)->count();
      $data['app_install'] = DB::table('app_config')->where('user_id', $userId)->where('type', 'install')->count();
      $data['page_view'] = DB::table('app_config')->where('user_id', $userId)->where('type', 'view')->count();
      
      $response = [
        'status' => 'success',
        'message' => 'dashboard',
        'data' => $data,
         ];
        return response($response, 200);
    }
    
    public function getUserPackage(){
        $data = [];
       $userId = auth()->user()->id;
       $user = User::where('id', $userId)->first();
       $data['fe'] = $user->fe;
       $data['oto1'] = $user->oto_1;
       $data['oto2'] = $user->oto_2;
       $data['oto3'] = $user->oto_3;
       $data['oto4'] = $user->oto_4;
       $data['oto5'] = $user->oto_5;
        $data['oto6'] = $user->oto_6;
         $data['oto7'] = $user->oto_7;
          $data['oto8'] = $user->oto_8;
         
         $response = [
        'status' => 'success',
        'message' => 'User Otos',
        'data' => $data,
         ];
        return response($response, 200);
         
        }
}
