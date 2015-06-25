<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 25/06/15
 * Time: 16:12
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Exception\InvalidFormException;
use AppBundle\Model\Exception\UserDoesNotExist;

class ChangeUserEmailCommand extends ContainerAwareCommand
{
    public static $name = 'docplanner:user:email_update';
    protected function configure()
    {
        $this
            ->setName(self::$name)
            ->setDescription("This command updates user's email.")
            ->addOption(
                'id',
                null,
                InputOption::VALUE_REQUIRED,
                'User identifier'
            )
            ->addOption(
                'email',
                null,
                InputOption::VALUE_REQUIRED,
                'New email value'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getOption('id');
        $email = $input->getOption('email');

        if (!$id || !$email) {
            $output->writeln('You must fill Id and Email options.');
        }

        $userHandler = $this->getContainer()->get('app.user.handler');

        try {
            $user = $userHandler->get($id);
        } catch (UserDoesNotExist $ex) {
            $output->writeln($ex->getMessage());
            return 1;
        }

        try {
            $userHandler->update($user, ['email' => $email]);
            $output->writeln(sprintf("User '%s' has been updated.", $email));
        } catch (InvalidFormException $ex) {
            $output->writeln($ex->getMessage());
        }
    }
}