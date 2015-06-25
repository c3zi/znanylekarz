<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 25/06/15
 * Time: 10:26
 */

namespace AppBundle\Handler;

use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Model\User;
use AppBundle\Model\UserRepositoryInterface;
use AppBundle\Form\UserType;
use AppBundle\Exception\InvalidFormException;
use AppBundle\Model\Exception\UserDoesNotExist;

class UserHandler implements UserHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        FormFactoryInterface $formFactory,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    )
    {
        $this->userRepository = $userRepository;
        $this->formFactory = $formFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * @param int $id
     * @return User
     * @throws UserDoesNotExist
     */
    public function get($id)
    {
        $user = $this->userRepository->get($id);

        if (!$user) {
            throw new UserDoesNotExist(sprintf('User \'%s\' does not exist.', $id));
        }

        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function update(User $user, array $parameters)
    {
        $user = $this->processForm($user, $parameters, 'PUT');
        $this->logger->info(sprintf("User '%s' has been updated", $user->getEmail()));

        return $user;
    }

    /**
     * @param array $parameters
     * @return User
     */
    public function save(array $parameters)
    {
        $userSkeleton = $this->createUser();
        $user = $this->processForm($userSkeleton, $parameters, 'POST');
        $this->logger->info(sprintf("User '%s' has been added", $user->getEmail()));

        return $user;
    }

    /**
     * Process User form.
     *
     * @param User $user
     * @param array $parameters
     * @param string $method
     * @return User
     * @throws InvalidFormException
     */
    private function processForm(User $user, array $parameters, $method = 'POST')
    {
        $form = $this->formFactory->create(new UserType(), $user, array('method' => $method));
        $form->submit($parameters, 'PUT' !== $method);

        if ($form->isValid()) {
            $user = $form->getData();
            $this->userRepository->update($user);
            $this->eventDispatcher->dispatch('user.post_update', new Event($user));

            return $user;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createUser()
    {
        return new User(User::generateId());
    }

}