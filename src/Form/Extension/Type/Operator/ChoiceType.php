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

use WS\Libraries\Listor\Operations;

/**
 * ChoiceType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ChoiceType extends AbstractOperatorType
{
    /**
     * {@inheritdoc}
     */
    protected function getChoices()
    {
        return array(
            Operations::CHOICE_OR => 'filter.choice.or',
            Operations::CHOICE_AND => 'filter.choice.and',
            Operations::CHOICE_NAND => 'filter.choice.nand',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefault()
    {
        return Operations::CHOICE_OR;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor_operator_choice';
    }
}
