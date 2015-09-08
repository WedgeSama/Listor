<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Form\Extension\Type\Operator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * AbstractOperatorType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
abstract class AbstractOperatorType extends AbstractType
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
            'data' => $this->getDefault(),
            'required' => true,
            'choices' => $this->getChoices(),
            'error_bubbling' => true,
            'constraints' => array(
                new Choice(array(
                    'choices' => array_keys($this->getChoices()),
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
     * @return array
     */
    abstract protected function getChoices();

    /**
     * @return integer
     */
    abstract protected function getDefault();
}
