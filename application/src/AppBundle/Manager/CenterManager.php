<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Center;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CenterManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    private $repository;

    /**
     * CenterManager constructor.
     * @param ObjectManager $manager
     * @param SessionInterface $session
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(Center::class);
    }

    /**
     * @param Center $center
     * @return mixed
     */
    public function exists(Center $center)
    {
        return $this->repository->findOneBy(['code' => $center->getCode()]);
    }

    /**
     * @param Center $center
     * @return $this
     * @throws \Exception
     */
    public function insert(Center $center)
    {
        if ($this->exists($center)) {
            throw new \Exception('alert.center.exists');
        }

        $this->manager->persist($center);
        $this->manager->flush();

        return $this;
    }

    /**
     * @param $id
     * @return $this
     * @throws \Exception
     */
    public function delete($id)
    {
        $center = $this->repository->find($id);
        if (null === $center) {
            throw new \Exception('This Center doesn\'t exist');
        }

        $name = $center->getName();
        $this->manager->remove($center);
        $this->manager->flush();

        return $this;
    }
}
