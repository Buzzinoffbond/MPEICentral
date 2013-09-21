<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Auth extends Kohana_Auth {

	public function force_login_r($user, $mark_session_as_forced = FALSE, $remember = FALSE)
	{
		if ( ! is_object($user))
		{
			$username = $user;

			// Load the user
			$user = ORM::factory('User');
			$user->where($user->unique_key($username), '=', $username)->find();
		}

		if ($mark_session_as_forced === TRUE)
		{
			// Mark the session as forced, to prevent users from changing account information
			$this->_session->set('auth_forced', TRUE);
		}

		if ($remember === TRUE)
			{
				// Token data
				$data = array(
					'user_id'    => $user->pk(),
					'expires'    => time() + $this->_config['lifetime'],
					'user_agent' => sha1(Request::$user_agent),
				);

				// Create a new autologin token
				$token = ORM::factory('User_Token')
							->values($data)
							->create();

				// Set the autologin cookie
				Cookie::set('authautologin', $token->token, $this->_config['lifetime']);
				echo "remembered";
			}

		// Run the standard completion
		$this->complete_login($user);
		echo "forced";
	}
}