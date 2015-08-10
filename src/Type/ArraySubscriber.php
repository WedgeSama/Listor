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

/**
 * ArraySubscriber
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ArraySubscriber implements EventSubscriberInterface
{
    public function parser(ListorEvent $event)
    {
        $target = $event->getTarget();

        if ($target instanceof \ArrayObject) {
            $target = $target->getArrayCopy();
        }

        if (is_array($target)) {
            // TODO Sort

            // TODO Filter

            $params = $event->getParams();
            // Pagination.
            $event->setCount(count($target));

            $event->setItems(array_slice(
                $target,
                $params['currentPage']*$params['itemsPerPage'],
                $params['itemsPerPage']
            ));

            $event->stopPropagation();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'ws_listor.target_parser' => array('parser', -10),
        );
    }
}
