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

        if (!$users) {
            $users = $this->initWithDefaultUsers();
        }

        $this->appendUsers($users);
    }

    private function initWithDefaultUsers()
    {
        return [
            new User(1, 'Rafael', 'rafael@example.com'),
            new User(2, 'Donatello', 'donatello@example.com'),
            new User(3, 'Michelangelo', 'michelangelo@example.com'),
            new User(4, 'Leonardo', 'leonardo@example.com'),
        ];
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
    public function get($id)
    {
        if (array_key_exists($id, $this->users)) {
            return $this->users[$id];
        }

    }

    /**
     * {@inheritdoc}
     */
    public function save(User $user)
    {
        $this->users[] = $user;
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user)
    {
        $this->users[$user->getId()] = $user;
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function has(User $user)
    {
        return array_key_exists($user->getId(), $this->users);
    }
}