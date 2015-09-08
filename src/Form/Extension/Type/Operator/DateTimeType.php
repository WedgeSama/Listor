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
 * DateTimeType
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class DateTimeType extends AbstractOperatorType
{
    /**
     * {@inheritdoc}
     */
    protected function getChoices()
    {
        return array(
            Operations::DATETIME_EXACT => 'filter.datetime.exact',
            Operations::DATETIME_BETWEEN => 'filter.datetime.between',
            Operations::DATETIME_BEFORE => 'filter.datetime.before',
            Operations::DATETIME_AFTER => 'filter.datetime.after',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefault()
    {
        return Operations::DATETIME_BETWEEN;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ws_listor_operator_datetime';
    }
}
