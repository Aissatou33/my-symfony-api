<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Task, contenant les méthodes de récupération des tâches.
 *
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Récupère toutes les tâches non archivées.
     *
     * @return Task[]
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isArchived = :isArchived')
            ->setParameter('isArchived', false)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère toutes les tâches archivées.
     *
     * @return Task[]
     */
    public function findAllArchived(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isArchived = :isArchived')
            ->setParameter('isArchived', true)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime toutes les tâches archivées.
     */
    public function deleteAllArchived(): void
    {
        $this->createQueryBuilder('t')
            ->delete()
            ->where('t.isArchived = :isArchived')
            ->setParameter('isArchived', true)
            ->getQuery()
            ->execute();
    }
}