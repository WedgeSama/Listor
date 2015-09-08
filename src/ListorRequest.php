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

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * ListorRequest
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class ListorRequest implements ListorRequestInterface
{
    /**
     * @var ListorInterface
     */
    private $listor;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var integer
     */
    private $itemsPerPage;

    public function __construct(ListorInterface $listor, FormFactory $formFactory, $itemsPerPage = 10)
    {
        $this->listor = $listor;
        $this->formFactory = $formFactory;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @param mixed $target
     * @param Request $request
     * @param string|\Symfony\Component\Form\FormTypeInterface $formType
     * @return \WS\Libraries\Listor\IterableInterface
     */
    public function execute($target, Request $request, $formType = 'ws_listor')
    {
        return $this->executeNamed('listor', $target, $request, $formType);
    }

    /**
     * @param string $name
     * @param mixed $target
     * @param Request $request
     * @param string|\Symfony\Component\Form\FormTypeInterface $formType
     * @return \WS\Libraries\Listor\IterableInterface
     */
    public function executeNamed($name, $target, Request $request, $formType = 'ws_listor')
    {
        $form = $this->formFactory->createNamed($name, $formType);
        $form->handleRequest($request);
        $data = $form->isValid()?$form->getData():array();

        $params = array();
        if (array_key_exists('current_page', $data)) {
            $params['currentPage'] = $data['current_page'];
        } else if ($request->attributes->has('currentPage')) {
            $params['currentPage'] = $request->attributes->get('currentPage', 0);
        } else {
            $params['currentPage'] = $request->query->get('currentPage', 0);
        }

        if (array_key_exists('items_per_page', $data)) {
            $params['itemsPerPage'] = $data['items_per_page'];
        } else {
            $params['itemsPerPage'] = $this->itemsPerPage;
        }

        $filters = array_key_exists('filters', $data)?$data['filters']:array();

        return IterableRequest::fromBaseIterable($this->listor->execute($target, $filters, $params), $request, $form);
    }
}
