<?php

namespace Mindk\Framework\Controllers;

use Mindk\Framework\Exceptions\AuthRequiredException;
use Mindk\Framework\Http\Request\Request;
use Mindk\Framework\Models\UserModel;
use Mindk\Framework\DB\DBOConnectorInterface;

/**
 * Class UserController
 * @package Mindk\Framework\Controllers
 */
class UserController
{
    /**
     * Register through action
     *
     * @param Request $request
     * @param UserModel $model
     *
     * @return mixed
     * @throws AuthRequiredException
     */
    public function register(Request $request, UserModel $model, DBOConnectorInterface $dbo) {
        //@TODO: Implement
        $user = new UserModel($dbo);
        $user->login = $request->get('login', '', 'string');
        $user->password = $request->get('password', '', 'string');
        
        // Light validation, what login and password exists
        if(empty($user->login) or empty($user->password)) {
            throw new AuthRequiredException('No login or password for registration');
        }
        // Check for login dublicate in DB
        if ($user->isUniqueLogin($user->login)) {
            throw new AuthRequiredException('User with current login alredy exists, try another login');
        }
        // Set default registred user role
        if (empty($request->get('role', '', 'string'))) {
            $user->role = 'user';
        }

        $user->token = md5(uniqid());
        $data = array('email' => $user->login, 'password' => md5($user->password), 'token' => $user->token, 'role' => $user->role);

        if ($user->create($data)) {
            return $user->token;
        } else {
            throw new AuthRequiredException('Registration unsuccessfully, write to DB aborted');
        }
    }

    /**
     * Login through action
     *
     * @param Request $request
     * @param UserModel $model
     *
     * @return mixed
     * @throws AuthRequiredException
     */
    public function login(Request $request, UserModel $model, DBOConnectorInterface $dbo) {

        if($login = $request->get('login', '', 'string')) {

            $user = $model->findByCredentials($login, $request->get('password', ''));
        }

        if(empty($user)) {
            throw new AuthRequiredException('Bad access credentials provided');
        }

        // Generate new access token and save:
        $user->token = md5(uniqid());
        // prepeare array with data, and insert to DB
        $data = array('token' => $user->token);
        $user->save($user->id, $data);

        return $user->token;
    }

    /**
     * Logout through action
     *
     * @param Request $request
     * @param UserModel $model
     *
     * @return mixed
     * @throws AuthRequiredException
     */
    public function logout(Request $request, UserModel $model, DBOConnectorInterface $dbo) {
        //@TODO: Implement
        $user = $model->findByToken($request->getHeader('X-Auth'));
        if ( $user->save($user->id, array('token' => 'null')) ) {
            unset($user);
            return true;
        } else {
            throw new AuthRequiredException("Logout error, can't logout user with current token");
        }
    }
}