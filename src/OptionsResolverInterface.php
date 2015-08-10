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
 * OptionsResolverInterface
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
interface OptionsResolverInterface
{
    /**
     * Valid parameters and set default missing keys.
     *
     * @param array $params
     * @return array
     */
    public function resolveParameters(array $params);

    /**
     * Valid filters and set default missing keys.
     *
     * @param array $filters
     * @return array
     */
    public function resolveFilters(array $filters);
}
