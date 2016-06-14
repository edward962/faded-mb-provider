<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Credentials: true');
ob_start();
session_start();

require 'lib/vendor/PHPMailer/PHPMailerAutoload.php';
require 'lib/init-rest.php';
require 'lib/Slim/Slim.php';
use lib\Slim\Middleware\SessionCookie;

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(
	array(
'cookies.encrypt' => true,
    'cookies.secret_key' => 'my_secret_key',
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' => MCRYPT_MODE_CBC,
		'name' => 'dummy_session',
  'autorefresh' => true,
  'lifetime' => '1 hour'
		)

);

$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => '',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));

//
//$app->add(new \Slim\Middleware\SessionCookie(array(
//    'expires' => '20 minutes',
//    'path' => '/',
//    'domain' => '',
//    'secure' => false,
//    'httponly' => false,
//    'name' => 'slim_sessioan',
//    'secret' => 'hello',
//    'cipher' => MCRYPT_RIJNDAEL_256,
//    'cipher_mode' => MCRYPT_MODE_CBC
//)));

/*
$app->hook('slim.before.dispatch', function () use ($app){

				$headers = $app->request->headers;
				$response = array();
        $app = \Slim\Slim::getInstance();

        $api_key = $headers['X-API-KEY'];

        // this could be a MYSQL query that parses an API Key table, for example
				$keys = Model::factory('Keys')->find_many();
				$keyset = array();
	 					foreach( $keys as $key )
						{
							array_push($keyset,$key->key);
						}


        if(in_array($api_key, $keyset)) {
                $authorized = true;
        } else if ($api_key == NULL) {
                $response["error"] = true;
                $response["message"] = '{"error":{"text": "api key not sent" }}';
                $app->response->headers['X-Authenticated'] = 'False';
                $authorized = false;
                $app->halt(401, $response['message']);
        } else {
                $response["error"] = true;
                $response["message"] = '{"error":{"text": "api key invalid" }}';
                $app->response->headers['X-Authenticated'] = 'False';
                $authorized = false;
        }

        if(!$authorized){ //key is false
                // dont return 403 if you request the home page
                $req = $_SERVER['REQUEST_URI'];
                if ($req != "/") {
                $app->halt('403', $response['message']); // or redirect, or other something
                }
        }

});
*/




