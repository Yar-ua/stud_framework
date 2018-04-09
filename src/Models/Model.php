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

        $columns = self::setSQLColumns($data);
        $values = self::setSQLValues($data);

        $sql = 'INSERT INTO `' . $this->tableName .  '` (' . $columns . 
            ') VALUES (' . $values . ')';

        if ( $this->dbo->setQuery($sql) ) {
            return 'Insert to DB was successfully!';
        };
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
        $values = self::setSQLValues($data);
        $sql = 'UPDATE `' . $this->tableName .  '` SET ' . self::pdoSet($data) . 
            ' WHERE id=' . $id;

        if ( $this->dbo->setQuery($sql) ) {
            return 'Update to DB was successfully!';
        };
    }

    /**
     * Delete record from DB
     */
    public function delete($id) {
        //@TODO: Implement this
        $sql = 'DELETE FROM `' . $this->tableName . '` WHERE id=' . $id;
        if ( $this->dbo->setQuery($sql) ) {
            return 'Item successfully deleted from DB';
        } else {
            return 'Data with specified id not found';
        };

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
     * Get string of key's array columns
     *
     * @return string
     */
    protected function setSQLColumns($data): string {
        return implode(", ", array_keys($data));
    }
    
    /**
     * Get string of value's array columns
     *
     * @return string
     */
    protected function setSQLValues($data): string {
        // to building sql we need quotes around the values
        $values = [];
        foreach (array_values($data) as $value) {
            array_push($values, "'" . $value . "'");
        }
        return implode(", ", array_values($values));
    }

    /**
     * Get prepeared line to using in SQL request for UPDATE
     *
     * @return string
     */
    protected function pdoSet($data) {
        $set = "";
        foreach ($data as $column => $value) {
            $set .= $column . "='" . $value . "', ";
        }
        return substr($set, 0, -2);
    }

}