<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Type\Doctrine\Dbal;

use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WS\Libraries\Listor\Event\ListorEvent;
use WS\Libraries\Listor\Exception\ListorException;
use WS\Libraries\Listor\ListorOperations;

/**
 * QueryBuilderSubscriber
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class QueryBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * Use to make all query parameters unique.
     *
     * @var int
     */
    private static $increment = 0;

    public function parser(ListorEvent $event)
    {
        $target = $event->getTarget();

        if ($target instanceof QueryBuilder) {
            $sort = array();
            foreach ($event->getFilters() as $field => $filter) {
                // Prepare sort.
                if (!$filter['sorted']) {
                    if (array_key_exists('sort', $filter)) {
                        $priority = $filter['sort_priority'];
                        if (!array_key_exists($priority, $sort)) {
                            $sort[$priority] = array();
                        }

                        $sort[$priority][$field] = $filter['sort'];
                    }
                }

                // Operator.
                if (!$filter['parsed']) {
                    if (array_key_exists('operator', $filter) && array_key_exists('value', $filter)) {
                        $name = $field . '_' . self::$increment++;
                        switch ($filter['operator']) {
                            case ListorOperations::TEXT_EQUAL:
                                $target->andWhere($target->expr()->eq('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']);
                                break;
                            case ListorOperations::TEXT_NOT_EQUAL:
                                $target->andWhere($target->expr()->neq('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']);
                                break;
                            case ListorOperations::TEXT_LIKE:
                                $target->andWhere($target->expr()->like('a.' . $field, ':' . $name))
                                    ->setParameter($name, '%' . str_replace('%', '\\%', $filter['value']) . '%');
                                break;
                            case ListorOperations::TEXT_NOT_LIKE:
                                $target->andWhere($target->expr()->notLike('a.' . $field, ':' . $name))
                                    ->setParameter($name, '%' . str_replace('%', '\\%', $filter['value']) . '%');
                                break;
                            case ListorOperations::TEXT_START_BY:
                                $target->andWhere($target->expr()->like('a.' . $field, ':' . $name))
                                    ->setParameter($name, str_replace('%', '\\%', $filter['value']) . '%');
                                break;
                            case ListorOperations::TEXT_END_BY:
                                $target->andWhere($target->expr()->like('a.' . $field, ':' . $name))
                                    ->setParameter($name, '%' . str_replace('%', '\\%', $filter['value']));
                                break;
                            case ListorOperations::DATETIME_BETWEEN:
                                if (empty($filter['value']['start']) || empty($filter['value']['end'])) {
                                    continue;
                                }

                                $name2 = $field . '_' . self::$increment++;
                                $target->andWhere($target->expr()->gte('a.' . $field, ':' . $name))
                                    ->andWhere($target->expr()->lte('a.' . $field, ':' . $name2))
                                    ->setParameter($name, $filter['value']['start'])
                                    ->setParameter($name2, $filter['value']['end']);
                                break;
                            case ListorOperations::DATETIME_EXACT:
                                // TODO
                                break;
                            case ListorOperations::DATETIME_BEFORE:
                                if (empty($filter['value']['start'])) {
                                    continue;
                                }

                                $target->andWhere($target->expr()->lte('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']['start']);
                                break;
                            case ListorOperations::DATETIME_AFTER:
                                if (empty($filter['value']['start'])) {
                                    continue;
                                }

                                $target->andWhere($target->expr()->gte('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']['start']);
                                break;
                            case ListorOperations::CHOICE_AND:
                            case ListorOperations::CHOICE_OR:
                            case ListorOperations::CHOICE_NAND:
                                if (!is_array($filter['value']) && !(is_object($filter['value']) && $filter['value'] instanceof \Traversable)) {
                                    $filter['value'] = array($filter['value']);
                                }

                                if (count($filter['value']) == 0) {
                                    break;
                                }

                                $method = ($filter['operator'] == ListorOperations::CHOICE_NAND) ? 'neq' : 'eq';

                                $exprs = array();
                                foreach ($filter['value'] as $one) {
                                    $exprs[] = $target->expr()->$method('a.' . $field, ':' . $name);
                                    $target->setParameter($name, $one);
                                    $name = $field . '_' . self::$increment++;
                                }

                                $op = ($filter['operator'] == ListorOperations::CHOICE_OR) ? 'orX' : 'andX';
                                $target->andWhere(call_user_func_array(array($target->expr(), $op), $exprs));
                                break;
                            default:
                                throw new ListorException(sprintf('The operator "%s" is not handle for "%s".', $filter['operator'], get_class($target)));
                        }
                    }
                }

                $event->filterParsed($field);
            }

            // Count.
            $count = clone $target;

            $count->select('count(*) as count');
            $event->setCount($count->execute()->fetchColumn());

            // Query.
            if ($event->count() > 0) {
                // Pagination.
                $params = $event->getParams();
                $target->setFirstResult($params['currentPage']*$params['itemsPerPage']);
                $target->setMaxResults($params['itemsPerPage']);

                // Apply sort.
                foreach ($sort as $priority) {
                    foreach ($priority as $field => $direction) {
                        $target->addOrderBy($field, $direction);
                    }
                }

                $event->setItems($target->execute()->fetchAll());
            }

            $event->stopPropagation();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'ws_listor.target_parser' => array('parser', 0),
        );
    }
}
