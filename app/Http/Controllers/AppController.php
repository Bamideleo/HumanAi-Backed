<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;

class AppController extends Controller
{
    // public function __construct()
    // {
    //     header("Access-Control-Allow-Origin: *");
        
    // }
    
    public function getAllTemplates()
    {
        $admin = DB::table('users')->where('role', 'admin')->first();
        $admin_id = $admin->id ?? 0;
        $userId = auth()->user()->id;
        $pre_templates = DB::table('user_app')
                            ->where('user_id', $admin_id)
                            ->where('temp', 1)
                            ->orderBy('id','desc')
                            ->get(['id', 'user_id', 'app_name','app_logo', 'display_image', 'category','app_mode','app_url','status']);
        // $templates = DB::table('user_app')->where('user_id', $userId)->where('temp', 0)->get();
        // $f_arr = array();
        // foreach($pre_templates as $key => $temp){
        //   $f_arr[$key]['id'] = $temp['id']; 
        //   //$f_arr[$key]['display_image'] = $temp['display_image'];
        // }
        $response = [
        'status' => 'success',
        'message' => 'successful',
         'templates' => $pre_templates,
         ];
        return response($response, 200);
        
    }

    public function getAllApp(){
    $userId = auth()->user()->id;
    $apps = DB::table('user_app')
            ->where('user_id', $userId)
            ->orderBy('id','desc')
            ->get(['id', 'user_id', 'app_name','app_logo', 'display_image', 'category','app_mode','app_url','status','domain']);
    
    if(!empty($apps)){
        
        $response = [
        'status' => 'success',
        'message' => 'successful',
         'apps' => $apps,
         
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'Data not found',
         ];
        return response($response, 200);
    }
    }

