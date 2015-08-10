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
 * IterableInterface
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
interface IterableInterface extends \Countable, \Iterator, \ArrayAccess
{
    /**
     * @return array
     */
    public function getItems();

    /**
     * @return integer
     */
    public function getTotalItems();

    /**
     * @return integer
     */
    public function getCurrentPage();

    /**
     * @return integer
     */
    public function getItemsPerPage();

    /**
     * @return integer
     */
    public function getTotalPages();
}
