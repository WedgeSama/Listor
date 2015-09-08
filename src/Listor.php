<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use WS\Libraries\Listor\Event\ListorEvent;
use WS\Libraries\Listor\Exception\ListorException;

/**
 * Listor
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class Listor implements ListorInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var OptionsResolverInterface
     */
    private $resolver;

    public function __construct(EventDispatcherInterface $dispatcher, OptionsResolverInterface $resolver)
    {
        $this->dispatcher = $dispatcher;
        $this->resolver =  $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($target, array $filters = array(), array $params = array())
    {
        $params = $this->resolver->resolveParameters($params);
        $filters = $this->resolver->resolveFilters($filters);

        /** @var ListorEvent $event */
        $event = $this->dispatcher->dispatch(Events::TARGET_PARSE, new ListorEvent($target, $params, $filters));

        if (!$event->isPropagationStopped()) {
            throw new ListorException(sprintf('The type "%s" is not handle by Listor.', get_class($event->getTarget())));
        }

        return new Iterable($event->getItems(), $event->count(), $params['currentPage'], $params['itemsPerPage']);
    }
}
