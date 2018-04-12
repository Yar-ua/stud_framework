<?php

namespace Mindk\Framework\DB;

/**
 * Interface DBOConnectorInterface
 * @package Mindk\Framework\DB
 */
interface DBOConnectorInterface
{
    /**
     * Set sql query
     *
     * @param $sql
     * @return mixed
     */
    public function setQuery($sql);

    /**
     * Get single row result
     *
     * @param $target
     * @return mixed
     */
    public function getResult(&$target);

    /**
     * Get list of resulting rows
     *
     * @param string $targetClass
     * @return mixed
     */
    public function getList($targetClass = '\stdClass');

    /**
     * Get list of resulting rows
     *
     * @param string builded SQL, array values
     * @return bool true/false
     */
    public function insertQuery($sql, $values);
}