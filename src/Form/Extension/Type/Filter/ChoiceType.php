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
use Symfony\Component\Validator\Constraints\Choice;
use WS\Libraries\Listor\Operations;

/**
 * ChoiceType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ChoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['operator']) {
            if ($options['multiple']) {
                $type = 'ws_listor_operator_choice';
                $operatorOptions = array();
            } else {
                $type = 'hidden';
                $operatorOptions = array(
                    'data' => Operations::CHOICE_OR,
                    'constraints' => array(
                        new Choice(array(
                            'choices' => array(Operations::CHOICE_OR),
                        )),
                    ),
                );
            }

            $builder
                ->add('operator', $type, $operatorOptions)
                ->add('value', $options['type'], $options['value_options']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'choice',
            'multiple' => true,
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
        return 'ws_listor_filter_choice';
    }
}
