<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Form\Extension\Type\Core;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * ItemsPerPageType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ItemsPerPageType extends AbstractType
{
    /**
     * @var string
     */
    private $parent;

    /**
     * @var array
     */
    private $choices;

    /**
     * @var integer
     */
    private $default;

    public function __construct($default = 10, array $choices = array(), $parent = 'choice')
    {
        $this->parent = $parent;
        $this->choices = $choices;
        $this->default = $default;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' =>  $this->choices,
            'data' => $this->default,
            'constraints' => array(
                new Choice(array(
                    'choices' => $this->choices,
                )),
            )
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
        return 'ws_listor_items_per_page';
    }
}
