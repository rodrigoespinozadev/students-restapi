<?php

namespace System\Traits;

use System\Cookie;
use App\Models\Auth;
use System\RestException;

trait Authenticate
{
	protected function isLogged()
	{
		return Cookie::get('is_logged');
	}

	protected function validateLogin($request)
	{
		$auth = new Auth;
		$user = $auth->findUser($request);

		if (!password_verify($request->password, $user->password)) {
			throw new RestException(401, 'Invalid Credentials');
		}

		Cookie::set('is_logged', $user->id);

		if ($request->remember_me) {
			$token = Cookie::set('remember_me', true);
			$auth->setUserToken($user->id, $token);
		}

		return true;
	}

	protected function autoLogin()
	{
		$token = Cookie::get('remember_me');

		if ($token) {
			$auth = new Auth;
			$user = $auth->findUserByToken($token);
			if ($user) {
				Cookie::set('is_logged', $user->id);
			}
		}
	}
}
