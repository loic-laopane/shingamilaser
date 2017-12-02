<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 01/12/2017
 * Time: 23:45
 */

namespace AppBundle\Command;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CreateAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this ->setName('app:create:admin')

          // the short description shown while running "php bin/console list"
          ->setDescription('Creates a new admin user')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp('This command allows you to create an admin user...')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $userManager = $container->get('AppBundle\Manager\UserManager');
        $encoder = $container->get('security.password_encoder');
        $mailer = $container->get('mailer');
        $validator = $container->get('validator');
        $helper = $this->getHelper('question');
        $question_username = new Question('Enter the username [admin] : ', 'admin');
        $question_email = new Question('Enter your email : ');
        $username = $helper->ask($input, $output, $question_username);
        $question_email->setValidator(function($answer) {
           if(filter_var($answer, FILTER_VALIDATE_EMAIL) == false)
           {
               throw new \RuntimeException('Bad email format');
           }
           return $answer;
        });
        $question_email->setMaxAttempts(3);
        $email = $helper->ask($input, $output, $question_email);

        $choices = ['y' => true, 'n' => false];
        $confirme_send_mail = new Question('Send password by mail (y/N) ?', 'n');
        $answer_sendmail = $helper->ask($input, $output, $confirme_send_mail);
        while(!preg_match('/^(y|n)$/i', $answer_sendmail))
        {
            $answer_sendmail = $helper->ask($input, $output, $confirme_send_mail);
        }


        $sendmail = $choices[$answer_sendmail];
        $password = substr(uniqid(), 0, 6);
        $user = new User();
        $user->setUsername($username)
            ->setPassword($encoder->encodePassword($user, $password))
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN']);

        $errors = $validator->validate($user);

        if(count($errors) > 0) {

        }

        $output->writeln('you choosed '.$username);
        $output->writeln('you choosed '.$email);
        if($sendmail) {
            $message = new \Swift_Message('Account Admin on ');
            $message->setFrom($container->getParameter('mailer_sender_address'))
                    ->setTo($email)
                    ->setBody("Account Admin\n\n
                    username: $username\n
                    password: $password", 'text/plain')
                    ;
            $mailer->send($message);
            $output->writeln('Password sent to '.$email);
        }

        $output->writeln('Account created '.strtolower($sendmail));
    }
}