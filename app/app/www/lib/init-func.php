<?php

/* start our session */
session_start();

/* require config, functions and db files */
require 'lib/config.php';
require 'lib/functions.php';
require 'vendor/idiorm.php';
require 'vendor/paris.php';
require 'vendor/Stripe.php';

/* require our model files */
require 'models/Config.php';
require 'models/Users.php';
require 'models/Reviews.php';
require 'models/Messages.php';
require 'models/Payments.php';
require 'models/Keys.php';
require 'models/Api_docs.php';
require 'models/Requests.php';
require 'models/Blogs.php';
require 'models/Pages.php';
require 'models/Geolocation.php';
require 'models/Proposals.php';




/* set db credentials for our ORM */
ORM::configure('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name']);
ORM::configure('username', $config['db_username']);
ORM::configure('password', $config['db_password']);

//Make base URL
$base_url = 'http://fadedbarbershop.co.uk/rest-all.php/provider';

//Make device URL
$device_url = '/#/';

//Initiate curl
require_once 'curl/curl.php'; 
$curl = new Curl;


// Make curl functions

function process_api_get($base_url,$extension)
{
	$curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $base_url . $extension,
		CURLOPT_CUSTOMREQUEST => 'GET'
));
	
$resp = curl_exec($curl);
curl_close($curl);
return json_decode($resp);
}

function process_api_post($input,$base_url,$extension)
{
	$obj = json_encode($input);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
    curl_setopt($ch, CURLOPT_POSTFIELDS, $obj);       
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0');
    curl_setopt($ch, CURLOPT_URL, $base_url . $extension);
    
    if (isset($_COOKIE[session_name()]))
        curl_setopt($ch, CURLOPT_COOKIE, session_name().'='.$_COOKIE[session_name()].'; path=/');
 
  session_write_close();    
	$result = curl_exec($ch);
	curl_close($ch);
	session_start();
  return json_decode($result); 
}

function process_api_put($input,$base_url,$extension)
{

  $obj = json_encode($input);
	$ch = curl_init();
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
  curl_setopt($ch, CURLOPT_POSTFIELDS, $obj);       
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_COOKIESESSION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0');   
	curl_setopt($ch, CURLOPT_URL, $base_url . $extension);
  if (isset($_COOKIE[session_name()]))
       curl_setopt($ch, CURLOPT_COOKIE, session_name().'='.$_COOKIE[session_name()].'; path=/');
 
  session_write_close(); 
	$result = curl_exec($ch);
	curl_close($ch);
	session_start();
  return json_decode($result); 
    
}
function process_api_delete($input,$base_url,$extension)
{
	$obj = json_encode($input);
	$ch = curl_init();
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");   
  curl_setopt($ch, CURLOPT_POSTFIELDS, $obj);       
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $base_url . $extension);
	$result = curl_exec($ch);
	curl_close($ch);
  return json_decode($result); 
}



//get product data

$product_data = (object) array(
	'stripe_secret_key' =>'xxx',
	'stripe_publishable_key'=>'xxx',
	'paypal_environment' => 'sandbox',
	'payment_type' => 'input',
	'https_redirect' => 0,
	'email' => 'sshussain270@gmail.com',
	'show_description' => 1,
	'page_title' => 'Faded',
	'show_billing_address' => 1,
	'name' => 'sshussain',
	'enable_paypal' => 1,
	'enable_subscriptions' => 'stripe_and_paypal',
	'paypal_email' => 'sshussain270@gmail.com',
	'subscription_length' => 0,
	'subscription_interval' => 1,
	'currency' => 'USD',
	'enable_trial' => 0,
	'trial_days' => 7,
	'notification_status' => 'check',
	'twilio_sid' => 'xxx',
	'twilio_token' => 'xxx',
	'pricepermeter' => '25',
	'paypal_username' => '',
	'paypal_password' => '',
	'paypal_signature' => '',
	'paypal_email_subject' => '',
	'commission_percentage' => '',
	'credits' => 0,
	'email_paypal' => '',
	'password' => '',
	

);


//Initiate twilio
require('twilio/Services/Twilio.php');
$twilio = new Services_Twilio($product_data->twilio_sid, $product_data->twilio_token);


//Other functions to be used

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


function objectToArray($obj, &$arr){

    if(!is_object($obj) && !is_array($obj)){
        $arr = $obj;
        return $arr;
    }

    foreach ($obj as $key => $value)
    {
        if (!empty($value))
        {
            $arr[$key] = array();
            objToArray($value, $arr[$key]);
        }
        else
        {
            $arr[$key] = $value;
        }
    }
    return $arr;
}


function get_username_by_id($id)
{
    $ju=get_username_id_db($id);
    
    $usr =json_decode($ju,true);

    if ($usr['username']){
        return $usr['username'];
    }else{
        return $ju;
    }
    
}



function get_username_id_db($id){
    try 
			 {
				if ($id)
					{
						$users = Model::factory('Users')->where('id',$id)->find_one();
						$response = array(
						'username'=>$users->username,
						); 
				
					}
				else
				{
					$status = "danger";
					$message = 'You need provide an id.';
					
					$response = array(
					'status' => $status,
					'message' => $message
					);
				}
					
			 } 
			catch (Exception $e) 
				{
					$status = "danger";
					$message = $e->getMessage();
				
					$response = array(
					'status' => $status,
					'message' => $message
					);
				}
			
			
			
			return json_encode($response);
}
function get_address_by_coordinates($latitude, $longitude)
{



 $lat=$latitude;
$long = $longitude;

$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_ENCODING, "");
$curlData = curl_exec($curl);
curl_close($curl);

$address = json_decode($curlData);
return $address->results[0]->formatted_address;
	
}