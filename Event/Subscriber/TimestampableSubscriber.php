<?php
/**
 * (c) Johnny Cottereau <johnny.cottereau@gmail.com>
 */

namespace CoreBundle\Event\Subscriber;

use CoreBundle\Model\Interfaces\TimestampableInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TimestampableSubscriber implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate' => 'preUpdate',
            'prePersist' => 'prePersist',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof TimestampableInterface) {
            $now = new \DateTime('now');
            $object->setUpdatedAt($now);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof TimestampableInterface) {
            $now = new \DateTime('now');
            $object->setCreatedAt($now);
            $object->setUpdatedAt($now);
        }
    }
}