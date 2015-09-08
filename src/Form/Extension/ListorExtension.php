<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Form\Extension;

use Symfony\Component\Form\AbstractExtension;

/**
 * ListorExtension
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ListorExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $parents;

    private $itemsPerPage;

    public function __construct(array $parents = array(), array $itemsPerPage = array())
    {
        $this->parents = array_merge(array(
            'items_per_page' => 'choice',
            'sort' => 'choice',
            'sort_priority' => 'hidden',
            'text' => 'choice',
            'choice' => 'choice',
            'datetime' => 'choice',
        ), $parents);

        $this->itemsPerPage = array_merge(array(
            'default' => 10,
            'choices' => array(
                1, 2, 5, 10, 25, 50, 100, 250,
            ),
        ), $itemsPerPage);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadTypes()
    {
        return array(
            new Type\Core\ListorType(),
            new Type\Core\ItemsPerPageType($this->itemsPerPage['default'], $this->itemsPerPage['choices'], $this->parents['items_per_page']),
            new Type\Filter\BaseType(),
            new Type\Filter\ChoiceType(),
            new Type\Filter\DateTimeType(),
            new Type\Filter\TextType(),
            new Type\Sort\SortType($this->parents['sort']),
            new Type\Sort\SortPriorityType($this->parents['sort_priority']),
            new Type\Operator\TextType($this->parents['text']),
            new Type\Operator\ChoiceType($this->parents['choice']),
            new Type\Operator\DateTimeType($this->parents['datetime']),
        );
    }
}
