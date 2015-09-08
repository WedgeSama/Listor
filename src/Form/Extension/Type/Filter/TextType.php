<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Form\Extension\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TextType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class TextType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['operator']) {
            $builder->add('operator', 'ws_listor_operator_text')
                ->add('value', $options['type'], $options['value_options']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'text',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'ws_listor_filter_base';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor_filter_text';
    }
}
