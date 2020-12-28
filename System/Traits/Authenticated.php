<?php

namespace System\Traits;

use System\Cookie;

trait Authenticated
{
	public function isAuthenticated()
	{
		return Cookie::get('is_logged');
	}
}
