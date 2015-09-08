<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Form\Extension\Type\Sort;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SortType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class SortType extends AbstractType
{
    /**
     * @var string
     */
    private $parent;

    public function __construct($parent = 'choice')
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' =>  array(
                'asc' => 'sort.asc',
                'desc' => 'sort.desc',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor_sort';
    }
}
