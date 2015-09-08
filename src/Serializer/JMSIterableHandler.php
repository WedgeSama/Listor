<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Bundle\ListorBundle\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use WS\Libraries\Listor\Iterable;

/**
 * JMSIterableHandler
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class JMSIterableHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'WS\Libraries\Listor\Iterable',
                'method' => 'serializeToJson',
            ),
        );
    }

    public function serializeToJson(JsonSerializationVisitor $visitor, Iterable $iterable, array $type, Context $context)
    {
        return $visitor->visitArray(array(
            'items' => $iterable->getItems(),
            'total' => (int) $iterable->getTotalItems(),
            'items_per_page' => $iterable->getItemsPerPage(),
            'current_page' => $iterable->getCurrentPage(),
        ), $type, $context);
    }
}
