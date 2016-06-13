<?php

require 'lib/vendor/PHPMailer/PHPMailerAutoload.php';
require 'lib/init-func.php';


$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ( !empty($action) ) 
{
	switch ( $action ) 
	{
		case 'register':
			
			
		try { 
							
					$input = array (
					"username" => post('username'),
					"email" => post('email'),
					"password" => post('password'),
					"confirm_password" => post('confirm_password'),
					"type" => post('type')
					);
				  $extension = '/register';

					$result = process_api_post($input,$base_url,$extension);

                                                               
					msg($result->message, $result->status);
					if($result->status == 'danger')
					{
						go($device_url . 'register.php'); 
					}
					else
					{
						go($device_url . 'login.php'); 
					}

    			} 
			catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
				    go($device_url . 'register.php'); 
					}

		break;
		
		case 'login':
			try {
	
					$input = array (
					"username" => post('username'),
					"password" => post('password'),
					);
				
					$extension = '/login';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);
				  if($result->status == 'danger')
					{
						go('login.php'); 
					}
					else
					{
						go($device_url . 'side-menu/requests.php'); 
					}		
					} 
					
			catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
				   	go($device_url . 'login.php'); 
					}
				
		break;
			
		
			
		case 'logout':
			try {
				  
				  $input ='';
					$extension = '/logout';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);
					if($result->status == 'danger')
					{
						go($device_url . 'login.php'); 
					}
					else
					{
						go($device_url . 'side-menu/requests.php'); 
					}		
				
					} 
					
			catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
				    go($device_url . 'side-menu/requests.php'); 
					}
				
		break;
			
			case 'forgot_password':	
			try {
							
					$input = array (
					"email" => post('email'),
					);
				
					$extension = '/forgot_password';
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);
					if($result->status == 'danger')
					{
						go($device_url . 'forgot-password.php'); 
					}
					else
					{
						go($device_url . 'login.php'); 
					}		
	

    			} 
			catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
				    go($device_url . 'forgot-password.php'); 
					}
			
			
		break;
			
		case 'user_data':	
			try {

					$extension = '/user_data';
				  $input = '';
					$result = process_api_get($input,$base_url,$extension);
					
	

    			} 
			catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
			
			go($device_url . 'side-menu/requests.php');
			
		break;
		
			
		case 'save_account_settings':
			try {
				
					$file_extension = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
          $target_dir = "../uploads/profile_pictures/";
					$target_file = $target_dir . $_SESSION['userid'] . '.' . $file_extension;
					move_uploaded_file( $_FILES["profile_picture"]["tmp_name"], $target_file );
				
					$input = array (
					"username" => post('username'),
					"email" => post('email'),
					"profile_picture" => 'http://dev.radiumenterprises.co.uk/viitgo/uploads/profile_pictures/' . $_SESSION['userid'] . '.' . $file_extension,
					"first_name" => post('first_name'),
					"last_name" => post('last_name'),
					"gender" => post('gender'),
					"qualification" => post('qualification'),
					"date_of_birth" => post('date_of_birth'),
					"language_of_teaching" => post('language_of_teaching'),
					"teaching_experience" => post('teaching_experience'),
					"fee" => post('fee'),
					"free_demo" => post('free_demo'),
					"institute" => post('institute'),
					"subject" => post('subject'),
					"degree" => post('degree'),
					"level" => post('level'),
					"grade" => post('grade'),
					"volunteering" => post('volunteering'),
					"specialities" => post('specialities'),
					);
				
					$extension = '/save_account_settings';
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);
				
			} 
					
		catch (Exception $e) 
					{
						msg($e->getMessage(), "danger");
					}
		
				
			go($device_url . 'side-menu/account-settings.php');
				
		break;
			
			
		case 'save_password':
		try {
	
					$input = array (
					"old_password" => post('old_password'),
					"new_password" => post('new_password'),
					"confirm_new_password" => post('confirm_new_password'),
						
					);
				
					$extension = '/save_password';
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);
					
					} 
			
			catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
			
			go($device_url . 'side-menu/password-settings.php');
				
		break;
			
			case 'save_payment_method':
		try {
	
					$input = array (
					"email_paypal" => post('email_paypal'),
						
					);
				
					$extension = '/save_payment_method';
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);
					
					} 
			
			catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
			
			go($device_url . 'side-menu/payment-settings.php');
				
		break;
			
			

			
		case 'create_message':
			
			
				try {
							
					$input = array (
					"content" => post('content'),
					);
				
					$extension = '/message/create';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/messages.php');	
			

		break;
			
			
		case 'edit_message':
			

			
			try {
							
					$input = array (
					"content" => post('content'),
					);
				
					$extension = '/message/edit/' . post('id');
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/messages.php');	

		break;
			
			case 'reply_message':
			

			
			try {
							
					$input = array (
					"to_user_id" => post('to_user_id'),
					"content" => post('content'),
					);
				
					$extension = '/message/reply';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/messages.php');	

		break;
			
			
    
		case 'delete_message':
			
			
			try {
							
					$extension = '/message/delete/' . $_GET['id'];
					$result = process_api_delete($input,$base_url,$extension);
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/messages.php');	
		break;
			

			
		case 'create_review':
			
			
				try {
							
					$input = array (
					"content" => post('content'),
					);
				
					$extension = '/review/create';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/reviews.php');	
			

		break;
			
			
		case 'edit_review':
			

			
			try {
							
					$input = array (
					"content" => post('content'),
					);
				
					$extension = '/review/edit/' . post('id');
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/reviews.php');	

		break;
			
			
    
		case 'delete_review':
			
			
			try {
							
					$extension = '/review/delete/' . $_GET['id'];
					$result = process_api_delete($input,$base_url,$extension);
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/reviews.php');	
		break;
			
		case 'create_key':
			
			
				try {
							
					$input = array (
					"device" => post('device'),
					"key" => post('key'),
					"description" => post('description'),
					);
				
					$extension = '/key/create';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/keys.php');	
			

		break;
			
			
		case 'edit_key':
			

			
			try {
							
					$input = array (
					"device" => post('device'),
					"key" => post('key'),
					"description" => post('description')
					);
				
					$extension = '/key/edit/' . post('id');
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/keys.php');	

		break;
			
			
    
		case 'delete_key':
			
			
			try {
							
					$extension = '/key/delete/' . $_GET['id'];
					$result = process_api_delete($input,$base_url,$extension);
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/keys.php');	
		break;
			
		case 'approve_key':
			
			
			try {
							
					$input = array (
			"id" => $_GET['id'],

			); 
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/keys.php');	
		break;
			
		
			
			
		case 'payment_create':


			$input = array (
			"token" => post('token'),
			"name" => post('name'),
			"email" => post('email'),
			"description" => post('description') ? post('description') : 'no description entered',
			"address" => post('address'),
			"city" => post('city'),
			"state" => post('state'),
			"zip" => post('zip'),
			"country" => post('country'),
			"invoice_id" => post('invoice_id') ? post('invoice_id') : '',
			"item_id" => post('item_id') ? post('item_id') : '',
			"amount" => post('amount') ? post('amount') : '',
			"payment_type" => post('payment_type')
			);

			try {

					$extension = '/payment/create/';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

			} catch (Exception $e) {
				$status = false;
				$message = $e->getMessage();
			}
			

			$response = array(
				'status' => $status,
				'message' => $message
			);
			header('Content-Type: application/json');
			die(json_encode($response));

		break;



		case 'paypal_ipn':
			
			$input = array (
			"reason_code" => post('reason_code') ? post('reason_code') : '',
			"custom" => post('custom'),
			"payment_gross" => post('payment_gross'),
			"item_name" => post('item_name') ? post('item_name') : '',
			"txn_type" => post('txn_type'),
			"txn_id" => post('txn_id'),
			"subscr_id" => post('subscr_id'),
			"amount3" => post('amount3'),
			"subscr_date" => post('subscr_date'),
			"subscr_id" => post('subscr_id'),
		
			);

			try {

					$extension = '/payment/paypal_ipn';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

					} 
			catch (Exception $e) {
				$status = false;
				$message = $e->getMessage();
			}
			

			$response = array(
				'status' => $status,
				'message' => $message
			);
			header('Content-Type: application/json');
			die(json_encode($response));

		break;



		case 'paypal_success':
			go('index.php#status=paypal_success');
		break;
			
		case 'delete_payment':
			
			
			try {
							
					$extension = '/payment/delete/' . $_GET['id'];
					$result = process_api_delete($input,$base_url,$extension);
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/payments.php');	
		break;
			
			
		case 'create_request':
			
			
				try {
							
					$input = array (
					"origin_latitude" => post('origin_latitude'),
					"origin_longitude" => post('origin_longitude'),
						"destination_latitude" => post('destination_latitude'),
						"destination_longitude" => post('destination_longitude'),
						"distance" => post('distance'),
						"duration" => post('duration'),
					
					);
				
					$extension = '/request/create';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/requests.php');	
			

		break;
			
			
		case 'edit_request':
			

			
			try {
							
					$input = array (
					"latitude" => post('latitude'),
					"longitude" => post('longitude'),
					);
				
					$extension = '/request/edit/' . post('id');
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/requests.php');	

		break;
			
			
    
		case 'delete_request':
			
			
			try {
							
					$extension = '/request/delete/' . $_GET['id'];
					$result = process_api_delete($input,$base_url,$extension);
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/requests.php');	
		break;
			
		case 'messageuser_request':
			
			
				try {
							
					$input = array (
					"to_user_id" => post('to_user_id'),
					"content" => post('content'),
					);
				
					$extension = '/request/messageuser';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/messages.php');	
			

		break;
			
				case 'reviewuser_request':
			
			
				try {
							
					$input = array (
					"request_id" => post('request_id'),
					"for_user_id" => post('for_user_id'),
					"content" => post('content'),
					);
				
					$extension = '/request/reviewuser';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/requests.php');	
			

		break;
			
	

    case 'request_accept':
			
			
			try {
					$input = array (
					"request_id" => $_GET['request_id'],
					);
				 $extension = '/request/accept';
					$result = process_api_put($input,$base_url,$extension);
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/requests.php');	
		break;
			
	
			
			case 'message_user':
			

			
			try {
							
					$input = array (
					"to_user_id" => post('to_user_id'),
					"content" => post('content'),
					);
				
					$extension = '/users/message';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				go($device_url . 'side-menu/messages.php');	

		break;
			
			case 'create_availability':
			
			
				try {
							
					$input = array (
					"start" => post('start'),
					"end" => post('end'),
					);
				
					$extension = '/availability/create';
					$result = process_api_post($input,$base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				
			

		break;
			
			
			case 'delete_availability':
			
			
				try {
							
				
					$extension = '/availability/delete/'. post('id');
					$result = process_api_delete($base_url,$extension);
					msg($result->message, $result->status);

    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
		
				
			

		break;
			
			case 'payment_release':
			
			
			try {
							
					$result = process_api_get($base_url,'/payment/release');
					msg($result->message, $result->status);
				
    			} 
				catch (Exception $e) 
					{
						msg($e->getMessage(), 'danger');
					}
					go($device_url . 'side-menu/payment-settings.php');	
		break;
		
	
			
  }
}