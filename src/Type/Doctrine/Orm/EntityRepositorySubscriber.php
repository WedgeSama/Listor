<?php
/*
 * This file is part of the ws-libraries package.
 *
 * (c) Benjamin Georgeault <github@wedgesama.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\Libraries\Listor\Type\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WS\Libraries\Listor\Event\ListorEvent;
use WS\Libraries\Listor\Events;
use WS\Libraries\Listor\Operations;

/**
 * Only handle ORM Relations.
 *
 * @author Benjamin Georgeault <github@wedgesama.fr>
 */
class EntityRepositorySubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function parser(ListorEvent $event)
    {
        $target = $event->getTarget();

        if ($target instanceof EntityRepository) {
            $qb = $target->createQueryBuilder('a');
            $metadata = $this->em->getClassMetadata($target->getClassName());

            // Handle only filtering of ORM Relations like OneToOne, etc...
            foreach ($event->getFilters() as $field => $filter) {
                if (!$filter['parsed'] && array_key_exists('operator', $filter) && array_key_exists('value', $filter)) {
                    switch ($filter['operator']) {
                        case Operations::CHOICE_AND:
                        case Operations::CHOICE_OR:
                        case Operations::CHOICE_NAND:
                            if ($metadata->hasAssociation($field)) {
                                if (!is_array($filter['value']) && !(is_object($filter['value']) && $filter['value'] instanceof \Traversable)) {
                                    $filter['value'] = array($filter['value']);
                                }

                                if (count($filter['value']) == 0) {
                                    continue;
                                }

                                $method = ($filter['operator'] == Operations::CHOICE_NAND)?'neq':'eq';
                                $qb->join('a.'.$field, 'c');

                                $exprs = array();
                                foreach ($filter['value'] as $one) {
                                    $name = $event->getUniqueName($field);
                                    $exprs[] = $qb->expr()->$method('c', ':'.$name);
                                    $qb->setParameter($name, $one);
                                }

                                $op = ($filter['operator'] == Operations::CHOICE_OR)?'orX':'andX';
                                $qb->andWhere(call_user_func_array(array($qb->expr(), $op), $exprs));

                                $event->filterParsed($field);
                            }

                            break;
                    }
                }
            }

            $event->setTarget($qb);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::TARGET_PARSE => array('parser', 10),
        );
    }
}
