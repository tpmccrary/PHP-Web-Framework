<?php

namespace app\models;

use app\core\Application;
// use app\models\Model;
require_once(dirname(__DIR__)."/models/Model.php");

abstract class DatabaseModel extends Model
{

    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn($attr) => ":$attr", $attributes);

        $stmnt = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).") VALUES(".implode(',', $params).")");

        foreach ($attributes as $attribute) {
            $stmnt->bindValue(":$attribute", $this->$attribute);
        }

        $stmnt->execute();
        return true;
    }

    public function update()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $primaryKey = $this->primaryKey();
        $id = Application::$app->user->id;

        $params = array_map(fn($attr) => ":$attr", $attributes);


        $sql = "UPDATE user SET ".implode(',', $attributes)."=".implode(',', $params)." WHERE $primaryKey = $id";

        $stmnt = self::prepare($sql);

        foreach ($attributes as $attribute) {
            $stmnt->bindValue(":$attribute", $this->$attribute);
        }

        $stmnt->execute();
        return true;

    }

    public static function prepare($sql)
    {
        return Application::$app->database->pdo->prepare($sql);
    }

    public static function query($sql)
    {
        return Application::$app->database->pdo->query($sql);
    }

    public static function getAll()
    {
        $tableName = static::tableName();
        $stmnt = self::prepare("SELECT * FROM $tableName");
        $stmnt->execute();
        return $stmnt->fetchAll();
    }

    public function findOne($where)
    {
        $tableName = static::tableName();

        $attributes = array_keys($where);

        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $sql = "SELECT * FROM $tableName WHERE ".$sql;

        $stmnt = self::prepare($sql);

        foreach ($where as $key => $value) {
            $stmnt->bindValue(":$key", $value);
        }

        $stmnt->execute();
        return $stmnt->fetchObject(static::class);
    }

    // public function getAll()
    // {
    //     $sql = "SELECT * FROM `users`";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute();
    //     return $stmt->fetchAll();
    // }

    // public function getOne($id)
    // {
    //     $sql = "SELECT * FROM `users` WHERE `id` = :id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':id', $id);
    //     $stmt->execute();
    //     return $stmt->fetch();
    // }

    // public function create($name, $email, $password)
    // {
    //     $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (:name, :email, :password)";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':name', $name);
    //     $stmt->bindParam(':email', $email);
    //     $stmt->bindParam(':password', $password);
    //     $stmt->execute();
    // }

    // public function update($id, $name, $email, $password)
    // {
    //     $sql = "UPDATE `users` SET `name` = :name, `email` = :email, `password` = :password WHERE `id` = :id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':id', $id);
    //     $stmt->bindParam(':name', $name);
    //     $stmt->bindParam(':email', $email);
    //     $stmt->bindParam(':password', $password);
    //     $stmt->execute();
    // }

    // public function delete($id)
    // {
    //     $sql = "DELETE FROM `users` WHERE `id` = :id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':id', $id);
    //     $stmt->execute();
    // }
}