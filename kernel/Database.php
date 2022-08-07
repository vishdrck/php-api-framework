<?php

namespace app\kernel;

class Database
{
    public \PDO $pdo;

    public ?\PDOStatement $pdoStatement = null;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';
        try {
            $this->pdo = new \PDO($dsn, $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            echo "Database Connection failed: " . $exception->getMessage();
        }

    }

    public function query($query): Database
    {
        if (isset($query)) {
            $this->pdoStatement = $this->pdo->query($query);
        }
        return $this;
    }

    public function prepare($query): Database
    {
        if (isset($query)) {
            $this->pdoStatement = $this->pdo->prepare($query);
        }
        return $this;
    }

    public function execute($params=[]): Database
    {
        if ($this->pdoStatement !== null || $this->pdoStatement !== false) {
            $this->pdoStatement->execute($params);
        }
        return $this;
    }

    public function exec() {
        if ($this->pdoStatement !== null || $this->pdoStatement !== false) {
            return $this->pdo->exec($this->pdoStatement);
        }
        return 0;
    }

    public function fetch() {
        if($this->pdoStatement === null || $this->pdoStatement === false) {
            return array();
        }
        return $this->pdoStatement->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll() {
        if($this->pdoStatement === null || $this->pdoStatement === false) {
            return array();
        }
        return $this->pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

}