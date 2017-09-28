<?php
/**
 * Created by PhpStorm.
 * User: nicu
 * Date: 26/09/2017
 * Time: 13:09
 */

namespace Api\Domain\Repository;

use Api\Domain\Model\BaseModel;

class BaseRepository
{
    protected $connection;

    public function getConnection()
    {
        $db       = 'calendar';
        $host     = 'localhost';
        $username = 'root';
        $password = 'root';

        $this->connection = new \PDO("mysql:dbname=$db;host=$host", $username, $password);

        return $this->connection;
    }

    public function executeQuery($query, $params = [], $action = 'select')
    {
        $connection = $this->getConnection();
        $stmt       = $connection->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        if ($action == 'insert') {
            return $connection->lastInsertId();
        }

        return $stmt->fetchAll();
    }

    public function all()
    {
        return $this->executeQuery('SELECT * FROM ' . $this->table);
    }

    public function get($id)
    {
        $rows = $this->executeQuery('SELECT * FROM ' . $this->table . ' WHERE id = :id', [':id' => $id]);

        if (!count($rows)) {
            throw new \Exception('Resource not found', 404);

            return null;
        }

        $row = $rows[0];

        $model   = $this->getModel();
        $methods = get_class_methods($model);

        foreach ($methods as $method) {
            if (substr($method, 0, 3) == 'set') {
                $key = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', substr($method, 3))), '_');
                $model->$method($row[ $key ]);
            }
        }

        return $model;
    }

    public function add(BaseModel &$model)
    {
        $methods = get_class_methods($model);

        $columns = $placeholders = $params = [];
        foreach ($methods as $method) {
            if (substr($method, 0, 3) == 'get' && $method != 'getId') {
                $key                  = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', substr($method, 3))), '_');
                $columns[]            = $key;
                $placeholders[]       = ':' . $key;
                $params[ ':' . $key ] = $model->$method();
            }
        }

        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', $placeholders) . ')';

        $id = $this->executeQuery($query, $params, 'insert');
        $model->setId($id);

        return $model;
    }

    public function update(BaseModel $model)
    {
        $methods = get_class_methods($model);

        $columns = $placeholders = $params = [];
        foreach ($methods as $method) {
            if (substr($method, 0, 3) == 'get' && $method != 'getId') {
                $key                  = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', substr($method, 3))), '_');
                $columns[]            = $key . '=:' . $key;
                $placeholders[]       = ':' . $key;
                $params[ ':' . $key ] = $model->$method();
            }
        }

        $query = 'UPDATE ' . $this->table . ' SET ' . implode(',', $columns) . ' WHERE id = ' . $model->getId();

        return $this->executeQuery($query, $params, 'update');
    }

    public function delete($resource)
    {
        $id = $resource;
        if ($resource instanceof BaseModel) {
            $id = $resource->getId();
        }

        $this->get($id);

        $this->executeQuery('DELETE FROM ' . $this->table . ' WHERE id = :id', [':id' => $id]);
    }

    private function getModel()
    {
        $entity = '\\Api\\Domain\\Model\\' . ucfirst($this->table);
        $model  = new $entity();

        return $model;
    }

}