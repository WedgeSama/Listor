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

use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyResolver;

/**
 * OptionsResolver
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class OptionsResolver implements OptionsResolverInterface
{
    /**
     * @var SymfonyResolver
     */
    private $parametersResolver;

    /**
     * @var SymfonyResolver
     */
    private $filtersResolver;

    public function __construct(array $defaultParameters = array())
    {
        $this->parametersResolver = new SymfonyResolver();
        $this->parametersResolver
            ->setDefaults(array_merge(array(
                'currentPage' => 0,
                'itemsPerPage' => 10,
            ), $defaultParameters))
            ->setAllowedTypes('currentPage', 'int')
            ->setAllowedTypes('itemsPerPage', 'int');

        $this->filtersResolver = new SymfonyResolver();
        $this->filtersResolver
            ->setDefined(array(
                'operator',
                'value',
                'sort',
            ))
            ->setDefaults(array(
                'sort_priority' => 0,
                'parsed' => false,
                'sorted' => false,
            ))
            ->setAllowedValues('sort', array(
                null,
                'asc',
                'desc',
            ))
            ->setAllowedValues('operator', array_merge(
                array(null),
                Operations::getTextOperators(),
                Operations::getChoiceOperators(),
                Operations::getDateTimeOperators()
            ))
            ->setAllowedTypes('sort_priority', 'int');
    }

    /**
     * {@inheritdoc}
     */
    public function resolveParameters(array $params)
    {
        return $this->parametersResolver->resolve($params);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveFilters(array $filters)
    {
        $result = array();
        foreach ($filters as $field => $filter) {
            $result[$field] = $this->filtersResolver->resolve($filter);
        }

        return $result;
    }
}
