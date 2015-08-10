<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor;

/**
 * ListorInterface
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
interface ListorInterface
{
    /**
     * Listor target.
     *
     * @param mixed $target
     * @param array $filters
     * @param array $params
     * @return IterableInterface
     */
    public function execute($target, array $filters = array(), array $params = array());
}
