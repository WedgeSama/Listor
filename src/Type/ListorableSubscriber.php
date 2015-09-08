<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WS\Libraries\Listor\Event\ListorEvent;
use WS\Libraries\Listor\Events;
use WS\Libraries\Listor\ListorableInterface;

/**
 * ListorableSubscriber
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ListorableSubscriber implements EventSubscriberInterface
{
    public function parser(ListorEvent $event)
    {
        $target = $event->getTarget();

        if ($target instanceof ListorableInterface) {
            $event->setTarget($target->getListorable());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::TARGET_PARSE => array('parser', 1000),
        );
    }
}
