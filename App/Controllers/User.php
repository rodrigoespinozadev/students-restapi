<?php

namespace App\Controllers;

use System\Controller;
use System\Traits\Authenticated;
use App\Models\User as ModelsUser;

class User extends Controller
{
	use Authenticated;

	public function index()
	{
		$users = (new ModelsUser())->allUsers(
			$this->pager['page'], 
			$this->pager['limit']
		);

		$this->pager['total'] = $users['total'];
		$this->pager['pages'] = ceil($users['total'] / $this->pager['limit']);

		$this->response->setContent([
			'results' => $users['results'],
			'pager' => $this->pager
		]);
	}
}
