<?php

namespace App\Repository;

use App\Entity\Chambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chambre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chambre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chambre[]    findAll()
 * @method Chambre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chambre::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Chambre $entity, bool $flush = true): void
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
    public function remove(Chambre $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Chambre[] Returns an array of Chambre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chambre
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

//    public function estDisponible(\DateTime $dateArrivee, \DateTime  $dateDepart, int $id){
//        $em = $this->getEntityManager();
//        $q = $em->createQuery('select c from App\Entity\Chambre c where count(select r from App\Entity\Reservation r join
//        )')
//    }
    public function estDisponible(\DateTime $dateArrivee, \DateTime $dateDepart)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * from chambre c WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND :dateA  BETWEEN r.date_arrivee AND r.date_depart or :dateD BETWEEN r.date_arrivee AND r.date_depart
            ))=0";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['dateA' => $dateArrivee->format('Y-m-d'), 'dateD' => $dateDepart->format('Y-m-d')]);

        // returns an array of arrays (i.e. a raw data set)
        $chambres = $resultSet->fetchAllAssociative();
//        dump($chambres);
        return $chambres;
    }

    public function chambresDispo()
    {
        $em = $this->getEntityManager();
        $sql = "SELECT * from chambre c WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ?  BETWEEN r.date_arrivee AND r.date_depart or ? BETWEEN r.date_arrivee AND r.date_depart or (? < r.date_arrivee and ? > r.date_depart )
            ))=0 order by c.id_hotel_id";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Chambre', 'c');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, date_create('2022-01-20'));
        $query->setParameter(2, date_create('2022-01-25'));
        $query->setParameter(3, date_create('2022-01-20'));
        $query->setParameter(4, date_create('2022-01-25'));
        $chs = $query->getResult();
//        dump($chs);
        return $chs;
    }

    public function chambresDispoParHotel(int $idHotel)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT * from chambre c WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ?  BETWEEN r.date_arrivee AND r.date_depart or ? BETWEEN r.date_arrivee AND r.date_depart or (? < r.date_arrivee and ? > r.date_depart )
            ))=0 and c.id_hotel_id= ?";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Chambre', 'c');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(5,$idHotel);
        $query->setParameter(1, date_create('2022-01-01'));
        $query->setParameter(2, date_create('2022-02-01'));
        $query->setParameter(3, date_create('2022-01-01'));
        $query->setParameter(4, date_create('2022-02-01'));
        $chs = $query->getResult();
        return $chs;
    }
    public function chambresDispoParHotelDate(int $idHotel ,\DateTime $dateA, \DateTime $dateD)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT * from chambre c WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ?  BETWEEN r.date_arrivee AND r.date_depart or ? BETWEEN r.date_arrivee AND r.date_depart or (? < r.date_arrivee and ? > r.date_depart )
            ))=0 and c.id_hotel_id= ?";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Chambre', 'c');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(5,$idHotel);
        $query->setParameter(1, $dateA);
        $query->setParameter(2, $dateD);
        $query->setParameter(3, $dateA);
        $query->setParameter(4, $dateD);
        $chs = $query->getResult();
        return $chs;
    }
    public function chambresDispoParHotelTypeChambre(int $idHotel ,\DateTime $dateA, \DateTime $dateD, String $type)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT * from chambre c WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ?  BETWEEN r.date_arrivee AND r.date_depart or ? BETWEEN r.date_arrivee AND r.date_depart or (? < r.date_arrivee and ? > r.date_depart )
            ))=0 and c.id_hotel_id= ? and c.type = ?";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Chambre', 'c');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(5,$idHotel);
        $query->setParameter(1, $dateA);
        $query->setParameter(2, $dateD);
        $query->setParameter(3, $dateA);
        $query->setParameter(4, $dateD);
        $query->setParameter(6, $type);
        $chs = $query->getResult();
        return $chs;
    }
    public function chambresDispoParTypeChambre(\DateTime $dateA, \DateTime $dateD, String $type)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT * from chambre c WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ?  BETWEEN r.date_arrivee AND r.date_depart or ? BETWEEN r.date_arrivee AND r.date_depart or (? < r.date_arrivee and ? > r.date_depart )
            ))=0 and c.type = ?";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Chambre', 'c');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $dateA);
        $query->setParameter(2, $dateD);
        $query->setParameter(3, $dateA);
        $query->setParameter(4, $dateD);
        $query->setParameter(5, $type);
        $chs = $query->getResult();
        return $chs;
    }
}
