<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 23/06/15
 * Time: 18:33
 */

namespace AppBundle\Tests\Model;

use AppBundle\Tests\PrivateMethodTrait;
use AppBundle\Model\User;
use AppBundle\Model\UserRepositoryInMemory;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class UserRepositoryInMemoryTest extends \PHPUnit_Framework_TestCase
{
    use PrivateMethodTrait;

    private $users;

    public function setUp()
    {
        $this->users = [
            new User(1034, 'Rafael', 'rafael@example.com'),
            new User(1035, 'Donatello', 'donatello@example.com'),
            new User(1036, 'Michelangelo', 'michelangelo@example.com'),
            new User(1037, 'Leonardo', 'leonardo@example.com'),
        ];
    }

    /**
     * @test
     */
    public function getByIdShouldReturnOneElement()
    {
        $userRepository = new UserRepositoryInMemory($this->users);

        foreach ($this->users as $userObject) {
            $this->assertEquals($userObject, $userRepository->get($userObject));
        }
    }

    /**
     * @test
     */
    public function hasShouldCheckIfUserExists()
    {
        $userRepository = new UserRepositoryInMemory($this->users);

        $user1 = new User(1034, 'Rafael', 'rafael@example.com');
        $user2 = new User(1036, 'Michelangelo', 'michelangelo@example.com');
        $user3 = new User(1040, 'Bob', 'abc@example.com');

        $this->assertTrue($userRepository->has($user1));
        $this->assertTrue($userRepository->has($user2));
        $this->assertFalse($userRepository->has($user3));
    }

    /**
     * @test
     */
    public function saveShouldAddUser()
    {
        $userRepository = new UserRepositoryInMemory($this->users);

        $user1 = new User(6666, 'Bob', 'bob@example.com');
        $user2 = new User(6664, 'Ted', 'bob@example.com');
        $userRepository->save($user1);

        $users = \PHPUnit_Framework_Assert::readAttribute($userRepository, 'users');
        $this->assertTrue(in_array($user1, $users));
        $this->assertFalse(in_array($user2, $users));
    }

    /**
     * @test
     */
    public function updateShouldChangeExistedUser()
    {
        $userRepository = new UserRepositoryInMemory($this->users);
        $user1 = new User(1034, 'Rafael', 'newrafael@example.com');
        $userRepository->update($user1);

        $users = \PHPUnit_Framework_Assert::readAttribute($userRepository, 'users');
        $this->assertEquals($user1->getEmail(), $users[$user1->getId()]->getEmail());
    }

    public function fieldsProvider()
    {
        return [
            ['id', 'getId'],
            ['name', 'getName'],
            ['email', 'getEmail'],
        ];
    }

    public function paramsProvider()
    {
        return [
            ['id', 1034, 1],
            ['id', 1035, 1],
            ['id', 1037, 1],
            ['name', 'Michelangelo', 1],
            ['email', 'donatello@example.com', 1],
        ];
    }

//    public function paramsProvider()
//    {
//        return [
//            ['id', 1034],
//            ['']
//        ];
//    }
}