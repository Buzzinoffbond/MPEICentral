<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Common {

	public function action_index()
	{	
		$username = $this->request->param('username');
		$this->template->title = $username;
		$this->template->content = View::factory('user/info')
			->bind('user', $user)
			->bind('logged_user',$logged_user);
		$logged_user = Auth::instance()->get_user();
		// Load the user information
		$user = Model::factory('Users')->get_user_info($username);
		if (!$username OR !$user)
		{
			throw new HTTP_Exception_404('Пользователя с таким именем не существует');
		}
		$this->template->title = HTML::chars($username);
	}
	public function action_edit() 
	{	
		if(Auth::instance()->logged_in())
		{
			$user = Auth::instance()->get_user();
			$this->template->content = View::factory('user/edit')
			->bind('user', $user)
			->bind('message', $message)
			->bind('errors', $errors)
			->bind('user_info', $user_info)
			->bind('vk_merge', $vk_merge);
			if ($this->request->post("submit_user_data")) 
			{
				try
				{
					$username = HelpingStuff::rusToLat($this->request->post('username'));
				$user = ORM::factory('User')
					->where('username', '=', $user->username)
					->find()
					->update_user(array(
						'username'			=>$username,
						'password'			=>$this->request->post('password'),
						'password_confirm'	=>$this->request->post('password_confirm'),
						'email'				=>$this->request->post('email')
					), array(
						'username',
						'password',
						'email'				
				));				
				// Set success message
				$message = "Профиль обновлен";
				$this->template->user=$user;
				}
				catch (ORM_Validation_Exception $e)
				{
				
					// Set failure message
					$message = 'Не получилось обновить профиль, есть ошибки.';
					// Set errors using custom messages
					$errors = $e->errors('models');
				}
			}
			if($this->request->post("submit_user_info"))
			{
				$institute=HelpingStuff::decodeInstitute($this->request->post("institute"),0);
				$age=$this->request->post('age');
				$data=array(
						"real_name"	=>$this->request->post("real_name"),
						"age"		=>$age,
						"hometown"	=>$this->request->post("hometown"),
						"institute"	=>$institute,
						"group"		=>$this->request->post("group"),
						"website"	=>$this->request->post("website"),
						"about"		=>$this->request->post("about"));
				$update_user_info= Model::factory("Users")->update_user_info($user->id,$data);
				if ($update_user_info==TRUE) {
					$message="Профиль обновлен";
				}
			}
			if ($this->request->post('update_avatar'))
			{
				$filename = Model::factory('Image')->upload_avatar($_FILES['avatar'],$user->id);
				if ($filename)
				{
					$message = "Аватар обновлен";
				}
				
			}
			$user_info=Model::factory("Users")->get_user_info($user->username);
			if ($this->request->post('disconnect_vk'))
			{
				Model::factory("Users")->disconnect_vk($user->id);	
			}

			//vkmerge_link
			$check_vk_connect = Model::factory('Users')->check_vk_connect($user->id);
			if ($check_vk_connect)
			{
				$vk_merge = '<form method="POST" action="">
				<input type="hidden" name="disconnect_vk" value="TRUE">
								<button class="vk-btn vk-merge" type="submit">
									<i class="vk-logo"></i>Отключить аккаунт Вконтакте
								</button>
							</form>';
			}
			else
			{
				$client_id = '/***ID***/'; // ID приложения
    			$redirect_uri = 'http://mpeicentral.ru/mergevk'; // Адрес сайта
				$url = 'http://oauth.vk.com/authorize';
				$params = array(
		    		'client_id'     => $client_id,
		    		'redirect_uri'  => $redirect_uri,
		    		'response_type' => 'code'
				);
					$vk_merge = '<a class="vk-btn vk-merge" href="' . $url . '?' . urldecode(http_build_query($params)) . '"><i class="vk-logo"></i>Подключить аккаунт Вконтакте</a>';
			}
			
			$this->template->head='
	<link rel="stylesheet" href="'.URL::base().'public/js/jquery-ui/css/jquery-ui-1.10.3.custom.css" />
  	<script src="'.URL::base().'public/js/jquery-ui/jquery-ui-1.10.3.custom.js"></script>
  	<script src="'.URL::base().'public/js/jquery-ui/jquery.ui.datepicker-ru.js"></script>
  	    	<script>
  				$(function() {
    			$( "#age" ).datepicker({
    				dateFormat: "dd-mm-yy",
    				changeYear: true,
    				changeMonth: true,
    				yearRange: "1930:+0"
    				});
    			$( "#age" ).datepicker( $.datepicker.regional[ "ru" ] );
  				});
  			</script>
  	<script type="text/javascript" src="'.URL::site("public/js/autosize-master/jquery.autosize-min.js").'"></script>';
		}
		else
		{
			HTTP::redirect('login');
		}	
	}	
	public function action_register() 
	{
		if(Auth::instance()->logged_in())
		{
			HTTP::redirect();
		}
		$this->template->content = View::factory('user/register')
			->bind('errors', $errors)
			->bind('message', $message);
			
		if (HTTP_Request::POST == $this->request->method()) 
		{			
			try {
		
				// Create the user using form values
				$username = HelpingStuff::rusToLat($this->request->post('username'));
				$user = ORM::factory('User')->create_user(array(
					'username'			=>$username,
					'password'			=>$this->request->post('password'),
					'password_confirm'	=>$this->request->post('password_confirm'),
					'email'				=>$this->request->post('email')
					), array(
					'username',
					'password',
					'email'				
				));
				
				// Grant user login role
				$user->add('roles', ORM::factory('Role', array('name' => 'login')));
				
				// Reset values so form is not sticky
				$_POST = array();
				
				$user = Auth::instance()->login($this->request->post('username'), $this->request->post('password'), $remember=TRUE);
				
				//create additional table
				$user_info = Auth::instance()->get_user();
				$create_user_info = Model::factory('Users')->create_user_info($user_info->id);
				
				//redirect to user profile
				HTTP::redirect('/user/'.$this->request->post('edit'));
			} catch (ORM_Validation_Exception $e) {
				
				// Set failure message
				$message = 'There were errors, please see form below.';
				
				// Set errors using custom messages
				$errors = $e->errors('models');
			}
		}
	}
	
	public function action_login() 
	{	
		if(Auth::instance()->logged_in())
		{
			HTTP::redirect('user/'.$this->request->post('username'));
		}

		$this->template->content = View::factory('user/login')
			->bind('message', $message)
			->bind('vk_link', $vk_link);

		//vkauth
		$client_id = '/***ID***/'; // ID приложения
    	$redirect_uri = 'http://mpeicentral.ru/vkauth'; // Адрес сайта

    	$url = 'http://oauth.vk.com/authorize';

		$params = array(
		    'client_id'     => $client_id,
		    'redirect_uri'  => $redirect_uri,
		    'response_type' => 'code'
			);
		
		$vk_link = '<a class="vk-btn" href="' . $url . '?' . urldecode(http_build_query($params)) . '"><i class="vk-logo"></i>Войти через Вконтакте</a>';
		




		if (HTTP_Request::POST == $this->request->method()) 
		{
			// Attempt to login user
			$remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
			$user = Auth::instance()->login($this->request->post('username'), $this->request->post('password'), $remember);
			
			// If successful, redirect user
			if ($user) 
			{
				HTTP::redirect('user/'.$this->request->post('username'));
			} 
			else 
			{
				$message = 'Login failed';
			}
		}
	}
	
	public function action_logout() 
	{
		// Log user out
		Auth::instance()->logout();
		
		// Redirect to login page
		HTTP::redirect('login');
	}
	public function action_vkauth()
	{
		if(Auth::instance()->logged_in())
		{
			HTTP::redirect('user/'.$this->request->post('username'));
		}

		$client_id = '/***ID***/'; // ID приложения
    	$client_secret = '/***KEY***/'; // Защищённый ключ
    	$redirect_uri = 'http://mpeicentral.ru/vkauth'; // Адрес сайта
		
		if (isset($_GET['code'])) {
		    $result = false;
		    $params = array(
		        'client_id' => $client_id,
		        'client_secret' => $client_secret,
		        'code' => $_GET['code'],
		        'redirect_uri' => $redirect_uri
		    );
		
		    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
		
		    if (isset($token['access_token'])) {
		        $basic_params = array(
		            'uids'         => $token['user_id'],
		            'fields'       => 'uid',
		            'access_token' => $token['access_token']
		        );
		
		        $userData = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($basic_params))), true);
		        if (isset($userData['response'][0]['uid'])) {
		            $userId = $userData['response'][0]['uid'];
		            $result = true;
		        }
		    }
		
		    if ($result)
		    {
		    	//check if user have alredy been registered
		    	$orm_user = ORM::factory('User', array('vk_id' => $userId));
		    	if (($orm_user->loaded() == TRUE)) {
		    		try
		    		{
		    			$orm_user = Auth::instance()->force_login_r($orm_user->username, FALSE, TRUE);
		    			HTTP::redirect();
		    		}
		    		catch(ORM_Validation_Exception $e)
		    		{
		    			// Set errors using custom messages
						$errors = $e->errors('models');
		    		}
		    	}
		    	else{
		    		//get info about vk user
		        	$extended_params = array(
		            'uids'         => $token['user_id'],
		            'fields'       => 'uid,first_name,last_name,screen_name,photo_200',
		            'access_token' => $token['access_token']
		        	);
		
		        	$user_reg_info = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($extended_params))), true);
		        	if (isset($user_reg_info['response'][0]['uid'])) {
		            	$user_reg_info = $user_reg_info['response'][0];

		            	//create new user
		            	$login = Model::factory('Users')->create_vk_user($user_reg_info);
		            	Auth::instance()->force_login_r($login, FALSE, TRUE);
		            	HTTP::redirect();
		        	}
		    		
		    	}
		    }
		}

	}
	public function action_mergevk(){
		if(Auth::instance()->logged_in())
		{
			$client_id = '/***ID***/'; // ID приложения
    		$client_secret = '/***KEY***/'; // Защищённый ключ
    		$redirect_uri = 'http://mpeicentral.ru/mergevk'; // Адрес сайта
		
			if (isset($_GET['code']))
			{
		    	$result = false;
		    	$params = array(
		        	'client_id' => $client_id,
		        	'client_secret' => $client_secret,
		        	'code' => $_GET['code'],
		        	'redirect_uri' => $redirect_uri
		    	);
		
		    	$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
		
		    	if (isset($token['access_token'])) {
		        	$basic_params = array(
		            	'uids'         => $token['user_id'],
		            	'fields'       => 'uid',
		            	'access_token' => $token['access_token']
		        	);
		
		        	$userData = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($basic_params))), true);
		        	if (isset($userData['response'][0]['uid'])) {
		            	$userId = $userData['response'][0]['uid'];
		            	$result = true;
		        	}
		    	}
		
		    	if ($result)
		    	{
		    		$user_info = Auth::instance()->get_user();
		    		Model::factory('Users')->merge_vk($user_info->id, $userId);
		    		HTTP::redirect('edit');
		    	}
		    }
		}
		else
		{
			HTTP::redirect('login');
		}
	}
	public function action_delete_me(){
		$user_info = Auth::instance()->get_user();
		$result = Model::factory("Users")->delete_user($user_info->username);
		HTTP::redirect();
	}
	public function action_request_pass_reset(){
		if(Auth::instance()->logged_in())
		{
			HTTP::redirect();
		}

		$view = View::factory('user/request_pass_reset')
			->bind('message', $message);
		if (HTTP_Request::POST == $this->request->method())
		{
			try
			{
				$result = Model::factory('Users')->request_password_reset($this->request->post('email'));
				if ($result===TRUE)
				{
					$view = View::factory('user/request_pass_reset_success');
				}
				else
				{
					$message = 'Не удалось найти email.';
				}
			}
			catch(Exceptions $e)
			{
				$message='Произошла ошибка';
			}
		}
		
		$this->template->content = $view;
	}
	public function action_reset_pass(){
		if ($this->request->query('t') OR !Auth::instance()->logged_in())
		{
			try
			{
				$result = Model::factory('Users')->check_recovery_token($this->request->query('t'));	
				if (!empty($result))
				{
					$view = View::factory('user/reset_pass')
					->bind('message',$message)
					->bind('errors', $errors)
					->bind('token',$token);
					$token = $this->request->query('t');
					if (HTTP_Request::POST == $this->request->method()) 
					{
						try
						{
							$user = ORM::factory('User')
								->where('id', '=', $result['id'])
								->find()
								->update_user(array(
									'password'			=>$this->request->post('password'),
									'password_confirm'	=>$this->request->post('password_confirm'),
								), array(
									'password'			
							));
							Model::factory('Users')->delete_user_tokens($result['id']);

							Auth::instance()->force_login($user, FALSE);
							HTTP::redirect('edit');

						}
						catch (ORM_Validation_Exception $e)
						{
				
							// Set failure message
							$message = 'Не получилось обновить профиль, есть ошибки.';
							// Set errors using custom messages
							$errors = $e->errors('models');
						}
					}

					$this->template->content = $view;

				}
				else
				{
					//HTTP::redirect('request_pass_reset/?m=expired');
				}
			}
			catch(Exceptions $e)
			{
				echo 'Произошла ошибка';
			}
		}
		else
		{
			HTTP::redirect();
		}
	}
}