<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 25/06/15
 * Time: 10:58
 */

namespace AppBundle\Tests\Handler;

use AppBundle\Handler\UserHandler;
use AppBundle\Model\User;
use AppBundle\Model\UserRepositoryInMemory;

class UserHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $repository;

    private $formFactory;

    private $eventDispatcher;

    private $logger;

    public function setUp()
    {
        $this->users = [
            new User(1034, 'Rafael', 'rafael@example.com'),
            new User(1035, 'Donatello', 'donatello@example.com'),
            new User(1036, 'Michelangelo', 'michelangelo@example.com'),
            new User(1037, 'Leonardo', 'leonardo@example.com'),
        ];

        $this->repository = new UserRepositoryInMemory($this->users);
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');
        $this->userHandler = new UserHandler($this->repository, $this->formFactory, $this->eventDispatcher, $this->logger);
    }

    /**
     * @test
     */
    public function getShouldReturnObject()
    {
        $id = 1034;
        $this->assertEquals($this->userHandler->get($id), $this->repository->get($id));
    }

    /**
     * @test
     *
     * @expectedException \AppBundle\Model\Exception\UserDoesNotExist
     */
    public function getShouldThrowExceptionIfUserDoesNotExist()
    {
        $id = 6666;
        $this->userHandler->get($id);
    }

    /**
     * @test
     */
    public function updateShouldModifyExistedUser()
    {
        $id = 1034;

        $user = new User(1035, 'Donatello', 'donatello@example.com');
        $parameters = ['email' => 'test@example.com'];

        $user->setEmail($parameters['email']);

        $form = $this->getMock('Symfony\Component\Form\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($user));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->userHandler = new UserHandler($this->repository, $this->formFactory, $this->eventDispatcher, $this->logger);
        $userObject = $this->userHandler->update($user, $parameters);

        $this->assertEquals($userObject, $user);
    }

    /**
     * @test
     *
     * @expectedException \AppBundle\Exception\InvalidFormException
     */
    public function updateShouldBeInvalidForEmptyEmail()
    {
        $id = 1034;

        $user = new User(1035, 'Donatello', 'donatello@example.com');
        $parameters = ['email' => ''];

        $user->setEmail($parameters['email']);

        $form = $this->getMock('Symfony\Component\Form\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->userHandler = new UserHandler($this->repository, $this->formFactory, $this->eventDispatcher, $this->logger);
        $this->userHandler->update($user, $parameters);
    }
}