<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 24/06/15
 * Time: 22:43
 */

namespace AppBundle\Model;

interface UserRepositoryInterface
{
    /**
     * Gets an user from repository.
     *
     * @param User $user
     * @return User|null
     */
    public function get(User $user);

    /**
     * Saves an user in repository.
     *
     * @param User $user
     */
    public function save(User $user);

    /**
     * Updates an user in repository.
     *
     * @param User $user
     */
    public function update(User $user);

    /**
     * Checks if an user exists in repository.
     *
     * @param user $user
     * @return bool
     */
    public function has(user $user);
}