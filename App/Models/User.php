<?php 

namespace App\Models;

use System\Model;

class User extends Model
{
	public function allUsers($page, $limit)
	{
		$results  = $this->paginate("SELECT * FROM students", $page, $limit);
		$total_results = $this->getTotal();

		return [
			'results' => $results,
			'total' => $total_results
		];
	}
}