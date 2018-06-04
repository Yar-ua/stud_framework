<?php

namespace Mindk\Framework\Models;

use Mindk\Framework\DB\DBOConnectorInterface;

/**
 * Basic Model Class
 * @package Mindk\Framework\Models
 */
abstract class Model
{
    /**
     * @var string  DB Table name
     */
    protected $tableName = '';

    /**
     * @var string  DB Table primary key
     */
    protected $primaryKey = 'id';

    /**
     * @var null
     */
    protected $dbo = null;

    /**
     * Model constructor.
     * @param DBOConnectorInterface $db
     */
    public function __construct(DBOConnectorInterface $db)
    {
        $this->dbo = $db;
    }

    /**
     * Create new record
     */
    public function create($data) {
        //@TODO: Implement this

        $sql = 'INSERT INTO `' . $this->tableName .  '` SET ' . self::pdoSet($data);
        $result = $this->dbo->insertQuery($sql, $data);

        if ($result['status'] == true) {
            return self::load($result['id']);
        } else {
            throw new \Exception('Create operation was unsuccessfully');
        }
    }

    /**
     * Read record
     *
     * @param   int Record ID
     *
     * @return  object
     */
    public function load( $id ) {
        $sql = 'SELECT * FROM `' . $this->tableName .
            '` WHERE `'.$this->primaryKey.'`='.(int)$id; //!

        return $this->dbo->setQuery($sql)->getResult($this);
    }

    /**
     * Save record state to db
     *
     * @return bool
     */
    public function save($id, $data) {
        //@TODO: Implement this
        //$values = self::setSQLValues($data);
        $sql = 'UPDATE `' . $this->tableName .  '` SET ' . self::pdoSet($data) .
            ' WHERE id=' . (int)$id;

        $result = $this->dbo->insertQuery($sql, $data);

        if ($result['status'] == true) {
            return self::load((int)$id);
        } else {
            throw new \Exception('Update operation was unsuccessfully');
        }
    }

    /**
     * Delete record from DB
     */
    public function delete($id) {
        //@TODO: Implement this
        // check for exsistense model in DB with specified id
        if ( empty(self::load($id)) ) {
            throw new \Exception('No data with current id in DB to delete');
        }

        $sql = 'DELETE FROM `' . $this->tableName . '` WHERE id=' . (int)$id;
        $this->dbo->setQuery($sql);
        return ['id' => $id];
    }

    /**
     * Get list of records
     *
     * @return array
     */
    public function getList() {
        $sql = 'SELECT * FROM `' . $this->tableName . '`';

        return $this->dbo->setQuery($sql)->getList(get_class($this));
    }

    /**
     * Get prepeared line to using in SQL request for UPDATE
     *
     * @return string builded sql
     */
    protected function pdoSet($data) {
        $set = "";
        foreach ($data as $column => $value) {
            $set .= $column . "=:" . $column . ", ";
        }
        return substr($set, 0, -2);
    }

}