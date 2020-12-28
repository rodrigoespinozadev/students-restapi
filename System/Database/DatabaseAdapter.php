<?php

namespace System\Database;

class DatabaseAdapter
{
    private $dbConnection;

    public function __construct(array $config)
    {
        extract($config);

        $class = 'System\\Database\\DB\\' . $driver;

        if (class_exists($class)) {
            $this->dbConnection = new $class($hostname, $username, $password, $database, $port);
        } else {
            exit('Error: Could not load database driver ' . $driver . '!');
        }
    }

    public function query($sql, array $params = []) : object
    {
        return $this->dbConnection->query($sql, $params);
    }
}
