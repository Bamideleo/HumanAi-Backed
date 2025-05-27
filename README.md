AppMax API Endpoints and Parameters
##############################

Register User | POST Request to http://localhost/appmax/api/register
	Parameter{name, email, password, password_confirmation}

User Login | POST Request to http://localhost/appmax/api/login
	Parameter{email, password}

Forgot Password | POST Request to http://localhost/appmax/api/reset	
	Parameter{email}

get Password reset token | GET Request to http://localhost/appmax/api/password/reset/{token}

Reset Password | POST Request to http://localhost/appmax/api/password/reset
  parameter{token,email,password, passord_confirmation}

User Profile | GET Request to http://localhost/appmax/api/profile
Update Profile | POST Request to http://localhost/appmax/api/profile
	Parameter{name, email}

change-password | POST Request to http://localhost/appmax/api/change-password
	Parameter{current_password, password, password_confirmation}

profile-image | GET Request to http://localhost/appmax/api/profile-image
change profile image | POST Request to http://localhost/appmax/api/profile-image	Parameter{user_image} 

#Product
Product | Get Request to http://localhost/appmax/api/products
Add Product | POST to  	http://localhost/appmax/api/products
	Parameter {name,price,des,product_image,category,url}
Get single product | Get to http://localhost/appmax/api/products/{id}	
update Product | Post to http://localhost/appmax/api/products/{id}
	parameter{name,price,des,product_image,category,url}
search product | Get to http://localhost/appmax/api/products/search/{name}
Delete Product | Delete to http://localhost/appmax/api/products/{id}

#Category
Category | Get Request to http://localhost/appmax/api/category
Add Category | POST to  	http://localhost/appmax/api/category
	Parameter {name}
Get single Category | Get to http://localhost/appmax/api/category/{id}	
update Category | Post to http://localhost/appmax/api/category/{id}
	parameter{name}
search Category | Get to http://localhost/appmax/api/category/search/{name}
Delete Category | Delete to http://localhost/appmax/api/category/{id}

#SMS
Get Twilio Setting | Get to http://localhost/appmax/api/get-twilio
Save Twilio Setting | Post to http://localhost/appmax/api/twilio-setting
  parameter{twilio_number,twilio_sid,twilio_token}
Get Sms Number | Get to http://localhost/appmax/api/get-numbers
Add Numbers | Post to  http://localhost/appmax/api/save-numbers
	parameter{phone_number}
SEND SMS | Post to http://localhost/appmax/api/send-sms
	parameter{numbers[], body}

#Payment
Get Stripe Keys | Get to http://localhost/appmax/api/get-stripeKey
Add Stripe Keys | Post to http://localhost/appmax/api/save-stripeKey
	parameter{stripe_key,public_key}
Pay with Srtipe | Post to http://localhost/appmax/api/paywithstripe
	parameter{amount, description,product_name,currency}
Get Paypal key | Get to http://localhost/appmax/api/get-paypay
Add Paypal key | Post to http://localhost/appmax/api/post-paypalEmail
	parameter{paypal_key}
Pay with Paypal | Post to http://localhost/appmax/api/paywithpaypal
 parameter{amount,description,product_name,currency}	

 #AutoResponder
 Integrate with auto | GET to http://localhost/appmax/api/settings/optin/{account} --> (aweber | mailchimp)
 Add Getrespond | Post to http://localhost/appmax/api/settings/optin/getresponse
 	parameter{api_key}
 Return Aweber code | POST to https://appbk.myaiappz.com/api/authorize/aweber
    parameter{code}
Return Mailchimp code | POST to https://appbk.myaiappz.com/api/authorize/mailchimp
    parameter{code}    
 Get Autoespoonder List | Get to http://localhost/appmax/api/optin/list/{account} --> (aweber | mailchimp | getresponse)
 Delete AutoResponder Account | Get to http://localhost/appmax/api/optin/delete/{account} --> (aweber | mailchimp | getresponse)
 Get active Autoresponder
 GET Request to https://appbk.myaiappz.com/api/optin-all

 #OrderHistory
 Get History | GET to http://localhost/appmax/api/order-history
 delete History | Delete to http://localhost/appmax/api/delete-order-history/{id}

 #AI
 Generate Image | POST to http://localhost/appmax/api/post-ai-image
	parameter{title,style,lightning,artist,medium,mood,resolution,max_results}
Generate Content | POST to http://localhost/appmax/api/post-ai-content
	parameter{search_key_word}
	
#APPS
Get all apps by user | GET to https://appbk.myaiappz.com/api/apps
Craete App | POST to https://appbk.myaiappz.com/api/create-app
    parameter{app_name,slug,app_mode,template_id,business_description,keywords}
Get single app | GET to https://appbk.myaiappz.com/api/app/{id}
Update App | POST to https://appbk.myaiappz.com/api/update-app/{id}
    parameter{app_name,slug,app_mode,business_description,keywords,app_settings,popup,customization,appMenuSettings,app_content,app_logo,display_image,domain}
Delete App | delete to https://appbk.myaiappz.com/api/delete-app/{id}  
    
#Templates
Get All Templates | GET to https://appbk.myaiappz.com/api/templates
    
#Upload Files
POST Request To https://appbk.myaiappz.com/api/uploadfiles/{type} --> (app_logo | images | audio | video | display_image)
 parameter{files_input}
 
#Monetize
Route::get('/monetize');
Route::post('/add-monetize') -- (banner_image,redirect_url,app);
Route::get('/monetize/{id}');
Route::post('/update-monetize/{id}') -- (banner_image,redirect_url,app); 
Route::delete('/delete-monetize/{id}');
get(monetize-by-appid/{id})

#Coupon
Route::get('coupon')
Route::get('edit-coupon/{id}')
Route::post('create-coupon') -- (coupon_token,amount,ex_date);
Route::post('update-coupon/{id}') -- (coupon,amount,ex_date);
Route::delete('delete-coupon/{id}')

#Dashboad User 
GET Request to https://appbk.myaiappz.com/api/user-dashboard


#Agency User
Route::get('/agency');
Route::post('/add-agency') -- (name, email, password, password_confirmation, oto1);
Route::get('/agency/{id}');
Route::post('/update-agency/{id}') -- (name, email, oto1);
Route::delete('/delete-agency/{id}'); 

#Push Notification
Route::post('save-push-notifacation-token') -- (pin);
Route::post('send-push-notifacation') -- (title,body,push_image,push_url);

#Slug and Domain
Route::get('getapp-by-slug/{slug}');
Route::get('getapp-by-domain/{site}');

#exsting Site
Route::post('/create-exsting-app') -- (app_name,slug,app_mode,business_description,theme,bg_color,p_color,category,url,icon);

#Publish App
Route::post('/publish-app/{app_id})

#Admin
Route::get('/users');
Route::post('/create-users') -- (name, email, password, password_confirmation)
 Route::get('/edituser/{id}');
 Route::post('/update-user/{id}') -- (name,email,oto1,oto2,oto3,oto4,oto5,oto6,oto7,oto8) || (password);
 Route::delete('/deleteuser/{id}');
 
#Google
Route::post('/save-google-key') -- (google_key)
Route::get('/get-google-key/{id})

#App Config
Route::post('/app-config') -- (uid, app_id, type-(install || view))

#Stripe
Route::post('/stripe-config/{uid}) -- (amount)

#automation
Route::post('/add-sub-user') -- (name,email,user_id,app_id)
Route::get('/automation')
Route::post('/send-auto-email') -- (emails,subject,message)



#user logout
 Post To https://appbk.myaiappz.com/api/logout;  	


	






