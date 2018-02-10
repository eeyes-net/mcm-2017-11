<?php
/**
 * Created by PhpStorm.
 * User: Ganlv
 * Date: 2018/2/10
 * Time: 16:20
 */

namespace App\Http\Middleware;


use Illuminate\Auth\AuthenticationException;

class Authenticate extends \Illuminate\Auth\Middleware\Authenticate
{
    /**
     * @param array $guards
     * @throws AuthenticationException
     */
    protected function authenticate(array $guards)
    {
        try {
            parent::authenticate($guards);
        } catch (AuthenticationException $exception) {
            throw new AuthenticationException('请先登录。', $exception->guards());
        }
    }
}