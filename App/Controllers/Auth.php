<?php 

namespace App\Controllers;

use System\Controller;
use System\Cookie;
use System\RestException;
use System\Traits\Authenticate;
use App\Models\Auth as AuthModel;

class Auth extends Controller
{
	use Authenticate;

	public function dologin($params)
	{
		if ($this->isLogged()) {
			throw new RestException(401, 'Already logged in');
		}

		$this->validateLogin($params['data']);
	}

	public function signout()
	{
		if (!$this->isLogged()) {
			throw new RestException(401, 'Not logged');
		}

		$user_id = Cookie::get('is_logged');
		(new AuthModel)->setUserToken($user_id, null);

		Cookie::remove('is_logged');
		Cookie::remove('remember_me');
	}
}