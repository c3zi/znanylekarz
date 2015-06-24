<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 24/06/15
 * Time: 23:00
 */

namespace AppBundle\Model;


class UserManager
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(User $user)
    {
        $this->userRepository->update($user);
    }
}