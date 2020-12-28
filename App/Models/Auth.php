<?php 

namespace App\Models;

use System\Model;

class Auth extends Model
{
	public function findUser($data)
	{
		$this->sql = [
			"SELECT id, password FROM api_users WHERE username = ?",
			[$data->username]
		];
		return $this->fetch();
	}

	public function setUserToken($id, $token)
	{
		$this->query("UPDATE api_users SET token = ? WHERE id = ?", [$token, $id]);
	}

	public function findUserByToken($token)
	{
		$result = $this->query("SELECT id FROM api_users WHERE token = ?", [$token]);
		return $result->row;
	}
}