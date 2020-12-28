<?php

namespace System\Contracts;

interface Auth
{
	public function isAuthorized($classObj) : bool;

	public function unauthorized($classObj) : void;
}
