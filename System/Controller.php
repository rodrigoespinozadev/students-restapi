<?php

namespace System;

abstract class Controller
{
	protected $request;

	protected $response;

	protected $pager = array();

	public function __construct()
	{
		$this->request = $GLOBALS['request'];

		$this->response = $GLOBALS['response'];

		$this->pager = array(
			'page' => isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1,
			'limit' => isset($_GET['limit']) ? $_GET['limit'] : 5,
			'total' => null
		);
	}
}
