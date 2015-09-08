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
use Symfony\Component\Validator\Constraints\Type;

/**
 * SortPriorityType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class SortPriorityType extends AbstractType
{
    /**
     * @var string
     */
    private $parent;

    public function __construct($parent = 'hidden')
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data' => 0,
            'constraints' => array(
                new Type(array(
                    'type' => 'integer',
                )),
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
        return 'ws_listor_sort_priority';
    }
}
