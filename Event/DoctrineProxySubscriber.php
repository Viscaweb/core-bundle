<?php

namespace Visca\Bundle\CoreBundle\Event;

use Doctrine\Common\Persistence\Proxy;
use Doctrine\ODM\MongoDB\PersistentCollection as MongoDBPersistentCollection;
use Doctrine\ODM\PHPCR\PersistentCollection as PHPCRPersistentCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Proxy\Proxy as ORMProxy;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

/**
 * Class DoctrineProxySubscriber.
 */
class DoctrineProxySubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerialize',
            ],
        ];
    }

    /**
     * @param PreSerializeEvent $event
     */
    public function onPreSerialize(PreSerializeEvent $event)
    {
        $object = $event->getObject();
        $type = $event->getType();

        // If the set type name is not an actual class,
        // but a faked type for which a custom handler exists, we do not
        // modify it with this subscriber.
        // Also, we forgo autoloading here as an instance of this type is already created,
        // so it must be loaded if its a real class.
        $virtualType = !class_exists($type['name'], false);

        if ($object instanceof PersistentCollection
            || $object instanceof MongoDBPersistentCollection
            || $object instanceof PHPCRPersistentCollection
        ) {
            if (!$virtualType) {
                $event->setType('ArrayCollection');
            }

            return;
        }

        if (!$object instanceof Proxy && !$object instanceof ORMProxy) {
            return;
        }

        if (!$virtualType) {
            $event->setType(get_parent_class($object));
        }
    }
}
