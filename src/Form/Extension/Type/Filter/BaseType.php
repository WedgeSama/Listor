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
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * BaseType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class BaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['sort']) {
            $builder
                ->add('sort', 'ws_listor_sort')
                ->add('sort_priority', 'ws_listor_sort_priority');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'operator' => true,
            'sort' => true,
            'type' => null,
            'value_options' => array(),
        ));

        $resolver->setAllowedTypes('value_options', 'array');

        $resolver->setNormalizer('type', function(Options $options, $value) {
            if ($options['operator'] == true && $value === null) {
                throw new MissingOptionsException(sprintf('The required option "type" is missing.'));
            }

            return $value;
        });

        $resolver->setNormalizer('value_options', function(Options $options, $value) {
            return array_merge(array(
                'required' => false,
            ), $value);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor_filter_base';
    }
}
