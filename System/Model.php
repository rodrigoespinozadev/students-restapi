<?php

namespace System;

use System\Database\DatabaseAdapter;

class Model
{
	public $db;

	protected $sql = '';

	protected $sql_count = '';

	private $pager = array(
		'page' => 1,
		'limit' => 5,
		'total' => null,
		'offset' => null,
		'sql' => '',
	);

	public function __construct()
	{

		try {
			$this->db = new DatabaseAdapter(DATABASE);
		} catch (\Throwable $th) {
			exit(sprintf('Unable to connect to database { %s }!', $th->getMessage()));
		}
	}

	public function paginate($sql, $page = 1, $limit = 5)
	{
		$this->_preparePager($limit, $page);
		$this->sql = $sql;
		return $this->fetchAll();
	}

	protected function _preparePager($limit, $page)
	{
		if ($limit) {
			$this->pager['limit'] = $limit;
			$this->pager['page'] = $page;
			$this->pager['offset'] = ($this->pager['page'] - 1) * $this->pager['limit'];
			$this->pager['sql'] = 'LIMIT ' . $this->pager['offset'] . ', ' . $this->pager['limit'] . ';';
		}
	}

	public function query($sql, array $params = [])
	{
		return $this->db->query($sql, $params);
	}

	public function fetch()
	{
		$params = [];

		if (is_array($this->sql)) {
			$sql = $this->sql[0];
			$params = is_array($this->sql[1]) ? $this->sql[1] : [];
		}

		$result = $this->db->query($sql, $params);
		return $result->row;
	}

	public function fetchAll()
	{
		$sql = $this->sql;
		if (!empty($this->pager['sql'])) {
			if ($this->pager['limit'] < 1) {
				return [];
			}

			$sql .= ' ' . $this->pager['sql'];
		}

		$this->pager['sql'] = '';
		$results = [];
		$result = $this->db->query($sql);

		foreach ($result->rows as $row) {
			$results[] = $row;
		}

		return $results;
	}

	public function getTotal()
	{
		if (empty($this->sql_count)) {
			$this->sql_count = explode('FROM', $this->sql);
			array_shift($this->sql_count);
			$this->sql_count = 'SELECT COUNT(*) AS count FROM' . implode('FROM', $this->sql_count);
		}

		if (empty($this->sql_count)) {
			return;
		}

		$result = $this->db->query($this->sql_count);
		$total = (int) $result->row->count;

		return $total;
	}

	public function __destruct()
	{
		$this->db = null;
	}
}
