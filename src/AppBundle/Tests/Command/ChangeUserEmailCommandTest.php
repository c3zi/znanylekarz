<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 25/06/15
 * Time: 16:34
 */

namespace AppBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use AppBundle\Command\ChangeUserEmailCommand;

class ChangeUserEmailCommandTest extends WebTestCase
{

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    /**
     * @test
     */
    public function executeWithValidData()
    {
        $application = new Application(static::$kernel);
        $application->add(new ChangeUserEmailCommand());

        $command = $application->find(ChangeUserEmailCommand::$name);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--email' => 'new@example.com',
            '--id' => 1
        ]);

        $this->assertRegExp('/has been updated./', $commandTester->getDisplay());
    }

    /**
     * @test
     */
    public function executeWithNonValidEmail()
    {
        $application = new Application(static::$kernel);
        $application->add(new ChangeUserEmailCommand());

        $command = $application->find(ChangeUserEmailCommand::$name);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--email' => 'new',
            '--id' => 1
        ]);

        $this->assertRegExp('/Invalid submitted data/', $commandTester->getDisplay());
    }

    /**
     * @test
     */
    public function executeWithUnexistedUser()
    {
        $application = new Application(static::$kernel);
        $application->add(new ChangeUserEmailCommand());

        $command = $application->find(ChangeUserEmailCommand::$name);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--email' => 'new@example.com',
            '--id' => 828282
        ]);

        $this->assertRegExp('/does not exist./', $commandTester->getDisplay());
    }
}