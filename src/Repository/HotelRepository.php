<?php

namespace App\Repository;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Hotel $entity, bool $flush = true): void
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
    public function remove(Hotel $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function hotelsContenatnChambresDispo()
    {
        $em = $this->getEntityManager();
        $sql = "SELECT h.id, h.nom ,h.adresse, h.ville, h.code_postal,h.complement_adresse,h.pays,h.nb_etoile 
from chambre c join hotel h on c.id_hotel_id=h.id WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ?  BETWEEN r.date_arrivee AND r.date_depart or ? BETWEEN r.date_arrivee AND r.date_depart
            ))=0";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Hotel', 'h');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, date_create('2022-04-20'));
        $query->setParameter(2, date_create('2022-04-25'));
        $hotels = $query->getResult();
        return $hotels;
    }

    // /**
    //  * @return Hotel[] Returns an array of Hotel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hotel
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
