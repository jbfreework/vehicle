<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 *
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Vehicle $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Vehicle $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllAsArray($data = null)
    {
        $query =  $this->createQueryBuilder('v')
            ->andWhere('v.deleted = :val')
            ->setParameter('val', 0)
            ->andWhere('v.vehicle_type = :type')
            ->setParameter('type', $_ENV['VEHICLE_TYPE']);

        if(array_key_exists('make',$data)) {
            $query = $query->andWhere('v.make = :make')
                ->setParameter('make', $data['make']);
        }        
        if(array_key_exists('model',$data)) {
            $query = $query->andWhere('v.model = :model')
                ->setParameter('model', $data['model']);
        }       
        if(array_key_exists('vin', $data)) {
            $query = $query->andWhere('v.vin = :vin')
                ->setParameter('vin', $data['vin']);
        }       
        if(array_key_exists('msrp', $data)) {
            $query = $query->andWhere('v.msrp = :msrp')
                ->setParameter('msrp', $data['msrp']);
        }

        $query = $query->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getArrayResult();

        return $query;
    }
}
