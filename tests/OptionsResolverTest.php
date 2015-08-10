<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Test;

use WS\Libraries\Listor\Operations;
use WS\Libraries\Listor\OptionsResolver;

/**
 * OptionsResolverTest
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class OptionsResolverTest extends \PHPUnit_Framework_TestCase
{
    private function getDefaultParams()
    {
        return array(
            'currentPage' => 0,
            'itemsPerPage' => 10,
        );
    }

    /**
     * @return OptionsResolver
     */
    private function getResolver()
    {
        static $resolver = null;

        if ($resolver === null) {
            $resolver = new OptionsResolver($this->getDefaultParams());
        }

        return $resolver;
    }

    public function provideValidParams()
    {
        return array(
            array(
                array(),
            ),
            array(
                array(
                    'currentPage' => 0,
                    'itemsPerPage' => 10,
                ),
            ),
            array(
                array(
                    'currentPage' => 0,
                ),
            ),
            array(
                array(
                    'itemsPerPage' => 10,
                ),
            ),
        );
    }

    /**
     * @dataProvider provideValidParams
     */
    public function testValidParams($given)
    {
        $this->assertSame($this->getDefaultParams(), $this->getResolver()->resolveParameters($given));
    }

    public function provideInvalidParams()
    {
        return array(
            array(
                array(
                    'currentPage' => 0,
                    'itemsPerPage' => 'foo',
                ),
            ),
            array(
                array(
                    'currentPage' => 'foo',
                ),
            ),
            array(
                array(
                    'itemsPerPage' => 'foo',
                ),
            ),
        );
    }

    /**
     * @dataProvider provideInvalidParams
     */
    public function testInvalidParams($given)
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');

        $this->getResolver()->resolveParameters($given);
    }

    public function provideNotExistParams()
    {
        return array(
            array(
                array(
                    'currentPage' => 0,
                    'itemsPerPage' => 10,
                    'foo' => 'bar',
                ),
            ),
            array(
                array(
                    'foo' => 'bar',
                ),
            ),
        );
    }

    /**
     * @dataProvider provideNotExistParams
     */
    public function testNotExistParams($given)
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException');

        $this->getResolver()->resolveParameters($given);
    }

    public function testValidFilters()
    {
        $this->getResolver()->resolveFilters(array(
            'fields-1' => array(),
            'fields-2' => array(
                'operator' => Operations::TEXT_EQUAL,
                'value' => 'foo bar',
            ),
            'fields-3' => array(
                'sort' => 'asc',
            ),
            'fields-4' => array(
                'sort' => 'asc',
                'sort_priority' => 1,
            ),
            'fields-5' => array(
                'sort' => 'asc',
                'sort_priority' => 2,
                'operator' => Operations::TEXT_LIKE,
                'value' => 'foo bar',
            ),
        ));
    }
}
