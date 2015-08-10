<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor;

/**
 * List all sorting and filtering operations types.
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
final class Operations
{
    const TEXT_EQUAL = 0;

    const TEXT_NOT_EQUAL = 1;

    const TEXT_LIKE = 2;

    const TEXT_NOT_LIKE = 3;

    const TEXT_START_BY = 4;

    const TEXT_END_BY = 5;

    const DATETIME_EXACT = 10;

    const DATETIME_BETWEEN = 11;

    const DATETIME_BEFORE = 12;

    const DATETIME_AFTER = 13;

    const CHOICE_AND = 20;

    const CHOICE_OR = 21;

    const CHOICE_NAND = 22;

    /**
     * Get all Text operators.
     * 
     * @return array
     */
    public static function getTextOperators()
    {
        return array(
            self::TEXT_EQUAL,
            self::TEXT_NOT_EQUAL,
            self::TEXT_LIKE,
            self::TEXT_NOT_LIKE,
            self::TEXT_START_BY,
            self::TEXT_END_BY,
        );
    }

    /**
     * Get all Choice operators.
     *
     * @return array
     */
    public static function getChoiceOperators()
    {
        return array(
            self::CHOICE_AND,
            self::CHOICE_OR,
            self::CHOICE_NAND,
        );
    }

    /**
     * Get all DateTime operators.
     *
     * @return array
     */
    public static function getDateTimeOperators()
    {
        return array(
            self::DATETIME_BETWEEN,
            self::DATETIME_EXACT,
            self::DATETIME_BEFORE,
            self::DATETIME_AFTER,
        );
    }
}
