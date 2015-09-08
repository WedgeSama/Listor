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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;

/**
 * ListorType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ListorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['items_per_page']) {
            $builder->add('items_per_page', 'ws_listor_items_per_page', $options['items_per_page_options']);
        }

        if ($options['current']) {
            $builder->add('current_page', 'text', array(
                'data' => 0,
                'constraints' => array(
                    new Type(array(
                        'type' => 'integer',
                    )),
                ),
            ));
        }

        if ($options['submit']) {
            $builder->add('submit', 'submit');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'GET',
            'items_per_page' => true,
            'items_per_page_options' => array(),
            'current' => false,
            'submit' => true,
            'csrf_protection' => false,
        ));

        $resolver->setAllowedTypes('items_per_page_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor';
    }
}
