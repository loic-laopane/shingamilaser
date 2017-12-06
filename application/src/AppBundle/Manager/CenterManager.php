<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Center;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CenterManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    private $repository;
    /**
     * @var SessionInterface
     */
    private $session;


    public function __construct(ObjectManager $manager, SessionInterface $session)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(Center::class);
        $this->session = $session;
    }

    /**
     * @param Center $center
     * @return mixed
     */
    public function exists(Center $center)
    {
        return $this->repository->findOneByCode($center->getCode());
    }

    /**
     * Insert Center in DB
     * @param Center $center
     * @return bool
     */
    public function insert(Center $center)
    {
        if($this->exists($center)) {
            throw new \Exception('alert.center.exists');
        }

        $this->manager->persist($center);
        $this->manager->flush();
    }

    public function delete($id)
    {
        $center = $this->repository->find($id);
        if (null === $center){
            throw new \Exception('This Center doesn\'t exist');
            return false;
        }

        $name = $center->getName();
        $this->manager->remove($center);
        $this->manager->flush();

        $this->session->getFlashBag()->add('success', 'Center '.$name.' has been deleted');

    }

}