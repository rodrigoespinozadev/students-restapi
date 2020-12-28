<?php

namespace System;

class Cookie
{
	public static function get($name)
	{
		if (isset($_COOKIE[$name])) {
			if ($v = json_decode(base64_decode($_COOKIE[$name]))) {
				if ($v[0] < COOKIE['timeout']) {
					return is_scalar($v[1]) ? $v[1] : (array)$v[1];
				}
			}
		}

		return FALSE;
	}

	public static function set($name, $value)
	{
		extract(COOKIE);
		$value = $value ? base64_encode(json_encode(array(time(), $value))) : '';
		$_COOKIE[$name] = $value;
		setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
		return $value;
	}

	public static function remove($name)
	{
		self::set($name, '');
		unset($_COOKIE[$name]);
	}
}
