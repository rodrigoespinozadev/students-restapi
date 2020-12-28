<?php

namespace System\Http;

class Request
{
	protected $jsonAssoc = false;

	public function server(string $key = '')
	{
		return isset($_SERVER[strtoupper($key)])
			? $this->clean($_SERVER[strtoupper($key)])
			: $this->clean($_SERVER);
	}

	public function getUrl(): string
	{
		return preg_replace('/\?.*$/', '', $this->server('REQUEST_URI'));
	}

	public function getMethod(): string
	{
		return strtoupper($this->server('REQUEST_METHOD'));
	}

	private function clean($data)
	{
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);
				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}

	public function getData()
	{
		$data = file_get_contents('php://input');
		$data = json_decode($data, $this->jsonAssoc);
		return $data;
	}
}
