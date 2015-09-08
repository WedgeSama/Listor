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
 * TextType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class TextType extends AbstractOperatorType
{
    /**
     * {@inheritdoc}
     */
    protected function getChoices()
    {
        return array(
            Operations::TEXT_LIKE => 'filter.text.like',
            Operations::TEXT_NOT_LIKE => 'filter.text.notLike',
            Operations::TEXT_EQUAL => 'filter.text.equal',
            Operations::TEXT_NOT_EQUAL => 'filter.text.notEqual',
            Operations::TEXT_START_BY => 'filter.text.startBy',
            Operations::TEXT_END_BY => 'filter.text.endBy',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefault()
    {
        return Operations::TEXT_LIKE;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor_operator_text';
    }
}
