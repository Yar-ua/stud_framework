<?php
/**
 * Created by PhpStorm.
 * User: dimmask
 * Date: 11.04.18
 * Time: 20:10
 */

namespace Mindk\Framework\Models;

/**
 * Class UserModel
 * @package Mindk\Framework\Models
 */
class UserModel extends Model
{
    /**
     * @var string  DB Table name
     */
    protected $tableName = 'users';

    /**
     * Find user by credentials
     *
     * @param $login
     * @param $password
     *
     * @return mixed
     */
    public function findByCredentials($login, $password){
        $sql = sprintf("SELECT * FROM `%s` WHERE `email`='%s' AND `password`='%s'", $this->tableName, $login, md5($password));

        return $this->dbo->setQuery($sql)->getResult($this);
    }

    /**
     * Find user by access token
     *
     * @param $token
     *
     * @return mixed
     */
    public function findByToken($token){
        $token = filter_var($token, FILTER_SANITIZE_STRING);
        $sql = sprintf("SELECT * FROM `%s` WHERE `token`='%s'", $this->tableName, $token);

        return $this->dbo->setQuery($sql)->getResult($this);
    }

    /**
     * Check, if user with current login exists in DB
     * 
     * @param $user->login
     *
     * @return mixed
     */
    public function isUniqueLogin($login){
        $sql = sprintf("SELECT * FROM `users` WHERE `email`='%s'", $login);
        $result = $this->dbo->setQuery($sql)->getResult($this);
        return $result;
    }


    /**
     * Create User in DB
     *
     * @param array with $user's login, password, token, role
     *
     * @return bool from parent function create (parent is Model)
     */
    public function create($data) {
        // Using method create(), what was implemented in base model (Model)
        // Try to follow DRY
        return parent::create($data);
    }

    /**
     * Update User or User's token in DB
     *
     * @param array with $user->token
     *
     * @return bool from parent function save (parent is Model)
     */
    public function save($id, $data) {
        // Using method save(), what was implemented in base model (Model)
        return parent::save($id, $data);
    }
}