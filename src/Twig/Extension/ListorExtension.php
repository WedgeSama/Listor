<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Twig\Extension;

use WS\Libraries\Listor\IterableInterface;

/**
 * ListorExtension
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ListorExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    private $pagination;

    private $sort;

    public function __construct(array $pagination, array $sort)
    {
        $this->pagination = $pagination;
        $this->sort = $sort;
    }

    /**
     * {@inheritDoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'ws_listor_paginate' => new \Twig_Function_Method($this, 'paginate', array('is_safe' => array('html'))),
            'ws_listor_sort' => new \Twig_Function_Method($this, 'sort', array('is_safe' => array('html'))),
        );
    }

    /**
     * @param IterableInterface $iterable
     * @return string
     */
    public function paginate(IterableInterface $iterable)
    {
        return $this->environment->render($this->pagination['template'], array(
            'last_page' => $iterable->getTotalPages()-1,
            'current_page' => $iterable->getCurrentPage(),
            'query' => $iterable->getQuery(),
        ));
    }

    /**
     * @param IterableInterface $iterable
     * @param string $field
     * @param string $direction asc or desc
     * @param null $label
     * @return string
     */
    public function sort(IterableInterface $iterable, $field, $direction = 'asc', $label = null)
    {
        return $this->environment->render($this->sort['template'], array(
            'label' => $label,
            'field' => $field,
            'direction' => (strtolower($direction)=='desc')?'desc':'asc',
            'query' => $iterable->getQuery(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor';
    }
}
