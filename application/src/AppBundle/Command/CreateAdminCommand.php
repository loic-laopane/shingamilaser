<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 01/12/2017
 * Time: 23:45
 */

namespace AppBundle\Command;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ValidatorBuilderInterface;

class CreateAdminCommand extends ContainerAwareCommand
{
    private $container;
    /**
     * @var null
     */
    private $name;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var ValidatorBuilderInterface
     */
    private $validator;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
                                $name = null,
                                ObjectManager $objectManager,
                                UserManager $userManager,
                                \Swift_Mailer $mailer
    ) {
        parent::__construct($name);
        $this->name = $name;
        $this->objectManager = $objectManager;
        $this->mailer = $mailer;
        $this->userManager = $userManager;
    }

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
        //Container
        $container = $this->getContainer();

        //Services
        $helper = $this->getHelper('question');

        $output->writeln($container->getParameter('app_name').' Admin Creator
        ==========');

        //Definition des questions
        $question_username = new Question('Enter the username [admin] : ', 'admin');
        $question_email = new Question('Enter your email : ');
        $question_email->setValidator(function ($answer) {
            if (filter_var(trim($answer), FILTER_VALIDATE_EMAIL) == false) {
                throw new \RuntimeException('Bad email format');
            }
            return $answer;
        });
        $question_email->setMaxAttempts(3);


        //Recuperation des reponse
        $username = $helper->ask($input, $output, $question_username);
        $email = $helper->ask($input, $output, $question_email);
        $password = substr(uniqid(), 0, 6);

        //Creation d'un user UserInterface de type admin
        $user = new User();
        $user->setUsername($username)
            ->setPassword($password)
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN']);

        //On enregister l'user validé
        try {
            $this->userManager->insert($user);
            $output->writeln('Username: '.$username);
            $output->writeln('Password: '.$password);
            $output->writeln('Account created !');
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            $output->writeln('Account not created !');
        }
    }
}
