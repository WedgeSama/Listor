<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Event;

use Symfony\Component\EventDispatcher\Event;
use WS\Libraries\Listor\Exception\ListorException;

/**
 * ListorEvent
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ListorEvent extends Event
{
    /**
     * @var mixed
     */
    private $target;

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var mixed
     */
    private $items;

    /**
     * @var integer
     */
    private $count;
    
    public function __construct($target, array $params, array $filters = array())
    {
        if ($target === null) {
            throw new ListorException(sprintf('Target cannot be null.'));
        }

        $this->items = array();
        $this->params = $params;
        $this->target = $target;
        $this->filters = $filters;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     * @return self
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     * @return self
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return self
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @param string $field
     */
    public function filterParsed($field)
    {
        $this->filters[$field]['parsed'] = true;
    }
}
