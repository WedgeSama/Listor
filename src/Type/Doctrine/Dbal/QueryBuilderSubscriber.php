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
use WS\Libraries\Listor\Events;
use WS\Libraries\Listor\Exception\ListorException;
use WS\Libraries\Listor\Operations;

/**
 * QueryBuilderSubscriber
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class QueryBuilderSubscriber implements EventSubscriberInterface
{
    public function parser(ListorEvent $event)
    {
        $target = $event->getTarget();

        if ($target instanceof QueryBuilder) {
            $sort = array();
            foreach ($event->getFilters() as $field => $filter) {
                // Prepare sort.
                if (!$filter['sorted'] && array_key_exists('sort', $filter)) {
                    $priority = $filter['sort_priority'];
                    if (!array_key_exists($priority, $sort)) {
                        $sort[$priority] = array();
                    }

                    $sort[$priority][$field] = $filter['sort'];
                }

                // Operator.
                if (!$filter['parsed']) {
                    if (array_key_exists('operator', $filter) && array_key_exists('value', $filter)) {
                        $name = $event->getUniqueName($field);
                        switch ($filter['operator']) {
                            case Operations::TEXT_EQUAL:
                                $target->andWhere($target->expr()->eq('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']);
                                break;
                            case Operations::TEXT_NOT_EQUAL:
                                $target->andWhere($target->expr()->neq('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']);
                                break;
                            case Operations::TEXT_LIKE:
                                $target->andWhere($target->expr()->like('a.' . $field, ':' . $name))
                                    ->setParameter($name, '%' . str_replace('%', '\\%', $filter['value']) . '%');
                                break;
                            case Operations::TEXT_NOT_LIKE:
                                $target->andWhere($target->expr()->notLike('a.' . $field, ':' . $name))
                                    ->setParameter($name, '%' . str_replace('%', '\\%', $filter['value']) . '%');
                                break;
                            case Operations::TEXT_START_BY:
                                $target->andWhere($target->expr()->like('a.' . $field, ':' . $name))
                                    ->setParameter($name, str_replace('%', '\\%', $filter['value']) . '%');
                                break;
                            case Operations::TEXT_END_BY:
                                $target->andWhere($target->expr()->like('a.' . $field, ':' . $name))
                                    ->setParameter($name, '%' . str_replace('%', '\\%', $filter['value']));
                                break;
                            case Operations::DATETIME_BETWEEN:
                                if (empty($filter['value']['start']) || empty($filter['value']['end'])) {
                                    continue;
                                }

                                $name2 = $event->getUniqueName($field);
                                $target->andWhere($target->expr()->gte('a.' . $field, ':' . $name))
                                    ->andWhere($target->expr()->lte('a.' . $field, ':' . $name2))
                                    ->setParameter($name, $filter['value']['start'])
                                    ->setParameter($name2, $filter['value']['end']);
                                break;
                            case Operations::DATETIME_EXACT:
                                // TODO
                                break;
                            case Operations::DATETIME_BEFORE:
                                if (empty($filter['value']['start'])) {
                                    continue;
                                }

                                $target->andWhere($target->expr()->lte('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']['start']);
                                break;
                            case Operations::DATETIME_AFTER:
                                if (empty($filter['value']['start'])) {
                                    continue;
                                }

                                $target->andWhere($target->expr()->gte('a.' . $field, ':' . $name))
                                    ->setParameter($name, $filter['value']['start']);
                                break;
                            case Operations::CHOICE_AND:
                            case Operations::CHOICE_OR:
                            case Operations::CHOICE_NAND:
                                if (!is_array($filter['value']) && !(is_object($filter['value']) && $filter['value'] instanceof \Traversable)) {
                                    $filter['value'] = array($filter['value']);
                                }

                                if (count($filter['value']) == 0) {
                                    break;
                                }

                                $method = ($filter['operator'] == Operations::CHOICE_NAND) ? 'neq' : 'eq';

                                $exprs = array();
                                foreach ($filter['value'] as $one) {
                                    $exprs[] = $target->expr()->$method('a.' . $field, ':' . $name);
                                    $target->setParameter($name, $one);
                                    $name = $event->getUniqueName($field);
                                }

                                $op = ($filter['operator'] == Operations::CHOICE_OR) ? 'orX' : 'andX';
                                $target->andWhere(call_user_func_array(array($target->expr(), $op), $exprs));
                                break;
                            default:
                                throw new ListorException(sprintf('The operator "%s" is not handle for "%s".', $filter['operator'], get_class($target)));
                        }
                    }
                }
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
            Events::TARGET_PARSE => array('parser', 0),
        );
    }
}
