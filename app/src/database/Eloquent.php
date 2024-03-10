<?php 

namespace Source\Database;

use Source\Database\Database;

class Eloquent{
    protected static $table;
    private static $sqlWhere = "WHERE ";
    private static $paramsWhere = [];
    private static $fieldsWhere = [];
    private static $sqlOrderBy;

    protected static function getTableName() {
        return static::$table;
    }

    public static function whereId(int $id){
        self::$paramsWhere[] = $id;
        self::$sqlWhere = self::$sqlWhere . "id = ?";
        return new static();
    }

    public static function where(string $field, string $value){
        $complement = !empty(self::$paramsWhere) ? ' AND ' : '';
        self::$paramsWhere[] = $value;
        self::$fieldsWhere[] = $field;
        self::$sqlWhere = self::$sqlWhere . $complement . "$field = ? ";
        return new static();
    }

    public static function first(){
        $table = static::$table;
        $sqlWhere = self::$sqlWhere;
        $sql = "SELECT * FROM $table $sqlWhere LIMIT 1";
        $database = new Database();
        $data = $database->first($sql, self::$paramsWhere);
        return ($data) ? new static($data) : null;
    }

    public static function find(int $id){
        $table = static::$table;
        $sql = "SELECT * FROM $table WHERE id=?";
        $database = new Database();
        $data = $database->find($sql, [$id]);
        return ($data) ? new static($data) : null;
    }

    public static function update(array $data){
        $table = static::$table;
        $sqlWhere = self::$sqlWhere;
        $stringKeys = '';

        foreach ($data as $key => $value) {
            $stringKeys .= "$key = ?, ";
        }

        $stringKeys = rtrim($stringKeys, ', ');

        $sql = "UPDATE $table SET $stringKeys, updated_at = now() $sqlWhere";
        $database = new Database();
        array_push($data, self::$paramsWhere[0]);
        $values = array_values($data);
        $data = $database->update($sql, $values);
        return ($data) ? self::find(self::$paramsWhere[0]) : null;
    }

    public static function create(array $data){
        $table = static::$table;
        $stringKeys = '';

        foreach ($data as $key => $value) {
            $stringKeys .= "$key, ";
        }

        $stringKeys = rtrim($stringKeys, ', ');
        $stringValues = '';

        foreach ($data as $key => $value) {
            $stringValues .= "?, ";
        }

        $stringValues = rtrim($stringValues, ', ');
        $sql = "INSERT INTO $table ($stringKeys, created_at) VALUES($stringValues, now())";
        $database = new Database();
        $id = $database->insert($sql, array_values($data));
        return ($id) ? self::find($id) : null;
    }

    public static function destroy(int $id){
        $table = static::$table;
        $sql = "UPDATE $table SET deleted_at = NOW() WHERE id = ?";
        $database = new Database();
        $database->delete($sql, [$id]);
    }

    public static function orderBy(string $field, string $order){
        $table = static::$table;
        self::$sqlOrderBy = "SELECT * FROM $table WHERE deleted_at is NULL ORDER BY $field $order";
        return new static();
    }

    public static function get(){
        $database = new Database();
        $result = $database->select(self::$sqlOrderBy);

        if(empty($result)) return null;

        $collection = [];
        foreach($result as $row){
            $collection[] = new static($row);
        } 

        return $collection;
    }

    public static function firstOrCreate(array $data){
        $table = static::$table;
        $stringKeys = '';

        foreach ($data as $key => $value) {
            $stringKeys .= "$key = ? AND ";
        }

        $stringKeys = rtrim($stringKeys, ' AND ');
        $values = array_values($data);
        $sql = "SELECT * FROM $table WHERE $stringKeys LIMIT 1";
        $database = new Database();
        $result = $database->first($sql, $values);
        return ($result) ? new static($result) : self::create($data);
    }

    public static function updateOrCreate(array $keys, array $data){
        $table = static::$table;

        
        $stringKeys = '';

        foreach ($data as $key => $value) {
            $stringKeys .= "$key = ?, ";
        }

        $stringKeys = rtrim($stringKeys, ', ');

        $stringWhere = '';

        $values = array_values($data);

        for ($i = 0; $i < count($keys); $i++) {
            $stringWhere .= " $keys[$i] = ? AND ";
            $values[] = $data[$keys[$i]];
        }

        $stringWhere = rtrim($stringWhere, ' AND ');

        $sql = "UPDATE $table SET $stringKeys, updated_at = now() WHERE $stringWhere";
        $database = new Database();
        $result = $database->update($sql, $values);

        if($result === false) {
            self::create($data);
        }
    }

    protected function belongsTo(string $className){
        $obj = new $className;
        $table = static::$table;
        $joinTable = $obj->getTableName();
        $id = $this->getId();

        if(empty($id)) return null;

        $sql = "SELECT $joinTable.* FROM $table as t JOIN $joinTable WHERE t.id = $id";
        $database = new Database();
        $result = $database->belongsTo($sql);
        return ($result) ? new $className($result) : null;
    }
}