<?php 

namespace Source\Database;

use PDOException;
use PDO;
use Symfony\Component\Dotenv\Dotenv;

class Database{
    private $connection;

    public function __construct(){

        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__).'/../.env');
        $driver = $_ENV['DB_CONNECTION'];
        $host = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        try{
            $this->connection = new PDO("$driver:host=$host;dbname=$dbName", $username, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
        }catch(Exception $e){
            echo 'Error Connection:'.$e->getMessage()."\n";
        }
        
    }

    public function select(string $sql){
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            echo 'Error select: ' . $e->getMessage();
        }
    }

    public function belongsTo(string $sql){
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result !== false ? $result : null;
        }catch(PDOException $e){
            echo 'Error select: ' . $e->getMessage();
        }
    }

    public function insert(string $sql, array $data){
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($data);
            return $this->connection->lastInsertId();
        }catch(PDOException $e){
            echo 'Error insert: ' . $e->getMessage();
        }
    }

    public function update(string $sql, array $params){
        try{
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($params);
        }catch(PDOException $e){
            echo 'Error update: ' . $e->getMessage();
        }
    }

    public function first(string $sql, array $params){
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result !== false ? $result : null;
        }catch(PDOException $e){
            echo 'Error in query first: ' . $e->getMessage();
        }
    }

    public function find(string $sql, array $params){
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result !== false ? $result : null;
        }catch(PDOException $e){
            echo 'Error in query find: ' . $e->getMessage();
        }
    }

    public function delete(string $sql, array $params){
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
        }catch(PDOException $e){
            echo 'Error delete: ' . $e->getMessage();
        }
    }
}