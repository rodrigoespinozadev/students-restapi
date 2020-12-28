<?php
namespace System\Database\DB;

final class PDO 
{
    private $pdo = null;

    private $statement = null;

    public function __construct($hostname, $username, $password, $database, $port) {
        try {
            $this->pdo = new \PDO("mysql:host=" . $hostname . ";port=" . $port . ";dbname=" . $database, $username, $password, array(\PDO::ATTR_PERSISTENT => true));
        } catch(\PDOException $e) {
            trigger_error('Error: Could not make a database link ( ' . $e->getMessage() . '). Error Code : ' . $e->getCode() . ' <br />');
            exit();
        }

        $this->pdo->exec("SET NAMES 'utf8'");
        $this->pdo->exec("SET CHARACTER SET utf8");
        $this->pdo->exec("SET CHARACTER_SET_CONNECTION=utf8");
        $this->pdo->exec("SET SQL_MODE = ''");

    }
    
    public function query($sql, array $params = []) {
        $this->statement = $this->pdo->prepare($sql);
        $result = false;

        try {
            if ($this->statement && $this->statement->execute($params)) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_OBJ)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0]) ? $data[0] : null);
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (\PDOException $e) {
            trigger_error('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
            exit();
        }
    
        if ($result) {
            return $result;
        } else {
            $result = new \stdClass();
            $result->row = null;
            $result->rows = array();
            $result->num_rows = 0;
            return $result;
        }
    }

    public function escape($value) {
        $search = array("\\", "\0", "\n", "\r", "\x1a", "'", '"');
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"');
        return str_replace($search, $replace, $value);
    }

    public function __destruct() {
        $this->pdo = null;
    }
}