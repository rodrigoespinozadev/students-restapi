<?php

namespace System\Auth;

use System\Contracts\Auth as AuthContract;
use System\RestException;

class Auth implements AuthContract
{
	public function isAuthorized($classObj) : bool
	{
		if (method_exists($classObj, 'isAuthenticated')) {
			return $classObj->isAuthenticated();
		}

		return true;
	}

	public function unauthorized($classObj) : void
	{
		throw new RestException(401, "You are not authorized to access this resource.");
	}
}
