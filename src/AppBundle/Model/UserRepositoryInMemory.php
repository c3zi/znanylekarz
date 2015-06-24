<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 24/06/15
 * Time: 22:43
 */

namespace AppBundle\Model;


class UserRepositoryInMemory implements UserRepositoryInterface
{
    private $users = [];

    public function __construct(array $users = [])
    {
        if ($users) {
            $this->appendUsers($users);
        }
    }

    /**
     * Adds new users.
     *
     * @param array $users
     */
    private function appendUsers(array $users)
    {
        foreach ($users as $user) {
            $this->users[$user->getId()] = $user;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(User $user)
    {
        if ($this->has($user)) {
            return $user;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(User $user)
    {
        $this->users[] = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user)
    {
        if ($this->has($user)) {
            $this->users[$user->getId()] = $user;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has(User $user)
    {
        return array_key_exists($user->getId(), $this->users);
    }
}