$app->get("/product_data", function() use ($app)
		{
			try
			 {
						$product_datas = Model::factory('Config')->find_many();
						$response = array();
	 					foreach( $product_datas as $product_data )
						{

             		$response[$product_data->key] = $product_data->value;


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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


$app->post("/register", function() use ($app)
		{

     $input = $app->request()->getBody();
		 $input = json_decode($input);

			try {
				if ($input->username && $input->email && $input->password && $input->confirm_password &&  $input->password == $input->confirm_password)
						{
							$users = Model::factory('Users')->create();
							$users->username = $input->username;
							$users->email = $input->email;
							$users->phone_number = $input->phone_number;
	  					$users->password = md5($input->password);
							$users->type = $input->type;
							if($input->type == 'client')
							{
								$users->approved == 'yes';
							}
							else
							{
								$users->approved == 'no';
							}

							$users->save();
							$status = 'success';
							$message = 'Account created successfully.';
						}
				else
						{
							$status = 'danger';
							$message = 'Some error occured. Please try again.';
						}
    			}
			catch (Exception $e)
					{
						$status = 'danger';
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


	$app->post("/login", function() use ($app)
		{

			 $input = $app->request()->getBody();
		   	 $input = json_decode($input);

		   	/* diego pucci me@diegopucci.com for testing purposes
				$input = new stdClass();
				$input->username = $_POST["username"];
				$input->password = $_POST["password"];
			*/
			try
			{


				if ($input->username && $input->password)
				{
					$user = Model::factory('Users')->where("username",$input->username)->where("password",md5($input->password))->find_one();
					if($user)
					{
						/* diego pucci me@diegopucci.com */
						session_start();

						$_SESSION['userid'] = $user->id;

						//print_r($_SESSION); die;

						$status = 'success';
						$message = 'Logged in successfully.';
					}
					else
					{
							$status = 'danger';
							$message = 'Username or password incorrect';
					}
				}
				else
						{
							$status = 'danger';
							$message = 'Could not log you in. Please try again.';
						}

			}
			catch (Exception $e)
					{
						$status = 'danger';
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message,
			);
			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});



		$app->post("/logout",function() use ($app)
		{
			session_start();
			try {
						unset($_SESSION['userid']);
				   session_destroy();

						$status = 'success';
						$message = 'You have been logged out successfully';
					}

			catch (Exception $e)
					{
						$status = 'danger';
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});


		$app->post("/forgot_password", function() use ($app)
		{

			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ($input->email)
					{
						$user = Model::factory('Users')->where("email",$input->email)->find_one();
						$password = randomPassword();
						$user->set('password', md5($password));
					  $user->save();
				  	$mail = new PHPMailer;

						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp.secureserver.net';  // Specify main and backup SMTP servers
						//$mail->SMTPAuth = true;                               // Enable SMTP authentication
						//$mail->Username = 'user@example.com';                 // SMTP username
						//$mail->Password = 'secret';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						$mail->setFrom('admin@faded.com', 'Faded');
						$mail->addAddress($user->email, $user->username);     // Add a recipient
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = 'Your new password';
						$mail->Body    = 'This is the your new password <b>' . $password . '</b>.';
						$mail->AltBody = 'This is the your new password' . $password ;

						if(!$mail->send())
							{
								$status = "danger";
								$message = $mail->ErrorInfo;
							}
						else
							{
							  $status = "success";
								$message = 'You have been sent a new password in the email.';
							}
					}
			}
			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);
			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});


	$app->get("/user_data", function() use ($app)
		{
			try
			 {
			 	/* diego pucci me@diegopucci.com */
			 	session_start();
				if ($_SESSION['userid'])
					{
						$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
						$response = array(
             'id'=>$users->id,
							'username'=>$users->username,
							'email'=>$users->email,
							'phone_number'=>$users->phone_number,
							'password'=>$users->password,
							'type'=>$users->type,
							'credits'=>$users->credits,
							'profile_picture'=>$users->profile_picture,

						);

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



$app->get("/username_by_id/:id", function($id) use ($app)
		{
			session_start();
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->get("/request_data_by_id/:id", function($id) use ($app)
		{
	session_start();
			try
			 {
				if ($id)
					{
						$request = Model::factory('Requests')->where('id',$id)->find_one();
						$response = array(
						'id'=>$request->id,
							'origin_latitude'=>$request->origin_latitude,
							'origin_longitude'=>$request->origin_longitude,
							'destination_latitude'=>$request->destination_latitude,
							'destination_longitude'=>$request->destination_longitude,
'created_by_user_id'=>$request->created_by_user_id,
							'provided_to_user_id'=>$request->provided_to_user_id,
							'price'=>$request->price,
'started'=>$request->started,
'completed'=>$request->completed,
'reviewed_by_provider'=>$request->reviewed_by_provider,
'reviewed_by_client'=>$request->reviewed_by_client,
'distance'=>$request->distance,
'duration'=>$request->duration,
'picked up'=>$request->picked_up,
'dropped off'=>$request->dropped_off,
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->get("/geolocation_by_id/:id", function($id) use ($app)
		{
	session_start();
			try
			 {
				if ($id)
					{
						$geolocation = Model::factory('Geolocation')->where('id',$id)->find_one();
						$response = array(
						'id'=>$geolocation->id,
							'latitude'=>$geolocation->latitude,
							'longitude'=>$geolocation->longitude,
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->get("/page_by_id/:id", function($id) use ($app)
		{
	session_start();
			try
			 {
				if ($id)
					{
						$page = Model::factory('Pages')->where('id',$id)->find_one();
						$response = array(
						'id'=>$page->id,
							'title'=>$page->title,
							'content'=>$page->content,
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->get("/blog_by_id/:id", function($id) use ($app)
		{
	session_start();
			try
			 {
				if ($id)
					{
						$page = Model::factory('Blogs')->where('id',$id)->find_one();
						$response = array(
						'id'=>$page->id,
							'title'=>$page->title,
							'content'=>$page->content,
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get users

$app->get("/users", function() use ($app)
		{
	session_start();
			try
			 {
				if (1==1)
					{

						$users = Model::factory('Users')->find_many();
						foreach( $users as $user ){
							$response[] = array(

             		'id'=>$user->id,
								'username'=>$user->username,
								'email'=>$user->email,
								'type'=>$user->type,
								'credits'=>$user->credits,
								'profile_picture'=>$user->profile_picture,
								'approved' => $user->approved,
								'first_name' => $user->first_name,
								'last_name' => $user->last_name,
								'gender' => $user->gender,
								'qualification' => $user->qualification,
								'date_of_birth' => $user->date_of_birth,
								'language_of_teaching' => $user->language_of_teaching,
								'teaching_experience' => $user->teaching_experience,
								'fee' => $user->fee,
								'free_demo' => $user->free_demo,
								'institute' => $user->institute,
								'subject' => $user->subject,
								'degree' => $user->degree,
								'level' => $user->level,
								'grade' => $user->grade,
								'volunteering' => $user->volunteering,
								'specialities' => $user->specialities,


						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get availability by user id

$app->get("/availability_by_user_id/:user_id", function($user_id) use ($app)
		{
session_start();
			try
			 {
				if ($_SESSION['id'])
					{

						$availabilities = Model::factory('Availability')->where('user_id',$user_id)->find_many();
						foreach( $availabilities as $availability ){
							$response[] = array(

             		'id'=>$availability->id,
								 'user_id'=>$availability->user_id,
								 'start'=>$availability->start,
								 'end'=>$availability->end,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//---------------------CLIENT--------------------


$app->get("/client/product_data", function() use ($app)
		{
	session_start();
			try
			 {
						$product_datas = Model::factory('Config')->find_many();
						$response = array();
	 					foreach( $product_datas as $product_data )
						{

             		$response[$product_data->key] = $product_data->value;


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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



	$app->get("/client/user_data", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ($_SESSION['userid'])
					{
						$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
						$response = array(
             'id'=>$users->id,
							'session_code'=>$users->session_code,
							'username'=>$users->username,
							'email'=>$users->email,
							'type'=>$users->type,
							'credits'=>$users->credits,
							'profile_picture'=>$users->profile_picture,
							'approved'=>$users->approved,
							'first_name'=>$users->first_name,
							'last_name'=>$users->last_name,
							'phone_number'=>$users->phone_number,





						);

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



		$app->put("/client/save_account_settings", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ($input->username && $input->email)
				{

					$users = Model::factory('Users')->where('id', $_SESSION['userid'])->find_one();
				  $users->set('username', $input->username);
					$users->set('email', $input->email);
					if($input->profile_picture)
					{
					$users->set('profile_picture', $input->profile_picture);
					}
					$users->set('first_name', $input->first_name);
					$users->set('last_name', $input->last_name);
					$users->set('phone_number', $input->phone_number);
					$users->save();

				$status = "success";
				$message = 'Your settings have been saved successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error has occured. Please try again.';
				}

			}

			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);
			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});




		$app->put("/client/save_password", function() use ($app)
		{
			session_start();

			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ( $input->old_password && $input->new_password &&  $input->confirm_new_password && $input->new_password == $input->confirm_new_password  )
						{
							$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
							if(md5($input->old_password) == $users->password)
							{
								$users->set('password', md5($input->new_password));
								$users->save();

								$status = "success";
								$message = 'Password saved successfully.';
							}
							else
							{
								$status = "danger";
								$message = 'Your current password does not match the one in our database.';
							}
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again';
						}

    		}

			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});







//Get messages

$app->get("/client/messages", function() use ($app)
		{
			session_start();
$_SESSION['userid'] = 35;

			try
			 {
				if ($_SESSION['userid'])
					{

							$messages = Model::factory('Messages')->where_any_is(
							array(
               array('from_user_id'=> $_SESSION['userid']),
               array('to_user_id'=>$_SESSION['userid']),
								)
							)->find_many();
						foreach( $messages as $message ){
							$response[] = array(

             		'id'=>$message->id,
								 'to_user_id'=>$message->to_user_id,
								 'from_user_id'=>$message->from_user_id,
								 'content'=>$message->content,
								 'datetime'=>$message->datetime,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create message
		$app->post("/client/message/create", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;

			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content )
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = '0';
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Message created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Reply message
		$app->post("/client/message/reply", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;

			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content )
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = $input->to_user_id;
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Reply created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit message
			$app->put("/client/message/edit/:id", function($id) use ($app)
			{
				session_start();
				$_SESSION['userid'] = 35;

				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->content)
						{
							$messages = Model::factory('Messages')->where('id',$id)->where('to_user_id',$_SESSION['userid'])->find_one();
							$messages->set('content', $input->content);
							$messages->save();
							$status = "success";
							$message = 'Message edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete message

	$app->delete("/client/message/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;

			try
			 {
				if ( $id )
				{
					$messages = Model::factory('Messages')->where('id',$id )->where('to_user_id',$_SESSION['userid'])->find_one();
					$messages->delete();

					$status = "success";
					$message = 'Message deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get reviews

$app->get("/client/reviews", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$reviews = Model::factory('Reviews')->where('for_user_id',$_SESSION['userid'])->find_many();
						foreach( $reviews as $review ){
							$response[] = array(
             'id'=>$review->id,
							'by_user_id'=>$review->by_user_id,
							'for_user_id'=>$review->for_user_id,
							'content'=>$review->content,
							'datetime'=>$review->datetime,



						);
						}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create review
		$app->post("/client/review/create", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;

			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->content && $input->for_user_id )
				{
					$reviews = Model::factory('Reviews')->create();
					$reviews->content = $input->content;
					$reviews->by_user_id = $_SESSION['userid'];
					$reviews->for_user_id = '0';
					$reviews->datetime = date('Y-m-d H:i:s');
					$reviews->save();

					$status = "success";
					$message = 'Review created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit review
			$app->put("/client/review/edit/:id", function($id) use ($app)
			{
				session_start();
				$_SESSION['userid'] = 35;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->content)
						{
							$reviews = Model::factory('Reviews')->where('id',$id)->where('user_id',$_SESSION['userid'])->find_one();
							$reviews->set('content', $input->content);
							$reviews->save();
							$status = "success";
							$message = 'Review edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete review

	$app->delete("/client/review/delete/:id", function($id) use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ( $id )
				{
					$reviews = Model::factory('Reviews')->where('id',$id )->where('by_user_id',$_SESSION['userid'])->find_one();
					$reviews->delete();

					$status = "success";
					$message = 'Review deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get keys

$app->get("/client/keys", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$keys = Model::factory('Keys')->find_many();
							foreach( $keys as $key ){
							$response[] = array(
             'id'=>$key->id,
							'device'=>$key->device,
							'key'=>$key->key,
							'description'=>$key->description,
							'approved'=>$key->approved,

						);
							}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create key
		$app->post("/client/key/create", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->device && $input->key )
				{
					$keys = Model::factory('Keys')->create();
					$keys->device = $input->device;
					$keys->key = $input->key;
					$keys->description = $input->description;
					$keys->save();

					$status = "success";
					$message = 'Key created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit key
			$app->put("/client/key/edit/:id", function($id) use ($app)
			{
			session_start();
				$_SESSION['userid'] = 35;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->device && $input->key)
						{
							$keys = Model::factory('Keys')->where('id',$id)->find_one();
							$keys->set('device', $input->device);
							$keys->set('key', $input->key);
							$keys->set('description', $input->description);
							$keys->save();
							$status = "success";
							$message = 'Key edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete key

	$app->delete("/client/key/delete/:id", function($id) use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ( $id )
				{
					$keys = Model::factory('Keys')->where('id',$id )->find_one();
					$keys->delete();

					$status = "success";
					$message = 'Key deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Approve key

	$app->put("/client/key/approve/:id", function($id) use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ( $id )
				{
					$keys = Model::factory('Keys')->where('id',$id )->find_one();
					$keys->set('approved','yes');
					$keys->save();

					$status = "success";
					$message = 'Key approved successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get API docs
$app->get("/client/api_docs", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$api_docs = Model::factory('Api_docs')->find_many();
							foreach( $api_docs as $api_doc ){
							$response[] = array(
             'id'=>$api_doc->id,
								 'title'=>$api_doc->title,
								 'content'=>$api_doc->content,
						);
							}


					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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



			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get payments

$app->get("/client/payments", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$payments = Model::factory('Payments')->where('user_id',$_SESSION['userid'])->find_many();
								foreach( $payments as $payment ){
							$response[] = array(
             'id'=>$payment->id,
								 'user_id'=>$payment->user_id,
								 'invoice_id'=>$payment->invoice_id,
								 'name'=>$payment->name,
								 'email'=>$payment->email,
								 'address'=>$payment->address,
								 'city'=>$payment->city,
								 'state'=>$payment->state,
								 'zip'=>$payment->zip,
								 'country'=>$payment->country,
								 'amount'=>$payment->amount,
								 'description'=>$payment->description,
								 'type'=>$payment->type,
								 'cc_name'=>$payment->cc_name,
								 'cc_last_4'=>$payment->cc_last_4,
								 'stripe_transaction_id'=>$payment->stripe_transaction_id,
								 'paypal_transaction_id'=>$payment->paypal_transaction_id,
								 'date_created'=>$payment->date_created,




						); }


					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Make payment
$app->post("/client/payment/create", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->token )
				{
					// make sure we hve hte payment token first
				if ( !$input->token ) {
					throw new Exception('Payment could not be completed, please try again.');
				}

				// build customer data
				$name = $input->name;
				$name_arr = explode(' ', trim($name));
				$first_name = $name_arr[0];
				$last_name = trim(str_replace($first_name, '', $name));
				$email = $input->email;
				$description = $input->description ? $input->description : 'no description entered';
				$address = $input->address;
				$city = $input->city;
				$state = $input->state;
				$zip = $input->zip;
				$country = $input->country;

		if ( $input->amount ) {
					$amount = $input->amount;
					$type = 'input';
				// return error if amount not found
				} else {
					throw new Exception('No amount was specified.');
				}



					// do the payment now
					$transaction = Stripe_Charge::create(array(
					  'amount' => $amount * 100,
					  'currency' => $config['currency'],
					  'card' => post('token'),
					  'description' => isset($item) ? $item->name : $description
					));

					// save payment record
					$payment = Model::factory('Payments')->create();
					$payment->user_id = $_SESSION['userid'];
					$payment->invoice_id = isset($invoice) ? $invoice->id : null;
					$payment->name = $name;
					$payment->email = $email;
					$payment->amount = $transaction->amount / 100;
					$payment->description = isset($item) ? $item->name : $description;
					$payment->address = $address;
					$payment->city = $city;
					$payment->state = $state;
					$payment->zip = $zip;
					$payment->country = $country;
					$payment->type = $type;
					$payment->cc_name = $transaction->source->name;
					$payment->cc_last_4 = $transaction->source->last4;
					$payment->stripe_transaction_id = $transaction->id;
					$payment->save();


					//save credits in users table
					$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
					$total_credits = $users->credits + $transaction->amount / 100;
					$users->set('credits', $total_credits);
					$users->save();

					// set the message
					$message = 'Your payment has been completed successfully, you should receive a confirmation email shortly.';





				// build email values first
				$values = array(
					'customer_name' => $name,
					'customer_email' => $email,
					'amount' => currency($amount) . '<small>' . currencySuffix() . '</small>' . $trial,
					'description_title' => isset($item) ? 'Item' : 'Description',
					'description' => isset($item) ? $item->name : $description,
					'payment_method' => 'Credit Card' . (isset($transaction) ? ': XXXX-' . $transaction->source->last4 : ''),
					'transaction_id' => isset($transaction) ? $transaction->id : null,
					'subscription_id' => isset($subscription) ? $subscription->stripe_subscription_id : '',
					'manage_url' => isset($unique_subscription_id) ? url('manage.php?subscription_id=' . $unique_subscription_id) : '',
					'url' => url(''),
				);

					email($config['email'], 'payment-confirmation-admin', $values, 'You\'ve received a new payment!');
					email($email, 'payment-confirmation-customer', $values, 'Thank you for your payment to ' . $config['name']);




				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->post("/client/payment/paypal_ipn", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);

			try {


		    	// die if it's a refund notification
		    	if ( preg_match('/refund/', $input->reason_code) ) {
		    		die();
		    	}

		    	// parse our custom field data
				$custom = $input->custom;
				if ( $custom ) {
					parse_str($input->custom, $data);
				} else {
					$data = array();
				}
				// pull out some values
				$payment_gross = $input->payment_gross;
				$item_name = $input->item_name;

				// build customer data
				$name = isset($data['name']) && $data['name'] ? $data['name'] : null;
				$name_arr = explode(' ', trim($name));
				$first_name = $name_arr[0];
				$last_name = trim(str_replace($first_name, '', $name));
				$email = isset($data['email']) && $data['email'] ? $data['email'] : null;
				$description = $item_name ? $item_name : 'no description entered';
				$address = isset($data['address']) && $data['address'] ? $data['address'] : null;
				$city = isset($data['city']) && $data['city'] ? $data['city'] : null;
				$state = isset($data['state']) && $data['state'] ? $data['state'] : null;
				$zip = isset($data['zip']) && $data['zip'] ? $data['zip'] : null;
				$country = isset($data['country']) && $data['country'] ? $data['country'] : null;

				if ( $payment_gross ) {
					$amount = $payment_gross;
					$type = 'input';
				// return error if none found
				} else {
					$amount = 0;
					$type = '';
				}

				switch ( $input->txn_type ) {
					case 'web_accept':

						// save payment record
						$payment = Model::factory('Payment')->create();
						$payment->user_id = $_SESSION['userid'];
						$payment->invoice_id = isset($invoice) ? $invoice->id : null;
						$payment->name = $name;
						$payment->email = $email;
						$payment->amount = $amount;
						$payment->description = isset($item) ? $item->name : $description;
						$payment->address = $address;
						$payment->city = $city;
						$payment->state = $state;
						$payment->zip = $zip;
						$payment->country = $country;
						$payment->type = $type;
						$payment->paypal_transaction_id = $input->txn_id;
						$payment->save();

						//save credits in users table
						$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
						$total_credits = $users->credits + $amount;
						$users->set('credits', $total_credits);
						$users->save();


						// build email values first
						$values = array(
							'customer_name' => $payment->name,
							'customer_email' => $payment->email,
							'amount' => currency($payment->amount) . '<small>' . currencySuffix() . '</small>',
							'description_title' => isset($item) ? 'Item' : 'Description',
							'description' => $payment->description,
							'transaction_id' => $input->txn_id,
							'payment_method' => 'PayPal',
							'url' => url(''),
						);
						email($config['email'], 'payment-confirmation-admin', $values, 'You\'ve received a new payment!');


						email($payment->email, 'payment-confirmation-customer', $values, 'Thank you for your payment to ' . $config['name']);

					break;

				}


			}

			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


// Delete payment


$app->delete("/client/payment/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ( $id )
				{
					$payments = Model::factory('Payments')->where('id',$id )->where('user_id',$_SESSION['userid'])->find_one();
					$payments->delete();

					$status = "success";
					$message = 'Payment deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get requests

$app->get("/client/requests", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ($_SESSION['userid'])
					{
						$requests = Model::factory('Requests')->where('created_by_user_id',$_SESSION['userid'])->find_many();
						foreach( $requests as $request ){
							$response[] = array(
             		'id'=>$request->id,
								'start'=>$request->start,
								'end'=>$request->end,
								'price'=>$request->price,
								'paid'=>$request->paid,
								'accepted'=>$request->accepted,
								'reviewed_by_provider'=>$request->reviewed_by_provider,
								'reviewed_by_client'=>$request->reviewed_by_client,
								'created_by_user_id'=>$request->created_by_user_id,
								'provided_to_user_id'=>$request->provided_to_user_id,
						);
						}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




//Edit request
			$app->put("/client/request/edit/:id", function($id) use ($app)
			{
				session_start();
				$_SESSION['userid'] = 35;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->latitude  && $input->longitude)
						{
							$requests = Model::factory('Requests')->where('id',$id)->find_one();
							$keys->set('latitude', $input->latitude);
							$keys->set('longitude', $input->longitude);
							$keys->save();
							$status = "success";
							$message = 'Request edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete request

	$app->delete("/client/request/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ( $id )
				{
					$requests = Model::factory('Requests')->where('id',$id )->find_one();
					$requests->delete();

					$status = "success";
					$message = 'Request deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Request messageuser
		$app->post("/client/request/messageuser", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content && $input->to_user_id)
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = $input->to_user_id;
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Message created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Request reviewuser
		$app->post("/client/request/reviewuser", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content && $input->for_user_id && $input->request_id )
				{
					$messages = Model::factory('Reviews')->create();
					$messages->for_user_id = $input->for_user_id;
					$messages->by_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$request = Model::factory('Requests')->where('id',$input->request_id)->find_one();
					$request->set('reviewed_by_client', 'yes');
					$request->save();

					$status = "success";
					$message = 'Review created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Request pay
		$app->put("/client/request/pay", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->request_id && $_SESSION['userid'] )
				{
					$request = Model::factory('Requests')->where('id',$input->request_id)->where('created_by_user_id',$_SESSION['userid'])->find_one();
					$client =  Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
					$provider =  Model::factory('Users')->where('id',$request->provided_to_user_id)->find_one();
					if ($client->credits < $request->price)
					{ throw new Exception("You donot have enough credits to pay."); }
					//collect commission
					$admin = Model::factory('Config')->where('key','commission_percentage')->find_one();
					$commission = ($admin->value/100) *  $request->price;
					$fee_after_commission = $request->price - $commission;

					$client->set('credits', ($client->credits - $fee_after_commission));
					$client->save();
					$provider->set('credits', ($provider->credits + $fee_after_commission));
					$provider->save();
					$request->set('paid','yes');
					$request->save();

					$admin = Model::factory('Config')->where('key','credits')->find_one();
					$admin->set('value', ($admin->value + $commission));

					$status = "success";
					$message = 'Request payment completed successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get blogs
$app->get("/client/blogs", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$blogs = Model::factory('Blogs')->find_many();
								foreach( $blogs as $blog ){
							$response[] = array(
             'id'=>$blog->id,
									'title'=>$blog->title,
									'content'=>$blog->content,


						); }

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Get pages
$app->get("/client/pages", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$pages = Model::factory('Pages')->find_many();
							foreach( $pages as $page ){
							$response[] = array(
             'id'=>$page->id,
									'title'=>$page->title,
									'content'=>$page->content,


						); }

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


// Get username by id

$app->get("/client/username_by_id/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ($id)
					{
						$users = Model::factory('Users')->where('id',$id)->find_one();
						$response = array(
						'username'=>$users->username,
						'id'=>$users->id,
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get payment methods

$app->get("/client/payment_methods", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{

						$payment_methods = Model::factory('Payment_methods')->where('user_id',$_SESSION['userid'])->find_many();
						foreach( $payment_methods as $payment_method ){
							$response[] = array(

             		'id'=>$payment_method->id,
								'user_id'=>$payment_method->user_id,
								'type'=>$payment_method->type,
								'name_on_card'=>$payment_method->name_on_card,
								'card_number'=>$payment_method->card_number,
								'expiration_month'=>$payment_method->expiration_month,
								'expiration_year'=>$payment_method->expiration_year,
								'cvc'=>$payment_method->cvc,
								'paypal_email'=>$payment_method->paypal_email,
								'name'=>$payment_method->name,
								'email'=>$payment_method->email,
								'address'=>$payment_method->address,
								'city'=>$payment_method->city,
								'state'=>$payment_method->state,
								'zip'=>$payment_method->zip,
								'country'=>$payment_method->country,
								'primary'=>$payment_method->primary,
						);
						}

				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Create payment method
		$app->post("/client/payment_methods/create", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($_SESSION['userid'] && $input->type)
				{
					$payment_methods = Model::factory('Payment_methods')->create();
					$payment_methods->user_id = $_SESSION['userid'];
					$payment_methods->type = $input->type;
					$payment_methods->name_on_card = $input->name_on_card;
					$payment_methods->card_number = $input->card_number;
					$payment_methods->expiration_month = $input->expiration_month;
					$payment_methods->expiration_year = $input->expiration_year;
					$payment_methods->cvc = $input->cvc;
					$payment_methods->paypal_email = $input->paypal_email;
					$payment_methods->name = $input->name;
					$payment_methods->email = $input->email;
					$payment_methods->address = $input->address;
					$payment_methods->city = $input->city;
					$payment_methods->state = $input->state;
					$payment_methods->zip = $input->zip;
				  $payment_methods->country = $input->country;
					$payment_methods->primary = $input->primary;
					$payment_methods->save();

					$status = "success";
					$message = 'Payment method created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//delete payment method

$app->delete("/client/payment_methods/delete/:id", function($id) use ($app)
		{
		session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ( $id )
				{
					$payment_methods = Model::factory('Payment_methods')->where('id',$id )->where('user_id',$_SESSION['userid'])->find_one();
					$payment_methods->delete();

					$status = "success";
					$message = 'Payment method deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Payment method set primary
$app->put("/client/payment_methods/set_primary/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if ( $id )
				{
					//set all other payment methods to no primary
					$payment_methods = Model::factory('Payment_methods')->where('user_id',$_SESSION['userid'])->find_many();
					foreach($payment_methods as $payment_method)
					{
						$unprimary = Model::factory('Payment_methods')->where('id',$payment_method->id )->where('user_id',$_SESSION['userid'])->find_one();
						$unprimary->set('primary','no');
						$unprimary->save();
					}
					$primary = Model::factory('Payment_methods')->where('user_id',$_SESSION['userid'])->where('id',$id)->find_one();
					$primary->set('primary','yes');
					$primary->save();

					$status = "success";
					$message = 'Payment set to primary successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get users

$app->get("/client/users", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (1==1)
					{

						$users = Model::factory('Users')->find_many();
						foreach( $users as $user ){
							$response[] = array(

             		'id'=>$user->id,
								'username'=>$user->username,
								'email'=>$user->email,
								'type'=>$user->type,
								'credits'=>$user->credits,
								'profile_picture'=>$user->profile_picture,
								'approved' => $user->approved,
								'first_name' => $user->first_name,
								'last_name' => $user->last_name,
								'gender' => $user->gender,
								'qualification' => $user->qualification,
								'date_of_birth' => $user->date_of_birth,
								'language_of_teaching' => $user->language_of_teaching,
								'teaching_experience' => $user->teaching_experience,
								'fee' => $user->fee,
								'free_demo' => $user->free_demo,
								'institute' => $user->institute,
								'subject' => $user->subject,
								'degree' => $user->degree,
								'level' => $user->level,
								'grade' => $user->grade,
								'volunteering' => $user->volunteering,
								'specialities' => $user->specialities,


						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Users message
		$app->post("/client/users/message", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content )
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = $input->to_user_id;
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Message sent successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Create request
		$app->post("/client/users/request", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->provided_to_user_id && $input->start && $input->end)
				{
					$requests = Model::factory('Requests')->create();
					$requests->start = $input->start;
					$requests->end = $input->end;
					$requests->provided_to_user_id = $input->provided_to_user_id;
					$requests->created_by_user_id = $_SESSION['userid'];
					$requests->paid = 'no';
					$requests->accepted = 'no';
					$requests->reviewed_by_provider = 'no';
					$requests->reviewed_by_client = 'no';
					$requests->price = 0;

					$requests->save();

					$status = "success";
					$message = 'Request created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get availability by user id

$app->get("/client/availability_by_user_id/:user_id", function($user_id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{

						$availabilities = Model::factory('Availability')->where('user_id',$user_id)->find_many();
						foreach( $availabilities as $availability ){
							$response[] = array(

             		'id'=>$availability->id,
								 'user_id'=>$availability->user_id,
								 'start'=>$availability->start,
								 'end'=>$availability->end,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get reviews by user id

$app->get("/client/reviews_by_user_id/:user_id", function($user_id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 35;
			try
			 {
				if (isset($_SESSION['userid']))
					{

						$availabilities = Model::factory('Reviews')->where('for_user_id',$user_id)->find_many();
						foreach( $availabilities as $availability ){
							$response[] = array(

             		'id'=>$availability->id,
								 'by_user_id'=>$availability->by_user_id,
								'for_user_id'=>$availability->for_user_id,
								 'content'=>$availability->content,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


$app->post("/client/make_payment", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 35;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);

			try {

				switch ( $input->txn_type ) {
					case 'web_accept':

						// save payment record
						$payment = Model::factory('Payment')->create();
						$payment->user_id = $_SESSION['userid'];
						$payment->invoice_id = isset($invoice) ? $invoice->id : null;
						$payment->name = $name;
						$payment->email = $email;
						$payment->amount = $amount;
						$payment->description = isset($item) ? $item->name : $description;
						$payment->address = $address;
						$payment->city = $city;
						$payment->state = $state;
						$payment->zip = $zip;
						$payment->country = $country;
						$payment->type = $type;
						$payment->paypal_transaction_id = $input->txn_id;
						$payment->save();

						//save credits in users table
						$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
						$total_credits = $users->credits + $amount;
						$users->set('credits', $total_credits);
						$users->save();


						// build email values first
						$values = array(
							'customer_name' => $payment->name,
							'customer_email' => $payment->email,
							'amount' => currency($payment->amount) . '<small>' . currencySuffix() . '</small>',
							'description_title' => isset($item) ? 'Item' : 'Description',
							'description' => $payment->description,
							'transaction_id' => $input->txn_id,
							'payment_method' => 'PayPal',
							'url' => url(''),
						);
						email($config['email'], 'payment-confirmation-admin', $values, 'You\'ve received a new payment!');


						email($payment->email, 'payment-confirmation-customer', $values, 'Thank you for your payment to ' . $config['name']);

					break;

				}


			}

			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//------------------------PROVIDER-----------------------------

$app->get("/provider/product_data", function() use ($app)
		{
			try
			 {
						$product_datas = Model::factory('Config')->find_many();
						$response = array();
	 					foreach( $product_datas as $product_data )
						{

             		$response[$product_data->key] = $product_data->value;


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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


	$app->get("/provider/user_data", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
						$response = array(
             'id'=>$users->id,
							'username'=>$users->username,
							'email'=>$users->email,
							'type'=>$users->type,
							'credits'=>$users->credits,
							'profile_picture'=>$users->profile_picture,
							'email_paypal'=>$users->email_paypal,
							'approved'=>$users->approved,
							'first_name'=>$users->first_name,
							'last_name'=>$users->last_name,
							'phone_number'=>$users->phone_number,
							'gender'=>$users->gender,
							'qualification'=>$users->qualification,
							'date_of_birth'=>$users->date_of_birth,
							'language_of_teaching'=>$users->language_of_teaching,
							'teaching_experience'=>$users->teaching_experience,
							'fee'=>$users->fee,
							'free_demo'=>$users->free_demo,
							'institute'=>$users->institute,
							'subject'=>$users->subject,
							'degree'=>$users->degree,
							'level'=>$users->level,
							'grade'=>$users->grade,
							'volunteering'=>$users->volunteering,
							'specialities'=>$users->specialities,



							);

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



		$app->put("/provider/save_account_settings", function() use ($app)
		{
		session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ($input->username && $input->email)
				{

					$users = Model::factory('Users')->where('id', $_SESSION['userid'])->find_one();
				  $users->set('username', $input->username);
					$users->set('email', $input->email);
					if($input->profile_picture)
					{
					$users->set('profile_picture', $input->profile_picture);
					}
					$users->set('first_name', $input->first_name);
					$users->set('last_name', $input->last_name);
					$users->set('phone_number', $input->phone_number);
					$users->set('gender', $input->gender);
					$users->set('qualification', $input->qualification);
					$users->set('date_of_birth', $input->date_of_birth);
					$users->set('language_of_teaching', $input->language_of_teaching);//
					$users->set('teaching_experience', $input->teaching_experience);//
					$users->set('fee', $input->fee);//
					$users->set('free_demo', $input->free_demo);//
					$users->set('institute', $input->institute);
					$users->set('subject', $input->subject);//
					$users->set('degree', $input->degree);//
					$users->set('level', $input->level);//
					$users->set('grade', $input->grade);//
					$users->set('volunteering', $input->volunteering);//
					$users->set('specialities', $input->specialities);//

					$users->save();

				$status = "success";
				$message = 'Your settings have been saved successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error has occured. Please try again.';
				}

			}

			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);
			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});

		$app->put("/provider/save_password", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ( $input->old_password && $input->new_password &&  $input->confirm_new_password && $input->new_password == $input->confirm_new_password  )
						{
							$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
							if(md5($input->old_password) == $users->password)
							{
								$users->set('password', md5($input->new_password));
								$users->save();

								$status = "success";
								$message = 'Password saved successfully.';
							}
							else
							{
								$status = "danger";
								$message = 'Your current password does not match the one in our database.';
							}
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again';
						}

    		}

			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});
//save payment methods


$app->put("/provider/save_payment_method", function($string) use ($app)
		{
		session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ( $input->email_paypal )
						{
							$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
							$users->set('email_paypal', $input->email_paypal);
							$users->save();

								$status = "success";
								$message = 'Paypal email method saved successfully.';
							}
							else

						{
							$status = "danger";
							$message = 'Some error occured. Please try again';
						}

    		}

			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});





//Get messages

$app->get("/provider/messages", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{

						$messages = Model::factory('Messages')->where_any_is(
							array(
               array('from_user_id'=> $_SESSION['userid']),
               array('to_user_id'=>$_SESSION['userid']),
								)
							)->find_many();
						foreach( $messages as $message ){
							$response[] = array(

             		'id'=>$message->id,
								 'to_user_id'=>$message->to_user_id,
								 'from_user_id'=>$message->from_user_id,
								 'content'=>$message->content,
								 'datetime'=>$message->datetime,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create message
		$app->post("/provider/message/create", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content )
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = '0';
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Message created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Reply message
		$app->post("/provider/message/reply", function() use ($app)
		{
				session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content )
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = $input->to_user_id;
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Reply created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit message
			$app->put("/provider/message/edit/:id", function($id) use ($app)
			{
					session_start();
				$_SESSION['userid'] = 36;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->content)
						{
							$messages = Model::factory('Messages')->where('id',$id)->where('to_user_id',$_SESSION['userid'])->find_one();
							$messages->set('content', $input->content);
							$messages->save();
							$status = "success";
							$message = 'Message edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete message

	$app->delete("/provider/message/delete/:id", function($id) use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ( $id )
				{
					$messages = Model::factory('Messages')->where('id',$id )->where('to_user_id',$_SESSION['userid'])->find_one();
					$messages->delete();

					$status = "success";
					$message = 'Message deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get reviews

$app->get("/provider/reviews", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$reviews = Model::factory('Reviews')->where('for_user_id',$_SESSION['userid'])->find_many();
						foreach( $reviews as $review ){
							$response[] = array(
             'id'=>$review->id,
							'by_user_id'=>$review->by_user_id,
							'for_user_id'=>$review->for_user_id,
							'content'=>$review->content,
							'datetime'=>$review->datetime,



						);
						}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create review
		$app->post("/provider/review/create", function() use ($app)
		{
			$_SESSION['userid'] = 36;
			session_start();
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->content && $input->for_user_id )
				{
					$reviews = Model::factory('Reviews')->create();
					$reviews->content = $input->content;
					$reviews->by_user_id = $_SESSION['userid'];
					$reviews->for_user_id = '0';
					$reviews->datetime = date('Y-m-d H:i:s');
					$reviews->save();

					$status = "success";
					$message = 'Review created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit review
			$app->put("/provider/review/edit/:id", function($id) use ($app)
			{
				$_SESSION['userid'] = 36;
				session_start();
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->content)
						{
							$reviews = Model::factory('Reviews')->where('id',$id)->where('user_id',$_SESSION['userid'])->find_one();
							$reviews->set('content', $input->content);
							$reviews->save();
							$status = "success";
							$message = 'Review edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete review

	$app->delete("/provider/review/delete/:id", function($id) use ($app)
		{

		session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ( $id )
				{
					$reviews = Model::factory('Reviews')->where('id',$id )->where('by_user_id',$_SESSION['userid'])->find_one();
					$reviews->delete();

					$status = "success";
					$message = 'Review deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get keys

$app->get("/provider/keys", function() use ($app)
		{

			session_start();
	$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$keys = Model::factory('Keys')->find_many();
							foreach( $keys as $key ){
							$response[] = array(
             'id'=>$key->id,
							'device'=>$key->device,
							'key'=>$key->key,
							'description'=>$key->description,
								'approved'=>$key->approved,
						);
							}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create key
		$app->post("/provider/key/create", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->device && $input->key )
				{
					$keys = Model::factory('Keys')->create();
					$keys->device = $input->device;
					$keys->key = $input->key;
					$keys->description = $input->description;
					$keys->save();

					$status = "success";
					$message = 'Key created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit key
			$app->put("/provider/key/edit/:id", function($id) use ($app)
			{

				session_start();
				$_SESSION['userid'] = 36;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->device && $input->key)
						{
							$keys = Model::factory('Keys')->where('id',$id)->find_one();
							$keys->set('device', $input->device);
							$keys->set('key', $input->key);
							$keys->set('description', $input->description);
							$keys->save();
							$status = "success";
							$message = 'Key edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete key

	$app->delete("/provider/key/delete/:id", function($id) use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ( $id )
				{
					$keys = Model::factory('Keys')->where('id',$id )->find_one();
					$keys->delete();

					$status = "success";
					$message = 'Key deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get API docs
$app->get("/provider/api_docs", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$api_docs = Model::factory('Api_docs')->find_many();
							foreach( $api_docs as $api_doc ){
							$response[] = array(
             'id'=>$api_doc->id,
								 'title'=>$api_doc->title,
								 'content'=>$api_doc->content,
						);
							}


					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get payments

$app->get("/provider/payments", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$payments = Model::factory('Payments')->where('user_id',$_SESSION['userid'])->find_many();
								foreach( $payments as $payment ){
							$response[] = array(
             'id'=>$payment->id,
								 'user_id'=>$payment->user_id,
								 'invoice_id'=>$payment->invoice_id,
								 'name'=>$payment->name,
								 'email'=>$payment->email,
								 'address'=>$payment->address,
								 'city'=>$payment->city,
								 'state'=>$payment->state,
								 'zip'=>$payment->zip,
								 'country'=>$payment->country,
								 'amount'=>$payment->amount,
								 'description'=>$payment->description,
								 'type'=>$payment->type,
								 'cc_name'=>$payment->cc_name,
								 'cc_last_4'=>$payment->cc_last_4,
								 'stripe_transaction_id'=>$payment->stripe_transaction_id,
								 'paypal_transaction_id'=>$payment->paypal_transaction_id,
								 'date_created'=>$payment->date_created,




						); }


					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Make payment
$app->post("/provider/payment/create", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->token )
				{
					// make sure we hve hte payment token first
				if ( !$input->token ) {
					throw new Exception('Payment could not be completed, please try again.');
				}

				// build customer data
				$name = $input->name;
				$name_arr = explode(' ', trim($name));
				$first_name = $name_arr[0];
				$last_name = trim(str_replace($first_name, '', $name));
				$email = $input->email;
				$description = $input->description ? $input->description : 'no description entered';
				$address = $input->address;
				$city = $input->city;
				$state = $input->state;
				$zip = $input->zip;
				$country = $input->country;

		if ( $input->amount ) {
					$amount = $input->amount;
					$type = 'input';
				// return error if amount not found
				} else {
					throw new Exception('No amount was specified.');
				}



					// do the payment now
					$transaction = Stripe_Charge::create(array(
					  'amount' => $amount * 100,
					  'currency' => $config['currency'],
					  'card' => post('token'),
					  'description' => isset($item) ? $item->name : $description
					));

					// save payment record
					$payment = Model::factory('Payments')->create();
					$payment->user_id = $_SESSION['userid'];
					$payment->invoice_id = isset($invoice) ? $invoice->id : null;
					$payment->name = $name;
					$payment->email = $email;
					$payment->amount = $transaction->amount / 100;
					$payment->description = isset($item) ? $item->name : $description;
					$payment->address = $address;
					$payment->city = $city;
					$payment->state = $state;
					$payment->zip = $zip;
					$payment->country = $country;
					$payment->type = $type;
					$payment->cc_name = $transaction->source->name;
					$payment->cc_last_4 = $transaction->source->last4;
					$payment->stripe_transaction_id = $transaction->id;
					$payment->save();


					//save credits in users table
					$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
					$total_credits = $users->credits + $transaction->amount / 100;
					$users->set('credits', $total_credits);
					$users->save();

					// set the message
					$message = 'Your payment has been completed successfully, you should receive a confirmation email shortly.';





				// build email values first
				$values = array(
					'customer_name' => $name,
					'customer_email' => $email,
					'amount' => currency($amount) . '<small>' . currencySuffix() . '</small>' . $trial,
					'description_title' => isset($item) ? 'Item' : 'Description',
					'description' => isset($item) ? $item->name : $description,
					'payment_method' => 'Credit Card' . (isset($transaction) ? ': XXXX-' . $transaction->source->last4 : ''),
					'transaction_id' => isset($transaction) ? $transaction->id : null,
					'subscription_id' => isset($subscription) ? $subscription->stripe_subscription_id : '',
					'manage_url' => isset($unique_subscription_id) ? url('manage.php?subscription_id=' . $unique_subscription_id) : '',
					'url' => url(''),
				);

					email($config['email'], 'payment-confirmation-admin', $values, 'You\'ve received a new payment!');
					email($email, 'payment-confirmation-customer', $values, 'Thank you for your payment to ' . $config['name']);




				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->post("/provider/payment/paypal_ipn", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);

			try {


		    	// die if it's a refund notification
		    	if ( preg_match('/refund/', $input->reason_code) ) {
		    		die();
		    	}

		    	// parse our custom field data
				$custom = $input->custom;
				if ( $custom ) {
					parse_str($input->custom, $data);
				} else {
					$data = array();
				}
				// pull out some values
				$payment_gross = $input->payment_gross;
				$item_name = $input->item_name;

				// build customer data
				$name = isset($data['name']) && $data['name'] ? $data['name'] : null;
				$name_arr = explode(' ', trim($name));
				$first_name = $name_arr[0];
				$last_name = trim(str_replace($first_name, '', $name));
				$email = isset($data['email']) && $data['email'] ? $data['email'] : null;
				$description = $item_name ? $item_name : 'no description entered';
				$address = isset($data['address']) && $data['address'] ? $data['address'] : null;
				$city = isset($data['city']) && $data['city'] ? $data['city'] : null;
				$state = isset($data['state']) && $data['state'] ? $data['state'] : null;
				$zip = isset($data['zip']) && $data['zip'] ? $data['zip'] : null;
				$country = isset($data['country']) && $data['country'] ? $data['country'] : null;

				if ( $payment_gross ) {
					$amount = $payment_gross;
					$type = 'input';
				// return error if none found
				} else {
					$amount = 0;
					$type = '';
				}

				switch ( $input->txn_type ) {
					case 'web_accept':

						// save payment record
						$payment = Model::factory('Payment')->create();
						$payment->user_id = $_SESSION['userid'];
						$payment->invoice_id = isset($invoice) ? $invoice->id : null;
						$payment->name = $name;
						$payment->email = $email;
						$payment->amount = $amount;
						$payment->description = isset($item) ? $item->name : $description;
						$payment->address = $address;
						$payment->city = $city;
						$payment->state = $state;
						$payment->zip = $zip;
						$payment->country = $country;
						$payment->type = $type;
						$payment->paypal_transaction_id = $input->txn_id;
						$payment->save();

						//save credits in users table
						$users = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();
						$total_credits = $users->credits + $amount;
						$users->set('credits', $total_credits);
						$users->save();


						// build email values first
						$values = array(
							'customer_name' => $payment->name,
							'customer_email' => $payment->email,
							'amount' => currency($payment->amount) . '<small>' . currencySuffix() . '</small>',
							'description_title' => isset($item) ? 'Item' : 'Description',
							'description' => $payment->description,
							'transaction_id' => $input->txn_id,
							'payment_method' => 'PayPal',
							'url' => url(''),
						);
						email($config['email'], 'payment-confirmation-admin', $values, 'You\'ve received a new payment!');


						email($payment->email, 'payment-confirmation-customer', $values, 'Thank you for your payment to ' . $config['name']);

					break;

				}


			}

			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


// Delete payment


$app->delete("/provider/payment/delete/:id", function($id) use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ( $id )
				{
					$payments = Model::factory('Payments')->where('id',$id )->where('user_id',$_SESSION['userid'])->find_one();
					$payments->delete();

					$status = "success";
					$message = 'Payment deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get requests

$app->get("/provider/requests", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ($_SESSION['userid'])
					{
						$requests = Model::factory('Requests')->where('provided_to_user_id',$_SESSION['userid'])->find_many();
						foreach( $requests as $request ){
								$response[] = array(
             		'id'=>$request->id,
								'start'=>$request->start,
								'end'=>$request->end,
								'price'=>$request->price,
								'paid'=>$request->paid,
								'accepted'=>$request->accepted,
								'reviewed_by_provider'=>$request->reviewed_by_provider,
								'reviewed_by_client'=>$request->reviewed_by_client,
								'created_by_user_id'=>$request->created_by_user_id,
								'provided_to_user_id'=>$request->provided_to_user_id,
						);
						}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create request
		$app->post("/provider/request/create", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->latitude && $input->longitude )
				{
					$requests = Model::factory('Requests')->create();
					$requests->user_id = $_SESSION['userid'];
					$requests->latitude = $input->latitude;
					$requests->longitude = $input->longitude;
					$requests->save();

					$status = "success";
					$message = 'Request created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




//Delete request

	$app->delete("/provider/request/delete/:id", function($id) use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ( $id )
				{
					$requests = Model::factory('Requests')->where('id',$id )->find_one();
					$requests->delete();

					$status = "success";
					$message = 'Request deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Request messageuser
		$app->post("/provider/request/messageuser", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content && $input->to_user_id)
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = $input->to_user_id;
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Message created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Request reviewuser
		$app->post("/provider/request/reviewuser", function() use ($app)
		{

		session_start();
				$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content && $input->for_user_id && $input->request_id )
				{
					$messages = Model::factory('Reviews')->create();
					$messages->for_user_id = $input->for_user_id;
					$messages->by_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$request = Model::factory('Requests')->where('id',$input->request_id)->find_one();
					$request->set('reviewed_by_provider', 'yes');
					$request->save();

					$status = "success";
					$message = 'Review created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Get blogs
$app->get("/provider/blogs", function() use ($app)
		{

			session_start();
		$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$blogs = Model::factory('Blogs')->find_many();
								foreach( $blogs as $blog ){
							$response[] = array(
             'id'=>$blog->id,
									'title'=>$blog->title,
									'content'=>$blog->content,


						); }

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Get pages
$app->get("/provider/pages", function() use ($app)
		{

			session_start();
		$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{
						$pages = Model::factory('Pages')->find_many();
							foreach( $pages as $page ){
							$response[] = array(
             'id'=>$page->id,
									'title'=>$page->title,
									'content'=>$page->content,


						); }

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Get username by id


$app->get("/provider/username_by_id/:id", function($id) use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
      $app->response()->header("Content-Type", "application/json");
			echo get_username_id_db($id);

 		});



//Get availability

$app->get("/provider/availability", function() use ($app)
		{
			session_start();
		$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{

						$availabilities = Model::factory('Availability')->where('user_id',$_SESSION['userid'])->find_many();
						foreach( $availabilities as $availability ){
							$response[] = array(

             		'id'=>$availability->id,
								 'user_id'=>$availability->user_id,
								 'start'=>$availability->start,
								 'end'=>$availability->end,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

// add availability

$app->post("/provider/availability/create", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if (isset($_SESSION['userid']) && $input->start && $input->end )
					{

							$availability = Model::factory('Availability')->create();
							$availability->user_id = $_SESSION['userid'];
							$availability->start = $input->start;
	  					$availability->end = $input->end;
							$availability->save();
							$status = 'success';
							$message = 'Availability stored successfully.';

					}

				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




// delete availability

$app->delete("/provider/availability/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ($_SESSION['userid'])
					{

							$availability = Model::factory('Availability')->where('id',$id)->where('user_id',$_SESSION['userid'])->find_one();
							$availability->delete();
							$status = 'success';
							$message = 'Availability deleted successfully.';

					}

				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




//Get users

$app->get("/provider/users", function() use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if (1==1)
					{

						$users = Model::factory('Users')->find_many();
						foreach( $users as $user ){
							$response[] = array(

             		'id'=>$user->id,
								'username'=>$user->username,
								'email'=>$user->email,
								'type'=>$user->type,
								'credits'=>$user->credits,
								'profile_picture'=>$user->profile_picture,
								'approved' => $user->approved,

						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Users message
		$app->post("/provider/users/message", function() use ($app)
		{

		session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($input->content )
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = $input->to_user_id;
					$messages->from_user_id = $_SESSION['userid'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Message sent successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});
//Get reviews by user id

$app->get("/provider/reviews_by_user_id/:user_id", function($user_id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if (isset($_SESSION['userid']))
					{

						$availabilities = Model::factory('Reviews')->where('for_user_id',$user_id)->find_many();
						foreach( $availabilities as $availability ){
							$response[] = array(

             		'id'=>$availability->id,
								 'by_user_id'=>$availability->by_user_id,
								'for_user_id'=>$availability->for_user_id,
								 'content'=>$availability->content,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Paypal release payment
$app->get("/provider/payment/release", function($id) use ($app)
		{
			session_start();
			$_SESSION['userid'] = 36;
			try
			 {
				if ($_SESSION['userid'])
				{

					function MassPay()
						{
							$config_paypal_username = Model::factory('Config')->where('key','paypal_username')->find_one();
  						$ppl_username = $config_paypal_username->value;

							$config_paypal_password = Model::factory('Config')->where('key','paypal_password')->find_one();
  						$ppl_password = $config_paypal_password->value;

							$config_paypal_signature = Model::factory('Config')->where('key','paypal_signature')->find_one();
  						$ppl_signature = $config_paypal_signature->value;

							$config_paypal_email_subject = Model::factory('Config')->where('key','paypal_email_subject')->find_one();
  						$vEmailSubject = $config_paypal_email_subject->value;

							$config_paypal_environment = Model::factory('Config')->where('key','paypal_environment')->find_one();
 						 	$enviornment = $config_paypal_environment->value;

							function PPHttpPost($methodName_, $nvpStr_)
							{
 								global $environment;

 								// Set up your API credentials, PayPal end point, and API version.
 								// How to obtain API credentials:
								// https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO

 								$API_Endpoint = "https://api-3t.paypal.com/nvp";
 								if("sandbox" === $environment || "beta-sandbox" === $environment)
 								{
  								$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
 								}
 								$version = urlencode('51.0');

 								// Set the curl parameters.
 								$ch = curl_init();
 								curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
 								curl_setopt($ch, CURLOPT_VERBOSE, 1);

 								// Turn off the server and peer verification (TrustManager Concept).
 								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

 								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_POST, 1);

 								// Set the API operation, version, and API signature in the request.
 								$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

 								// Set the request as a POST FIELD for curl.
 								curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

 								// Get response from the server.
 								$httpResponse = curl_exec($ch);

 								if( !$httpResponse)
 								{
  								exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) .')');
 								}

								// Extract the response details.
 								$httpResponseAr = explode("&", $httpResponse);

 								$httpParsedResponseAr = array();
 								foreach ($httpResponseAr as $i => $value)
 								{
 								 $tmpAr = explode("=", $value);
  							if(sizeof($tmpAr) > 1)
 								 {
   								$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
  								}
 								}

 								if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
 								{
 								 exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
 								}

 							return $httpParsedResponseAr;
						}

						// Set request-specific fields.
						$emailSubject = urlencode($vEmailSubject);
						$receiverType = urlencode('EmailAddress');
						$currency = urlencode('GBP'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

						// Receivers
						// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
						// Here you can modify to obtain array data from database.

						$user = Model::factory('Users')->where('id',$_SESSION['userid'])->find_one();

						$receivers = array(
						  0 => array(
						    'receiverEmail' => $user->email_paypal,
						    'amount' => $user->credits,
						    'uniqueID' => "id_001", // 13 chars max
						    'note' => " Payment release"), // I recommend use of space at beginning of string.

						);

								$receiversLenght = count($receivers);

								// Add request-specific fields to the request string.
								$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

								$receiversArray = array();

								for($i = 0; $i < $receiversLenght; $i++)
								{
								 $receiversArray[$i] = $receivers[$i];
								}

								foreach($receiversArray as $i => $receiverData)
								{
								 $receiverEmail = urlencode($receiverData['receiverEmail']);
								 $amount = urlencode($receiverData['amount']);
								 $uniqueID = urlencode($receiverData['uniqueID']);
								 $note = urlencode($receiverData['note']);
								 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
								}

								// Execute the API operation; see the PPHttpPost function above.
								$httpParsedResponseAr = PPHttpPost('MassPay', $nvpStr);

								if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
									{
									 	exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
									  $status = 'success';
									  $message = 'Payment released successfully.';

									}
								else
									{
								 		exit('MassPay failed: ' . print_r($httpParsedResponseAr, true));
										$status = 'danger';
									  $message = 'Payment release failed.';
									}
								}

						}
				else
				{
					$status = "danger";
					$message = 'Payment release failed.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Request accept
		$app->put("/provider/request/accept", function() use ($app)
		{

			session_start();
			$_SESSION['userid'] = 36;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->request_id && $_SESSION['userid'] )
				{
					$request = Model::factory('Requests')->where('id',$input->request_id)->where('provided_to_user_id',$_SESSION['userid'])->find_one();
					$request->set('accepted','yes');
					$request->save();

					$status = "success";
					$message = 'Request accepted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//---------------------------ADMIN---------------------------------

$app->get("/admin/product_data", function() use ($app)
		{
			try
			 {
						$product_datas = Model::factory('Config')->find_many();
						$response = array();
	 					foreach( $product_datas as $product_data )
						{

             		$response[$product_data->key] = $product_data->value;


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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


$app->post("/admin/login", function() use ($app)
		{
			 $input = $app->request()->getBody();
		   $input = json_decode($input);
			try
			{

				if ($input->username && $input->password)
					{
					$username = Model::factory('Config')->where("key","name")->where("value",$input->username)->find_one();
					$password = Model::factory('Config')->where("key","password")->where("value",md5($input->password))->find_one();

					if($username && $password)
					{
						session_start();
						$_SESSION['admin'] = 1;
						$status = 'success';
						$message = 'Logged in successfully.';
					}
					else
					{
						$status = 'danger';
						$message = 'Could not log you in. Please try again.';
					}




					}
				else
						{
							$status = 'danger';
							$message = 'Could not log you in. Please try again.';
						}

			}
			catch (Exception $e)
					{
						$status = 'danger';
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message,
			);
			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});

$app->post("/admin/logout",function() use ($app)
		{
	session_start();

			try {
						unset($_SESSION['admin']);
						session_destroy();
						$status = 'success';
						$message = 'You have been logged out successfully';
					}

			catch (Exception $e)
					{
						$status = 'danger';
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});

$app->get("/admin/admin_data", function() use ($app)
		{

		session_start();
		$_SESSION['admin'] = 1;
			try
			 {
				if ($_SESSION['admin'])
					{
						$admin = Model::factory('Config')->where('key','name')->find_one();
						$response = array(
             'name'=> $admin->value,

							);

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

	$app->get("/admin/user_data", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{
						$users = Model::factory('Users')->where('id',$_SESSION['id'])->find_one();
						$response = array(
             'id'=>$users->id,
							'username'=>$users->username,
							'email'=>$users->email,
							'password'=>$users->password,
							'type'=>$users->type,
							'credits'=>$users->credits,
							'profile_picture'=>$users->profile_picture,
							'credits'=>$users->credits,
							'first_name'=>$users->first_name,
							'last_name'=>$users->last_name,
							'gender'=>$users->gender,
							'qualification'=>$users->qualification,
							'date_of_birth'=>$users->date_of_birth,
							'language_of_teaching'=>$users->language_of_teaching,
							'teaching_experience'=>$users->teaching_experience,
							'fee'=>$users->fee,
							'free_demo'=>$users->free_demo,
							'institute'=>$users->institute,
							'subject'=>$users->subject,
							'degree'=>$users->degree,
							'level'=>$users->level,
							'grade'=>$users->grade,
							'volunteering'=>$users->volunteering,
							'specialities'=>$users->specialities,



						);

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



		$app->put("/admin/save_site_settings", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ($input)
				{
					//your details
					if($input->name)
					{
					$name = Model::factory('Config')->where('key', 'name')->find_one();
					$name->set('value',$input->name);
					$name->save();
					}
					if($input->email)
					{
					$email = Model::factory('Config')->where('key', 'email')->find_one();
					$email->set('value',$input->email);
					$email->save();
					}
					if($input->email_paypal)
					{

					$email_paypal = Model::factory('Config')->where('key', 'email_paypal')->find_one();
					$email_paypal->set('value',$input->email_paypal);
					$email_paypal->save();
					}
					if($input->page_title)
					{
					$page_title = Model::factory('Config')->where('key', 'page_title')->find_one();
					$page_title->set('value',$input->page_title);
					$page_title->save();
					}
					if($input->logo)
					{
					$logo = Model::factory('Config')->where('key', 'logo')->find_one();
					$logo->set('value',$input->logo);
					$logo->save();
					}
					if($input->favicon)
					{
					$favicon = Model::factory('Config')->where('key', 'favicon')->find_one();
					$favicon->set('value',$input->favicon);
					$favicon->save();
					}
					if($input->http_redirect)
					{
					$https_redirect = Model::factory('Config')->where('key', 'https_redirect')->find_one();
					$https_redirect->set('value',$input->https_redirect);
					$https_redirect->save();
					}
					if($input->payment_type)
					{
					$payment_type = Model::factory('Config')->where('key', 'payment_type')->find_one();
					$payment_type->set('value',$input->payment_type);
					$payment_type->save();
					}
					if($input->show_description)
					{
					$show_description = Model::factory('Config')->where('key', 'show_description')->find_one();
					$show_description->set('value',$input->show_description);
					$show_description->save();
					}
					if($input->how_billing_address)
					{
					$show_billing_address = Model::factory('Config')->where('key', 'show_billing_address')->find_one();
					$show_billing_address->set('value',$input->show_billing_address);
					$show_billing_address->save();
					}
					if($input->commission_percentage)
					{
					$commission_percentage = Model::factory('Config')->where('key', 'commission_percentage')->find_one();
					$commission_percentage->set('value',$input->commission_percentage);
					$commission_percentage->save();
					}
					if($input->enable_subscriptions)
					{
					$enable_subscriptions = Model::factory('Config')->where('key', 'enable_subscriptions')->find_one();
					$enable_subscriptions->set('value',$input->enable_subscriptions);
					$enable_subscriptions->save();
					}
					if($input->subscription_interval)
					{
					$subscription_interval = Model::factory('Config')->where('key', 'subscription_interval')->find_one();
					$subscription_interval->set('value',$input->subscription_interval);
					$subscription_interval->save();
					}
					if($input->subscription_length)
					{
					$subscription_length = Model::factory('Config')->where('key', 'subscription_length')->find_one();
					$subscription_length->set('value',$input->subscription_length);
					$subscription_length->save();
					}
					if($input->enable_trial)
					{
					$enable_trial = Model::factory('Config')->where('key', 'enable_trial')->find_one();
					$enable_trial->set('value',$input->enable_trial);
					$enable_trial->save();
					}
					if($input->trial_days)
					{
					$trial_days = Model::factory('Config')->where('key', 'trial_days')->find_one();
					$trial_days->set('value',$input->trial_days);
					$trial_days->save();
					}
					if($input->stripe_secret_key)
					{
					$stripe_secret_key = Model::factory('Config')->where('key', 'stripe_secret_key')->find_one();
					$stripe_secret_key->set('value',$input->stripe_secret_key);
					$stripe_secret_key->save();
					}
					if($input->stripe_publishable_key)
					{
					$stripe_publishable_key = Model::factory('Config')->where('key', 'stripe_publishable_key')->find_one();
					$stripe_publishable_key->set('value',$input->stripe_publishable_key);
					$stripe_publishable_key->save();
					}
					if($input->enable_paypal)
					{
					$enable_paypal = Model::factory('Config')->where('key', 'enable_paypal')->find_one();
					$enable_paypal->set('value',$input->enable_paypal);
					$enable_paypal->save();
					}
					if($input->paypal_environment)
					{
					$paypal_environment = Model::factory('Config')->where('key', 'paypal_environment')->find_one();
					$paypal_environment->set('value',$input->paypal_environment);
					$paypal_environment->save();
					}
					if($input->paypal_email)
					{
					$paypal_email = Model::factory('Config')->where('key', 'paypal_email')->find_one();
					$paypal_email->set('value',$input->paypal_email);
					$paypal_email->save();
					}
					if($input->paypal_username)
					{
					$paypal_username = Model::factory('Config')->where('key', 'paypal_username')->find_one();
					$paypal_username->set('value',$input->paypal_username);
					$paypal_username->save();
					}
					if($input->paypal_password)
					{
					$paypal_password = Model::factory('Config')->where('key', 'paypal_password')->find_one();
					$paypal_password->set('value',$input->paypal_password);
					$paypal_password->save();
					}
					if($input->paypal_signature)
					{
					$paypal_signature = Model::factory('Config')->where('key', 'paypal_signature')->find_one();
					$paypal_signature->set('value',$input->paypal_signature);
					$paypal_signature->save();
					}
					if($input->paypal_email_subject)
					{
					$paypal_paypal_email_subject = Model::factory('Config')->where('key', 'paypal_email_subject')->find_one();
					$paypal_paypal_email_subject->set('value',$input->paypal_email_subject);
					$paypal_paypal_email_subject->save();
					}
					if($input->twilio_sid)
					{
					$twilio_sid = Model::factory('Config')->where('key', 'twilio_sid')->find_one();
					$twilio_sid->set('value',$input->twilio_sid);
					$twilio_sid->save();
					}
					if($input->twilio_token)
					{
					$twilio_token = Model::factory('Config')->where('key', 'twilio_token')->find_one();
					$twilio_token->set('value',$input->twilio_token);
					$twilio_token->save();
					}
					if($input->facebook)
					{
					$facebook = Model::factory('Config')->where('key', 'facebook')->find_one();
					$facebook->set('value',$input->facebook);
					$facebook->save();
					}
					if($input->twitter)
					{
					$twitter = Model::factory('Config')->where('key', 'twitter')->find_one();
					$twitter->set('value',$input->twitter);
					$twitter->save();
					}
					if($input->google)
					{
					$google = Model::factory('Config')->where('key', 'google')->find_one();
					$google->set('value',$input->google);
					$google->save();
					}
					if($input->linkedin)
					{
					$linkedin = Model::factory('Config')->where('key', 'linkedin')->find_one();
					$linkedin->set('value',$input->linkedin);
					$linkedin->save();
					}
					if($input->site_description)
					{
					$site_description = Model::factory('Config')->where('key', 'site_description')->find_one();
					$site_description->set('value',$input->site_description);
					$site_description->save();
					}
					if($input->site_keywords)
					{
					$site_keywords = Model::factory('Config')->where('key', 'site_keywords')->find_one();
					$site_keywords->set('value',$input->site_keywords);
					$site_keywords->save();
					}
					if($input->site_analytics)
					{
					$site_analytics = Model::factory('Config')->where('key', 'site_analytics')->find_one();
					$site_analytics->set('value',$input->site_analytics);
					$site_analytics->save();
					}
					if($input->site_homepage_content)
					{
					$site_homepage_content = Model::factory('Config')->where('key', 'site_homepage_content')->find_one();
					$site_homepage_content->set('value',$input->site_homepage_content);
					$site_homepage_content->save();
					}
					if($input->site_address)
					{
					$site_address = Model::factory('Config')->where('key', 'site_address')->find_one();
					$site_address->set('value',$input->site_address);
					$site_address->save();
					}
					if($input->site_phone_number)
					{
					$site_phone_number = Model::factory('Config')->where('key', 'site_phone_number')->find_one();
					$site_phone_number->set('value',$input->site_phone_number);
					$site_phone_number->save();
					}
					if($input->ios_app_url)
					{
					$ios_app_url = Model::factory('Config')->where('key', 'ios_app_url')->find_one();
					$ios_app_url->set('value',$input->ios_app_url);
					$ios_app_url->save();
					}
					if($input->windows_app_url)
					{
					$windows_app_url = Model::factory('Config')->where('key', 'windows_app_url')->find_one();
					$windows_app_url->set('value',$input->windows_app_url);
					$windows_app_url->save();
					}
					if($input->android_app_url)
					{
					$android_app_url = Model::factory('Config')->where('key', 'android_app_url')->find_one();
					$android_app_url->set('value',$input->android_app_url);
					$android_app_url->save();
					}


				$status = "success";
				$message = 'Site settings have been saved successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error has occured. Please try again.';
				}

			}

			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);
			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});


		$app->put("/admin/save_password", function($string) use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		  $input = json_decode($input);
			try {
				if ($_SESSION['admin'])

						{

							if(!$input->old_password || !$input->new_password || !$input->confirm_new_password || $input->new_password != $input->confirm_new_password)
							{
								throw new Exception("Incorrect values supplied");
							}
							$password = Model::factory('Config')->where('key','password')->find_one();
							if(md5($input->old_password) == $users->password)
							{
								$password->set('value', md5($input->new_password));
								$password->save();

								$status = "success";
								$message = 'Password saved successfully.';
							}
							else
							{
								$status = "danger";
								$message = 'Your current password does not match the one in our database.';
							}
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again';
						}

    		}

			catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

		});


//Get users

$app->get("/admin/users", function() use ($app)
		{


			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{

						$users = Model::factory('Users')->find_many();
						foreach( $users as $user ){
							$response[] = array(

             		'id'=>$user->id,
								'username'=>$user->username,
								'email'=>$user->email,
								'type'=>$user->type,
								'credits'=>$user->credits,
								'profile_picture'=>$user->profile_picture,
								'approved' => $user->approved,
								'first_name'=>$user->first_name,
							'last_name'=>$user->last_name,
							'phone_number'=>$user->phone_number,
							'gender'=>$user->gender,
							'qualification'=>$user->qualification,
							'date_of_birth'=>$user->date_of_birth,
							'language_of_teaching'=>$user->language_of_teaching,
							'teaching_experience'=>$user->teaching_experience,
							'fee'=>$user->fee,
							'free_demo'=>$user->free_demo,
							'institute'=>$user->institute,
							'subject'=>$user->subject,
							'degree'=>$user->degree,
							'level'=>$user->level,
							'grade'=>$user->grade,
							'volunteering'=>$user->volunteering,
							'specialities'=>$user->specialities,

						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->get("/admin/providers", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{

						$users = Model::factory('Users')->where('type','provider')->find_many();
						foreach( $users as $user ){
							$response[] = array(

             		'id'=>$user->id,
								'username'=>$user->username,
								'email'=>$user->email,
								'type'=>$user->type,
								'credits'=>$user->credits,
								'profile_picture'=>$user->profile_picture,
								'approved' => $user->approved,
								'first_name'=>$user->first_name,
							'last_name'=>$user->last_name,
							'gender'=>$user->gender,
							'qualification'=>$user->qualification,
							'date_of_birth'=>$user->date_of_birth,
							'language_of_teaching'=>$user->language_of_teaching,
							'teaching_experience'=>$user->teaching_experience,
							'fee'=>$user->fee,
							'free_demo'=>$user->free_demo,
							'institute'=>$user->institute,
							'subject'=>$user->subject,
							'degree'=>$user->degree,
							'level'=>$user->level,
							'grade'=>$user->grade,
							'volunteering'=>$user->volunteering,
							'specialities'=>$user->specialities,

						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create user
		$app->post("/admin/user/create", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($_SESSION['admin'] )
				{
					$users = Model::factory('Users')->create();
					$users->username = $input->username;
					$users->email = $input->email;
					$users->password = md5($input->password);
					$users->type = $input->type;
					$users->save();

					$status = "success";
					$message = 'User created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit user
			$app->put("/admin/user/edit/:id", function($id) use ($app)
			{

				session_start();
				$_SESSION['admin'] = 1;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($_SESSION['admin'])
						{
							$users = Model::factory('Users')->where('id',$id)->find_one();
							$users->set('username', $input->username);
							$users->set('email', $input->email);
							$users->set('password', md5($input->password));
							$users->set('type', $input->type);
							$users->set('first_name', $input->first_name);
							$users->set('last_name', $input->last_name);
							$users->set('phone_number', $input->phone_number);
							$users->set('gender', $input->gender);
							$users->set('qualification', $input->qualification);
							$users->set('date_of_birth', $input->date_of_birth);
							$users->set('language_of_teaching', $input->type);
							$users->set('fee', $input->fee);
							$users->set('free_demo', $input->free_demo);
							$users->set('institute', $input->institute);
							$users->set('subject', $input->subject);
							$users->set('degree', $input->degree);
							$users->set('level', $input->level);
							$users->set('grade', $input->grade);
							$users->set('volunteering', $input->volunteering);
							$users->set('specialities', $input->specialities);
							$users->save();
							$status = "success";
							$message = 'User edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete user

	$app->delete("/admin/user/delete/:id", function($id) use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if ( $id && $_SESSION['admin'] )
				{
					$users = Model::factory('Users')->where('id',$id )->find_one();
					$users->delete();

					$status = "success";
					$message = 'User deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Approve user

	$app->put("/admin/user/approve/:id", function($id) use ($app)
		{


			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if ( $id && $_SESSION['admin'] )
				{
					$users = Model::factory('Users')->where('id',$id )->find_one();
					$users->set('approved','yes');
					$users->save();


					$status = "success";
					$message = 'User approved successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




//Get messages

$app->get("/admin/messages", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{

						$messages = Model::factory('Messages')->find_many();
						foreach( $messages as $message ){
							$response[] = array(

             		'id'=>$messages->id,
								 'to_user_id'=>$message->to_user_id,
								 'from_user_id'=>$message->from_user_id,
								 'content'=>$message->content,
								 'datetime'=>$message->datetime,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create message
		$app->post("/admin/message/create", function() use ($app)
		{

		session_start();
			$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ($_SESSION['admin'])
				{
					$messages = Model::factory('Messages')->create();
					$messages->to_user_id = '0';
					$messages->from_user_id = $_SESSION['id'];
					$messages->content = $input->content;
					$messages->datetime = date('Y-m-d H:i:s');
					$messages->save();

					$status = "success";
					$message = 'Message created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit message
			$app->put("/admin/message/edit/:id", function($id) use ($app)
			{
				session_start();
				$_SESSION['admin'] = 1;

				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($_SESSION['admin'])
						{
							$messages = Model::factory('Messages')->where('id',$id)->find_one();
							$messages->set('content', $input->content);
							$messages->save();
							$status = "success";
							$message = 'Message edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete message

	$app->delete("/admin/message/delete/:id", function($id) use ($app)
		{

			session_start();
		$_SESSION['admin'] = 1;
			try
			 {
				if ( $id && $_SESSION['admin'] )
				{
					$messages = Model::factory('Messages')->where('id',$id )->find_one();
					$messages->delete();

					$status = "success";
					$message = 'Message deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Get reviews

$app->get("/admin/reviews", function() use ($app)
		{

		session_start();
	$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{
						$reviews = Model::factory('Reviews')->find_many();
						foreach( $reviews as $review ){
							$response[] = array(
             'id'=>$review->id,
							'by_user_id'=>$review->by_user_id,
							'for_user_id'=>$review->for_user_id,
							'content'=>$review->content,
							'datetime'=>$review->datetime,



						);
						}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create review
		$app->post("/admin/review/create", function() use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $_SESSION['admin'])
				{
					$reviews = Model::factory('Reviews')->create();
					$reviews->content = $input->content;
					$reviews->by_user_id = $_SESSION['id'];
					$reviews->for_user_id = '0';
					$reviews->datetime = date('Y-m-d H:i:s');
					$reviews->save();

					$status = "success";
					$message = 'Review created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit review
			$app->put("/admin/review/edit/:id", function($id) use ($app)
			{

				session_start();
				$_SESSION['admin'] = 1;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($_SESSION['admin'])
						{
							$reviews = Model::factory('Reviews')->where('id',$id)->find_one();
							$reviews->set('content', $input->content);
							$reviews->save();
							$status = "success";
							$message = 'Review edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete review

	$app->delete("/admin/review/delete/:id", function($id) use ($app)
		{
		session_start();
		$_SESSION['admin'] = 1;
			try
			 {
				if ( $id && $_SESSION['admin'])
				{
					$reviews = Model::factory('Reviews')->where('id',$id )->where('by_user_id',$_SESSION['id'])->find_one();
					$reviews->delete();

					$status = "success";
					$message = 'Review deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get keys

$app->get("/admin/keys", function() use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			try
			 {
				if (isset($_SESSION['admin']))
					{
						$keys = Model::factory('Keys')->find_many();
							foreach( $keys as $key ){
							$response[] = array(
             'id'=>$key->id,
							'device'=>$key->device,
							'key'=>$key->key,
								'description'=>$key->description,
								'approved'=>$key->approved,

						);
							}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create key
		$app->post("/admin/key/create", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->device && $input->key && $_SESSION['admin'] )
				{
					$keys = Model::factory('Keys')->create();
					$keys->device = $input->device;
					$keys->key = $input->key;
					$keys->description = $input->description;
					$keys->approved = 'yes';
					$keys->user_id= $_SESSION['admin'];
					$keys->save();

					$status = "success";
					$message = 'Key created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit key
			$app->put("/admin/key/edit/:id", function($id) use ($app)
			{
				session_start();
				$_SESSION['admin'] = 1;

				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->device && $input->key && $_SESSION['admin'])
						{
							$keys = Model::factory('Keys')->where('id',$id)->find_one();
							$keys->set('device', $input->device);
							$keys->set('key', $input->key);
							$keys->set('description', $input->description);
							$keys->save();
							$status = "success";
							$message = 'Key edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete key

	$app->delete("/admin/key/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			try
			 {
				if ( $id && $_SESSION['admin'] )
				{
					$keys = Model::factory('Keys')->where('id',$id )->find_one();
					$keys->delete();

					$status = "success";
					$message = 'Key deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get API docs
$app->get("/admin/api_docs", function() use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			try
			 {
				if (isset($_SESSION['admin']))
					{
						$api_docs = Model::factory('Api_docs')->find_many();
							foreach( $api_docs as $api_doc ){
							$response[] = array(
             'id'=>$api_doc->id,
								 'title'=>$api_doc->title,
								 'content'=>$api_doc->content,
						);
							}


					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});
//Create api doc
		$app->post("/admin/api_doc/create", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->title && $input->content && $_SESSION['admin'] )
				{
					$api_docs = Model::factory('Api_docs')->create();
					$api_docs->title = $input->title;
					$api_docs->content = $input->content;
					$api_docs->save();

					$status = "success";
					$message = 'API doc created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit api doc
			$app->put("/admin/api_doc/edit/:id", function($id) use ($app)
			{
				session_start();
				$_SESSION['admin'] = 1;

				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->title && $input->content && $_SESSION['admin'])
						{
							$api_docs = Model::factory('Api_docs')->where('id',$id)->find_one();
							$api_docs->set('title', $input->title);
							$api_docs->set('content', $input->content);
							$api_docs->save();
							$status = "success";
							$message = 'API doc edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete api doc

	$app->delete("/admin/api_doc/delete/:id", function($id) use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if ( $id && $_SESSION['admin'])
				{
					$api_docs = Model::factory('Api_docs')->where('id',$id )->find_one();
					$api_docs->delete();

					$status = "success";
					$message = 'API doc deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Get payments

$app->get("/admin/payments", function() use ($app)
		{


	session_start();
	$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{
						$payments = Model::factory('Payments')->find_many();
								foreach( $payments as $payment ){
							$response[] = array(
             'id'=>$payment->id,
								 'user_id'=>$payment->user_id,
								 'invoice_id'=>$payment->invoice_id,
								 'name'=>$payment->name,
								 'email'=>$payment->email,
								 'address'=>$payment->address,
								 'city'=>$payment->city,
								 'state'=>$payment->state,
								 'zip'=>$payment->zip,
								 'country'=>$payment->country,
								 'amount'=>$payment->amount,
								 'description'=>$payment->description,
								 'type'=>$payment->type,
								 'cc_name'=>$payment->cc_name,
								 'cc_last_4'=>$payment->cc_last_4,
								 'stripe_transaction_id'=>$payment->stripe_transaction_id,
								 'paypal_transaction_id'=>$payment->paypal_transaction_id,
								 'date_created'=>$payment->date_created,




						); }


					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




// Delete payment


$app->delete("/admin/payment/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			try
			 {
				if ( $id && $_SESSION['admin'] )
				{
					$payments = Model::factory('Payments')->where('id',$id )->find_one();
					$payments->delete();

					$status = "success";
					$message = 'Payment deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get requests

$app->get("/admin/requests", function() use ($app)
		{

			session_start();
$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{
						$requests = Model::factory('Requests')->find_many();
						foreach( $requests as $request ){
							$response[] = array(
             		'id'=>$request->id,
								'start'=>$request->start,
								'end'=>$request->end,
								'price'=>$request->price,
								'paid'=>$request->paid,
								'accepted'=>$request->accepted,
								'reviewed_by_provider'=>$request->reviewed_by_provider,
								'reviewed_by_client'=>$request->reviewed_by_client,
								'created_by_user_id'=>$request->created_by_user_id,
								'provided_to_user_id'=>$request->provided_to_user_id,
						);
						}

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Create request
		$app->post("/admin/request/create", function() use ($app)
		{

			session_start();
		$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->latitude && $input->longitude && $_SESSION['admin'] )
				{
					$requests = Model::factory('Requests')->create();
					$requests->user_id = $_SESSION['id'];
					$requests->latitude = $input->latitude;
					$requests->longitude = $input->longitude;
					$requests->save();

					$status = "success";
					$message = 'Request created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit request
			$app->put("/admin/request/edit/:id", function($id) use ($app)
			{
				session_start();
$_SESSION['admin'] = 1;

				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->latitude  && $input->longitude && $_SESSION['admin'])
						{
							$requests = Model::factory('Requests')->where('id',$id)->find_one();
							$keys->set('latitude', $input->latitude);
							$keys->set('longitude', $input->longitude);
							$keys->save();
							$status = "success";
							$message = 'Request edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete request

	$app->delete("/admin/request/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			try
			 {
				if ( $id && $_SESSION['admin'] )
				{
					$requests = Model::factory('Requests')->where('id',$id )->find_one();
					$requests->delete();

					$status = "success";
					$message = 'Request deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Get blogs
$app->get("/admin/blogs", function() use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			try
			 {
				if (isset($_SESSION['admin']))
					{
						$blogs = Model::factory('Blogs')->find_many();
								foreach( $blogs as $blog ){
							$response[] = array(
             'id'=>$blog->id,
									'title'=>$blog->title,
									'content'=>$blog->content,


						); }

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});
//Create blog
		$app->post("/admin/blog/create", function() use ($app)
		{

			session_start();
$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->title && $input->content && $_SESSION['admin'] )
				{
					$blogs = Model::factory('Blogs')->create();
					$blogs->title = $input->title;
					$blogs->content = $input->content;
					$blogs->save();

					$status = "success";
					$message = 'Blog created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit blog
			$app->put("/admin/blog/edit/:id", function($id) use ($app)
			{

				session_start();
		$_SESSION['admin'] = 1;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->title && $input->content && $_SESSION['admin'])
						{
							$blogs = Model::factory('Blogs')->where('id',$id)->find_one();
							$blogs->set('title', $input->title);
							$blogs->set('content', $input->content);
							$blogs->save();
							$status = "success";
							$message = 'Blog edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete blog

	$app->delete("/admin/blog/delete/:id", function($id) use ($app)
		{
			session_start();
		$_SESSION['admin'] = 1;

			try
			 {
				if ( $id && $_SESSION['admin'] )
				{
					$blogs = Model::factory('Blogs')->where('id',$id )->find_one();
					$blogs->delete();

					$status = "success";
					$message = 'Blog deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get pages
$app->get("/admin/pages", function() use ($app)
		{

			session_start();
			$_SESSION['admin'] = 1;
			try
			 {
				if (isset($_SESSION['admin']))
					{
						$pages = Model::factory('Pages')->find_many();
							foreach( $pages as $page ){
							$response[] = array(
             'id'=>$page->id,
									'title'=>$page->title,
									'content'=>$page->content,


						); }

					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});
//Create page
		$app->post("/admin/page/create", function() use ($app)
		{

			session_start();
	$_SESSION['admin'] = 1;
			$input = $app->request()->getBody();
		 	$input = json_decode($input);
			try
			 {
				if ( $input->title && $input->content && $_SESSION['admin'] )
				{
					$pages = Model::factory('Pages')->create();
					$pages->title = $input->title;
					$pages->content = $input->content;
					$pages->save();

					$status = "success";
					$message = 'Page created successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Edit page
			$app->put("/admin/page/edit/:id", function($id) use ($app)
			{

				session_start();
			$_SESSION['admin'] = 1;
				$input = $app->request()->getBody();
		   	$input = json_decode($input);
				try
			 		{
						if ($input->title && $input->content && $_SESSION['admin'])
						{
							$pages = Model::factory('Pages')->where('id',$id)->find_one();
							$pages->set('title', $input->title);
							$pages->set('content', $input->content);
							$pages->save();
							$status = "success";
							$message = 'Page edited successfully.';
						}
						else
						{
							$status = "danger";
							$message = 'Some error occured. Please try again.';
						}

			 		}
				catch (Exception $e)
					{
						$status = "danger";
						$message = $e->getMessage();
					}
				$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});



//Delete page

	$app->delete("/admin/page/delete/:id", function($id) use ($app)
		{
			session_start();
			$_SESSION['admin'] = 1;

			try
			 {
				if ( $id && $_SESSION['admin'])
				{
					$pages = Model::factory('Pages')->where('id',$id )->find_one();
					$pages->delete();

					$status = "success";
					$message = 'Blog deleted successfully.';
				}
				else
				{
					$status = "danger";
					$message = 'Some error occured. Please try again.';
				}
			 }
			catch (Exception $e)
				{
					$status = "danger";
					$message = $e->getMessage();
				}
			$response = array(
				'status' => $status,
				'message' => $message
			);

			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Get geolocation

$app->get("/admin/geolocations", function() use ($app)
		{
			session_start();
$_SESSION['admin'] = 1;

			try
			 {
				if (isset($_SESSION['admin']))
					{
						$geolocations = Model::factory('Geolocation')->find_many();
						foreach( $geolocations as $geolocation ){
					$response[] = array(
             'id'=>$geolocation->id,
						'user_id'=>$geolocation->user_id,
									'latitude'=>$geolocation->latitude,
									'longitude'=>$geolocation->longitude,


						); }
					}
				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




$app->get("/admin/username_by_id/:id", function($id) use ($app)
		{

			session_start();
	$_SESSION['admin'] = 1;
			try
			 {
				if ($id)
					{
						$user = Model::factory('Users')->where('id',$id)->find_one();

						$response= array(
						'username'=> $user->username,
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->get("/admin/geolocation_by_id/:id", function($id) use ($app)
		{

			session_start();
	$_SESSION['admin'] = 1;
			try
			 {
				if ($id)
					{
						$geolocation = Model::factory('Geolocation')->where('user_id',$id)->find_one();
						$response = array(
						'latitude'=>$geolocation->latitude,
						'longitude' => $geolocation->longitude,
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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});


//Paypal masspay
$app->get("/admin/payments/release", function($id) use ($app)
		{
			session_start();
$_SESSION['admin'] = 1;

			try
			 {
				if ($_SESSION['admin'])
				{

					function MassPay()
						{
							$config_paypal_username = Model::factory('Config')->where('key','paypal_username')->find_one();
  						$ppl_username = $config_paypal_username->value;

							$config_paypal_password = Model::factory('Config')->where('key','paypal_password')->find_one();
  						$ppl_password = $config_paypal_password->value;

							$config_paypal_signature = Model::factory('Config')->where('key','paypal_signature')->find_one();
  						$ppl_signature = $config_paypal_signature->value;

							$config_paypal_email_subject = Model::factory('Config')->where('key','paypal_email_subject')->find_one();
  						$vEmailSubject = $config_paypal_email_subject->value;

							$config_paypal_environment = Model::factory('Config')->where('key','paypal_environment')->find_one();
 						 	$enviornment = $config_paypal_environment->value;

							function PPHttpPost($methodName_, $nvpStr_)
							{
 								global $environment;

 								// Set up your API credentials, PayPal end point, and API version.
 								// How to obtain API credentials:
								// https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO

 								$API_Endpoint = "https://api-3t.paypal.com/nvp";
 								if("sandbox" === $environment || "beta-sandbox" === $environment)
 								{
  								$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
 								}
 								$version = urlencode('51.0');

 								// Set the curl parameters.
 								$ch = curl_init();
 								curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
 								curl_setopt($ch, CURLOPT_VERBOSE, 1);

 								// Turn off the server and peer verification (TrustManager Concept).
 								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

 								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_POST, 1);

 								// Set the API operation, version, and API signature in the request.
 								$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

 								// Set the request as a POST FIELD for curl.
 								curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

 								// Get response from the server.
 								$httpResponse = curl_exec($ch);

 								if( !$httpResponse)
 								{
  								exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) .')');
 								}

								// Extract the response details.
 								$httpResponseAr = explode("&", $httpResponse);

 								$httpParsedResponseAr = array();
 								foreach ($httpResponseAr as $i => $value)
 								{
 								 $tmpAr = explode("=", $value);
  							if(sizeof($tmpAr) > 1)
 								 {
   								$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
  								}
 								}

 								if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
 								{
 								 exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
 								}

 							return $httpParsedResponseAr;
						}

						// Set request-specific fields.
						$emailSubject = urlencode($vEmailSubject);
						$receiverType = urlencode('EmailAddress');
						$currency = urlencode('USD'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

						// Receivers
						// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
						// Here you can modify to obtain array data from database.

						$providers = process_api_get($base_url,'/providers');
						foreach( $providers as $provider ){
						$receivers [] = array(
							'receiverEmail' => $provider->email,
							'amount' => $provider->credits,
							'uniqueID' => "id_001",
							'note' => "payment"
							);
						}
						/*
						$receivers = array(
						  0 => array(
						    'receiverEmail' => "user1@paypal.com",
						    'amount' => "20.00",
						    'uniqueID' => "id_001", // 13 chars max
						    'note' => " payment of commissions"), // I recommend use of space at beginning of string.
						  1 => array(
						    'receiverEmail' => "user2@paypal.com",
						    'amount' => "162.38",
						    'uniqueID' => "A47-92w", // 13 chars max, available in 'My Account/Overview/Transaction details' when the transaction is made
						    'note' => " payoff of what I owed you"  // space again at beginning.
						  )
						);
						*/
								$receiversLenght = count($receivers);

								// Add request-specific fields to the request string.
								$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

								$receiversArray = array();

								for($i = 0; $i < $receiversLenght; $i++)
								{
								 $receiversArray[$i] = $receivers[$i];
								}

								foreach($receiversArray as $i => $receiverData)
								{
								 $receiverEmail = urlencode($receiverData['receiverEmail']);
								 $amount = urlencode($receiverData['amount']);
								 $uniqueID = urlencode($receiverData['uniqueID']);
								 $note = urlencode($receiverData['note']);
								 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
								}

								// Execute the API operation; see the PPHttpPost function above.
								$httpParsedResponseAr = PPHttpPost('MassPay', $nvpStr);

								if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
									{
									 	exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
									  $status = 'success';
									  $message = 'mass pay completed successfully.';

									}
								else
									{
								 		exit('MassPay failed: ' . print_r($httpParsedResponseAr, true));
										$status = 'danger';
									  $message = 'MassPay failed';
									}
								}

						}
				else
				{
					$status = "danger";
					$message = 'Mass pay failed';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

$app->get("/admin/availability/:id", function($id) use ($app)
		{

			session_start();
	$_SESSION['admin'] = 1;
			try
			 {
				if ($_SESSION['admin'])
					{

						$availabilities = Model::factory('Availability')->where('user_id',$id)->find_many();
						foreach( $availabilities as $availability ){
							$response[] = array(

             		'id'=>$availability->id,
								 'user_id'=>$availability->user_id,
								 'start'=>$availability->start,
								 'end'=>$availability->end,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Get availability by user id

$app->get("/admin/requests_by_user_id/:user_id", function($user_id) use ($app)
		{
			session_start();
		$_SESSION['admin'] = 1;

			try
			 {
				if (isset($_SESSION['id']))
					{

						$requests = Model::factory('Requests')->where('provided_to_user_id',$user_id)->find_many();
						foreach( $requests as $request ){
							$response[] = array(

             		'id'=>$request->id,
								 'provided_to_user_id'=>$request->user_id,
								 'start'=>$request->start,
								 'end'=>$request->end,
						);
						}



				}


				else
				{
					$status = "danger";
					$message = 'You need to be logged in to do that.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});

//Paypal release payment
$app->get("/admin/payment/release", function($id) use ($app)
		{

	session_start();
	$_SESSION['admin'] = 1;
			try
			 {
				if ($_SESSION['id'])
				{

					function MassPay()
						{
							$config_paypal_username = Model::factory('Config')->where('key','paypal_username')->find_one();
  						$ppl_username = $config_paypal_username->value;

							$config_paypal_password = Model::factory('Config')->where('key','paypal_password')->find_one();
  						$ppl_password = $config_paypal_password->value;

							$config_paypal_signature = Model::factory('Config')->where('key','paypal_signature')->find_one();
  						$ppl_signature = $config_paypal_signature->value;

							$config_paypal_email_subject = Model::factory('Config')->where('key','paypal_email_subject')->find_one();
  						$vEmailSubject = $config_paypal_email_subject->value;

							$config_paypal_environment = Model::factory('Config')->where('key','paypal_environment')->find_one();
 						 	$enviornment = $config_paypal_environment->value;

							function PPHttpPost($methodName_, $nvpStr_)
							{
 								global $environment;

 								// Set up your API credentials, PayPal end point, and API version.
 								// How to obtain API credentials:
								// https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO

 								$API_Endpoint = "https://api-3t.paypal.com/nvp";
 								if("sandbox" === $environment || "beta-sandbox" === $environment)
 								{
  								$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
 								}
 								$version = urlencode('51.0');

 								// Set the curl parameters.
 								$ch = curl_init();
 								curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
 								curl_setopt($ch, CURLOPT_VERBOSE, 1);

 								// Turn off the server and peer verification (TrustManager Concept).
 								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

 								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_POST, 1);

 								// Set the API operation, version, and API signature in the request.
 								$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

 								// Set the request as a POST FIELD for curl.
 								curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

 								// Get response from the server.
 								$httpResponse = curl_exec($ch);

 								if( !$httpResponse)
 								{
  								exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) .')');
 								}

								// Extract the response details.
 								$httpResponseAr = explode("&", $httpResponse);

 								$httpParsedResponseAr = array();
 								foreach ($httpResponseAr as $i => $value)
 								{
 								 $tmpAr = explode("=", $value);
  							if(sizeof($tmpAr) > 1)
 								 {
   								$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
  								}
 								}

 								if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
 								{
 								 exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
 								}

 							return $httpParsedResponseAr;
						}

						// Set request-specific fields.
						$emailSubject = urlencode($vEmailSubject);
						$receiverType = urlencode('EmailAddress');
						$currency = urlencode('GBP'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

						// Receivers
						// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
						// Here you can modify to obtain array data from database.

						$paypal_email = Model::factory('Config')->where('key','email_paypal')->find_one();
						$credits = Model::factory('Credits')->where('key','credits')->find_one();

						$receivers = array(
						  0 => array(
						    'receiverEmail' => $paypal_email->value,
						    'amount' => $credits->value,
						    'uniqueID' => "id_001", // 13 chars max
						    'note' => " Payment release"), // I recommend use of space at beginning of string.

						);

								$receiversLenght = count($receivers);

								// Add request-specific fields to the request string.
								$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

								$receiversArray = array();

								for($i = 0; $i < $receiversLenght; $i++)
								{
								 $receiversArray[$i] = $receivers[$i];
								}

								foreach($receiversArray as $i => $receiverData)
								{
								 $receiverEmail = urlencode($receiverData['receiverEmail']);
								 $amount = urlencode($receiverData['amount']);
								 $uniqueID = urlencode($receiverData['uniqueID']);
								 $note = urlencode($receiverData['note']);
								 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
								}

								// Execute the API operation; see the PPHttpPost function above.
								$httpParsedResponseAr = PPHttpPost('MassPay', $nvpStr);

								if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
									{
									 	exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
									  $status = 'success';
									  $message = 'Payment released successfully.';

									}
								else
									{
								 		exit('MassPay failed: ' . print_r($httpParsedResponseAr, true));
										$status = 'danger';
									  $message = 'Payment release failed.';
									}
								}

						}
				else
				{
					$status = "danger";
					$message = 'Payment release failed.';

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


			$app->response()->header("Content-Type", "application/json");
			echo json_encode($response);

 		});




$app->run();
