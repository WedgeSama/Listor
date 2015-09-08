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

use Symfony\Component\HttpFoundation\Request;

/**
 * ListorRequestInterface
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
interface ListorRequestInterface
{
    /**
     * @param mixed $target
     * @param Request $request
     * @param string|\Symfony\Component\Form\FormTypeInterface $formType
     * @return \WS\Libraries\Listor\IterableInterface
     */
    public function execute($target, Request $request, $formType = 'ws_listor');

    /**
     * @param string $name
     * @param mixed $target
     * @param Request $request
     * @param string|\Symfony\Component\Form\FormTypeInterface $formType
     * @return \WS\Libraries\Listor\IterableInterface
     */
    public function executeNamed($name, $target, Request $request, $formType = 'ws_listor');
}
