<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    /**
     * Guarda una actividad en la base de datos.
     * Centralizamos el EntityManager aquí para limpiar el controlador.
     */
    public function save(Activity $activity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($activity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retorna todas las actividades ordenadas por las más recientes.
     * @return Activity[]
     */
    public function findAllLatest(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }
}