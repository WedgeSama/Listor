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
 * ListorableInterface
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
interface ListorableInterface
{
    /**
     * Return something Listor can use.
     *
     * @return mixed
     */
    public function getListorable();
}
