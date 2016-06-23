<?php

//Make base URL
$base_url = 'http://fadedbarbershop.co.uk/rest-all.php/provider';

//Make device URL
$device_url = '/#/';

$functions_url = 'http://fadedbarbershop.co.uk/mb-provider/functions.php';

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

 $product_data = process_api_get($base_url,'/product_data'); 


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
	
	$base_url = 'http://fadedbarbershop.co.uk/admin/rest.php';
	
	
    $user = process_api_get($base_url,'/username_by_id/'. $id);
	
    if( $user->username )
		{
			return $user->username;
		}
		else
		{
			return 'Username not found';
		}
		
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