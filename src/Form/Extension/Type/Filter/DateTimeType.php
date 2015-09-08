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
 * DateTimeType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class DateTimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['operator']) {
            $builder->add('operator', 'ws_listor_operator_datetime');

            switch ($options['valid']) {
                case 'date':
                    $class = 'Symfony\Component\Validator\Constraints\Date';
                    break;
                case 'time':
                    $class = 'Symfony\Component\Validator\Constraints\Time';
                    break;
                default:
                    $class = 'Symfony\Component\Validator\Constraints\DateTime';
            }

            $value = $builder->create('value', 'ws_listor_groupable')
                ->add('start', $options['type'], array_merge(array(
                    'constraints' => new $class,
                ), $options['value_options']))
                ->add('end', $options['type'], array_merge(array(
                    'constraints' => new $class,
                ), $options['value_options']));

            $builder->add($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'datetime',
            'valid' => 'datetime',
        ));

        $resolver->setAllowedValues('valid', array(
            'datetime',
            'date',
            'time',
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
        return 'ws_listor_filter_datetime';
    }
}
