<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 26/11/2017
 * Time: 16:13
 */

namespace AppBundle\Event\Subscriber;


use AppBundle\Entity\Image;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UploadAvatarSubscriber implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return array(
          'prePersist',
          'preUpdate',
          'postPersist',
          'postUpdate',
          'preRemove',
        );
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->hydrate($eventArgs);
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->hydrate($eventArgs);
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $this->upload($eventArgs);
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->upload($eventArgs);
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof Image) {
            $fileToRemove = $entity->getUploadedDir().'/'.$entity->getFilename();
            if(file_exists($fileToRemove)) unlink($fileToRemove);
        }

    }

    public function hydrate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof Image) {
            $filename = uniqid().'.'.$entity->getFile()->guessExtension();
            $entity->setName($entity->getFile()->getClientOriginalName());
            $entity->setFilename($filename);
            $entity->setExtension($entity->getFile()->guessExtension());
        }


    }

    public function upload(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        // perhaps you only want to act on some "Image" entity
        if ($entity instanceof Image) {
            //$entityManager = $eventArgs->getEntityManager();

            $entity->getFile()->move($entity->getUploadedDir(), $entity->getFilename());

            if(null !== $entity->getTmpFilename())
            {
                if(file_exists($entity->getTmpFilename())) unlink($entity->getTmpFilename());
            }

        }
    }



}