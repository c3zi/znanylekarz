<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 25/06/15
 * Time: 10:33
 */

namespace AppBundle\Handler;

use AppBundle\Model\User;

interface UserHandlerInterface
{
    /**
     * Get an User given the identifier.
     *
     * @param $id
     * @return User
     */
    public function get($id);

    /**
     * Edit an User.
     *
     * @param User $user
     * @param array $parameters
     * @return User
     */
    public function update(User $user, array $parameters);

    /**
     * Save an User.
     *
     * @param array $parameters
     * @return User
     */
    public function save(array $parameters);
}