    public function createApp(Request $request){
     $validator = Validator::make($request->all(), [
            'app_name' => 'required|string',
            'slug' => 'required|string|unique:user_app',
            'app_mode' => 'required',
         ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
$userId = auth()->user()->id;

//Admin create App
if(auth()->user()->role == "admin"){
   $app = DB::table('user_app')->insertGetId([
              'user_id'=>  $userId,
               'app_name' => $request->app_name ?? '',
               'slug' => str_replace(',', '-', $request->slug),
               'app_mode' => $request->app_mode ?? '',
               'business_description' => $request->business_description ?? '',
               'keywords' => $request->keywords ?? '',
               'app_url' => 'https://myaiappz.com/'.str_replace(',', '-', $request->slug),
               'category' => $request->category ?? '',
               'temp'  => 1,
               //'app_url' => url('/').'/'.$request->slug,
              ]); 
}
elseif($request->template_id != "" && $request->template_id > 0){
    $tid = $request->template_id;
 $temp = DB::table('user_app')->where('id', $tid)->where('temp', 1)->first();
 $app = DB::table('user_app')->insertGetId([
              'user_id'=>  $userId,
               'app_name' => $request->app_name ?? '',
               'template_id' => $request->template_id, 
               'slug' => str_replace(',', '-', $request->slug),
               'app_mode' => $request->app_mode ?? '',
               'category' => $request->category ?? '',
               'business_description' => $request->business_description ?? '',
               'keywords' => $request->keywords ?? '',
               'app_url' => 'https://myaiappz.com/'.str_replace(',', '-', $request->slug),
               'app_settings' => $temp->app_settings,
               'popup' => $temp->popup,
               'customization' => $temp->customization,
               'appMenuSettings' => $temp->appMenuSettings,
               'app_content' => $temp->app_content,
               'display_image' => $temp->display_image,
              ]);
}
else{
$app = DB::table('user_app')->insertGetId([
              'user_id'=>  $userId,
               'app_name' => $request->app_name ?? '',
               'slug' => str_replace(',', '-', $request->slug),
               'app_mode' => $request->app_mode ?? '',
               'business_description' => $request->business_description ?? '',
               'keywords' => $request->keywords ?? '',
               'category' => $request->category ?? '',
               'app_url' => 'https://myaiappz.com/'.str_replace(',', '-', $request->slug),
              ]);
}              
     
         $response = [
        'status' => 'success',
        'message' => 'app created',
        'app_id' => $app,
        
      ];
    return response($response, 200);        

    }
    
    public function createExstingApp(Request $request)
    {
        $userId = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string',
            'url' => 'required|url|',
            'icon' => 'required',
          ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
    }
    
     if($request->hasFile('icon')){
      $file = $request->icon;
      $filename = time().'.'.$file->getClientOriginalExtension();
      $file->move(public_path('images/user_'.$userId.'/editor/icon'), $filename);
      
        $path = url('public/images/user_'.$userId.'/editor/icon').'/'.$filename;
     
  }
    
    $app = DB::table('user_app')->insertGetId([
              'user_id'=>  $userId,
               'app_name' => $request->app_name ?? '',
               'slug' => str_replace(' ', '-', $request->slug),
              'business_description' => $request->business_description ?? '',
              'app_mode' => $request->app_mode ?? '',
               'theme_color' => $request->theme ?? '',
               'bg_color' => $request->bg_color ?? '',
               'p_color' => $request->p_color ?? '',
               'category' => $request->category ?? '',
               'app_url' =>  $request->url,
               'app_logo' => $path ?? '',
              ]);
      $apps = DB::table('user_app')->where('id', $app)->first();        
     $response = [
            'status' => 'success',
            'message' => 'Successful',
            'app' => $apps,
             ];
            return response($response, 200);
    }
    
    

    public function getapp($id){
        $userId = auth()->user()->id;
        $apps = DB::table('user_app')->where('id', $id)->first();
        //dd($apps);
        if(!empty($apps)){
            
            $response = [
            'status' => 'success',
            'message' => 'successful',
            'app' => $apps,
            //  'app_name' => $apps->app_name,
            //  'template_id' => $apps->template_id,
            //  'app_url' => $apps->app_url,
            //  'slug' => $apps->slug,
            //  'business_description' => $apps->business_description,
            //  'app_mode' => $apps->app_mode,
            //  'keywords' => $apps->keywords,
            //  'app_settings' => $apps->app_settings,
            //  'popup' => $apps->popup,
            //  'customization' =>$apps->customization,
            //  'appMenuSettings' => $apps->appMenuSettings,
            //  'app_content' => $apps->app_content,
            //  'app_logo' => $apps->app_logo,
            
             ];
            return response($response, 200);
        }else{
            $response = [
            'status' => 'error',
            'message' => 'Data not found',
             ];
            return response($response, 200);
        }
        }


      public function updateApp(Request $request, $id){
     $validator = Validator::make($request->all(), [
            'app_name' => 'required|string',
            'domain'   => 'string|unique:user_app',
            //'slug' => 'required',
         ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
             $userId = auth()->user()->id;   
            $applogo = '';
//       if($request->hasFile('app_logo')){
//       $file = $request->app_logo;
//       $filename = time().'.'.$file->getClientOriginalExtension();
//       $file->move(public_path('images/user_'.$userId.'/editor/applogo'), $filename);
//       $applogo = url('public/images/user_'.$userId.'/editor/applogo').'/'.$filename;
//   }      
     
$app = DB::table('user_app')->where('id', $id)->where('user_id', $userId)->first();

DB::table('user_app')->where('id',$id)->where('user_id',$userId)->update([
              'app_name' => $request->app_name ?? $app->app_name,
               'slug' => str_replace(',', '-', $request->slug) ?? $app->slug,
               'app_mode' => $request->app_mode ?? $app->app_mode,
               //'app_url' => url('/').'/'.$request->slug,
               'business_description' => $request->business_description ?? $app->business_description,
               'keywords' => $request->keywords ?? $app->keywords,
               'app_settings' => $request->app_settings ?? $app_settings,
               'popup' => $request->popup ?? $app->popup,
               'customization' => $request->customization ?? $app->customization,
               'appMenuSettings' => $request->appMenuSettings ?? $app->appMenuSettings,
               'app_content' => $request->app_content ?? $app->app_content,
               'app_logo' => $request->app_logo ?? $app->app_logo,
               'display_image' => $request->display_image ?? $app->display_image,
               'domain' => $request->domain ?? $app->domain,
              ]);
     
         $response = [
        'status' => 'success',
        'message' => 'App updated sucessfully',
        
      ];
    return response($response, 200);        

    } 

    public function deleteApp($id)
    {
       
      if(DB::table('user_app')->where('id', $id)->delete()){
        
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

    public function uploadFiles(Request $request, $type)
    {
        $userId = auth()->user()->id;
        $validator = Validator::make($request->all(), [
               'files_input' => 'required|max:2000048',
                  ]);

                if ($validator->fails()) {
                  $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
        if($request->hasFile('files_input')){
      $file = $request->files_input;
      $filename = time().'.'.$file->getClientOriginalExtension();
      $file->move(public_path('images/user_'.$userId.'/editor/'.$type), $filename);
      $response = [
        'status' => 'success',
        'message' => 'Successful',
        'path' => url('public/images/user_'.$userId.'/editor/'.$type).'/'.$filename,
      ];
       return response($response, 200); 
  }
    } 
    
    public function monetize(){
        $userId = auth()->user()->id;
    $apps = DB::table('monetize')->where('user_id', $userId)->get();
    
    if(!empty($apps)){
        
        $response = [
        'status' => 'success',
        'message' => 'successful',
         'monetize' => $apps,
         
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'Data not found',
         ];
        return response($response, 200);
    }
    }
    
    public function storeMonetize(Request $request){
        $validator = Validator::make($request->all(), [
            'banner_image' => 'required',
            'banner_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:20048',
            'redirect_url' => 'required',
            'app'  => 'required',
         ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
     $userId = auth()->user()->id;            
        if($request->hasFile('banner_image')){
      $file = $request->banner_image;
      $filename = time().'.'.$file->getClientOriginalExtension();
      $file->move(public_path('images/banner/'.auth()->user()->id.'/'), $filename);
      $path = url('/').'/public/images/banner/'.auth()->user()->id.'/'.$filename;
     }
     $dd = DB::table('user_app')->where('id', $request->app)->first();
     DB::table('monetize')->insert([
              'user_id'=>  $userId,
               'app' => $request->app,
               'url' => $request->redirect_url,
               'banner' => $path,
               'app_name' => $dd->app_name ?? '',
              ]);
     
         $response = [
        'status' => 'success',
        'message' => 'Created',
      ];
    return response($response, 200);
    }
    
     public function editMonetize($id)
    {
        $userId = auth()->user()->id;
    $monetize = DB::table('monetize')->where('id',$id)->where('user_id', $userId)->first();
    if(!empty($monetize)){
        
        $response = [
        'status' => 'success',
        'message' => 'Successful',
         'monetize' => $monetize,
         
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'Data not found',
         ];
        return response($response, 200);
    }
    }
    
     public function monetizeByAppId($id)
    {
       
    $monetize = DB::table('monetize')->where('app',$id)->get();
    if(!empty($monetize)){
        
        $response = [
        'status' => 'success',
        'message' => 'Successful',
         'banners' => $monetize,
         
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'Data not found',
         ];
        return response($response, 200);
    }
    }
    
    public function updateMonetize(Request $request, $id)
    {
       $validator = Validator::make($request->all(), [
            'redirect_url' => 'required',
            'app'  => 'required',
         ]);
    if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
     $userId = auth()->user()->id;            
        if($request->hasFile('banner_image')){
      $old_banner = DB::table('monetize')->where('id',$id)->where('user_id', $userId)->first();        
      $file = $request->banner_image;
      $filename = time().'.'.$file->getClientOriginalExtension();
      $file->move(public_path('images/banner/'.auth()->user()->id.'/'), $filename);
      $path = url('/').'/public/images/banner/'.auth()->user()->id.'/'.$filename;
       //delete old banner
       $dfile = url('public/images/banner/'.auth()->user()->id).'/'.$old_banner->banner;
                if (file_exists($dfile)) {
                    unlink($dfile);
                    } 
        $data['banner'] = $path;            
     }
     
     $data = [
              'app' => $request->app,
               'url' => $request->redirect_url,
               
              ];
     
     DB::table('monetize')->where('id',$id)->update($data);
     
         $response = [
        'status' => 'success',
        'message' => 'Updated',
      ];
    return response($response, 200);  
    }
    
    public function deleteMonetize($id)
    {
       
      if(DB::table('monetize')->where('id', $id)->delete()){
        
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
    
    public function getAppBySlug($slug)
    {
        $count = DB::table('user_app')->where('slug', $slug)->count();
        if($count > 0){
            $app = DB::table('user_app')->where('slug', $slug)->first();
            $response = [
        'status' => 'success',
        'message' => 'App Availabel',
        'app' =>  $app,
        ];
        return response($response, 200);
        }else{
            $response = [
        'status' => 'error',
        'message' => 'Page not found',
        ];
        return response($response, 200);
        }
    }
    
    public function getAppByDomain($url)
    {
        $count = DB::table('user_app')->where('domain', $url)->count();
        if($count > 0){
            $app = DB::table('user_app')->where('domain', $url)->first();
            $response = [
        'status' => 'success',
        'message' => 'Domain Found',
        'app' =>  $app,
        ];
        return response($response, 200);
        }else{
            $response = [
        'status' => 'error',
        'message' => 'Domain not found',
        ];
        return response($response, 200);
        }
    }
    
    public function publishApp(Request $request, $id){
     $userId = auth()->user()->id;   
    DB::table('user_app')->where('id',$id)->where('user_id',$userId)->update([
        'status' => 1,
    ]);
    
    $response = [
        'status' => 'success',
        'message' => 'Published',
        ];
        return response($response, 200);
    }
    
    public function saveAppDetails(Request $request)
    {
      DB::table('app_config')->insert([
              'user_id'=>  $request->uid ?? 0,
               'app_id' => $request->app_id ?? 0,
               'type' => $request->type ?? '',
               'status' => 1
              ]);
     
         $response = [
        'status' => 'success',
        'message' => 'App Config Saved',
      ];
    return response($response, 200); 
    }
    

}