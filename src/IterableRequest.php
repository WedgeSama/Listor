<?php
/*
 * This file is part of the www package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * IterableRequest
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class IterableRequest extends Iterable implements IterableRequestInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var FormInterface
     */
    private $form;

    public function __construct(Request $request, FormInterface $form, array $items, $totalItems, $currentPage = 0, $itemsPerPage = 10)
    {
        parent::__construct($items, $totalItems, $currentPage, $itemsPerPage);
        $this->request = $request;
        $this->form = $form;
    }

    /**
     * Create a IterableRequest from another IterableInterface instance.
     *
     * @param IterableInterface $iterable
     * @param Request $request
     * @param FormInterface $form
     * @return IterableRequest
     */
    public static function fromBaseIterable(IterableInterface $iterable, Request $request, FormInterface $form)
    {
        return new self($request, $form, $iterable->getItems(), $iterable->getTotalItems(), $iterable->getCurrentPage(), $iterable->getItemsPerPage());
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->form->createView();
    }
}
