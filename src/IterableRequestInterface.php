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
 * IterableRequestInterface
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
interface IterableRequestInterface extends IterableInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest();

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getForm();
}
