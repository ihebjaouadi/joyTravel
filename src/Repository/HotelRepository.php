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



    /**
     * //  * @return Hotel[] Returns an array of Hotel objects
     * //  */

    public function getHotelNames()
    {
        $data = [];
        $result = $this->createQueryBuilder('h')
            ->select('DISTINCT(h.Nom)')
            ->getQuery()
            ->getResult();
        foreach ($result as $item) {
            $data[] = $item['1'];
        }
        return $data;
    }

    public function getHotelByFilters($hotelName = null, $hotelCity = null, $typeChambre = null, $tri = null)
    {
        $result = $this->createQueryBuilder('h');
        if (null != $hotelName) {
            $result
                ->andWhere('h.Nom = :name')
                ->setParameter('name', $hotelName);
        }
        if (null != $hotelCity) {
            $result
                ->andWhere('h.Ville = :city')
                ->setParameter('city', $hotelCity);
        }
        if (null != $typeChambre) {
            $result
                ->leftJoin('h.chambres', 'c')
                ->andWhere('c.Type = :type')
                ->setParameter('type', $typeChambre);
        }


        if (null != $tri && ($tri == "DESC" || $tri == "ASC")) {
            $result
                ->orderBy('h.Nb_etoile', $tri);
        }

        return $result->getQuery()->getResult();


    }

    public function getStat()
    {
        $data = [];
        $result =  $this->createQueryBuilder('h')
            ->select('h.Nb_etoile', 'count(h.id) as total')
            ->groupBy('h.Nb_etoile')
            ->getQuery()
            ->getResult();
        foreach ($result as $item){
            $data[$item['Nb_etoile']] = (int)$item['total'];
        }

        return $data;
    }

    public function hotelsContenantChambresDispo()
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
    public function hotelsContenantChambresDispoDate(\DateTime $dateA, \DateTime $dateD)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT h.id, h.nom ,h.adresse, h.ville, h.code_postal,h.complement_adresse,h.pays,h.nb_etoile 
from chambre c join hotel h on c.id_hotel_id=h.id WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ( ?  BETWEEN r.date_arrivee AND r.date_depart ) or ( ? BETWEEN r.date_arrivee AND r.date_depart ) or (? < r.date_arrivee and ? > r.date_depart )
            ))=0";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Hotel', 'h');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $dateA);
        $query->setParameter(2, $dateD);
        $query->setParameter(3, $dateA);
        $query->setParameter(4, $dateD);
        $hotels = $query->getResult();
        return $hotels;
    }
    public function hotelsContenantChambresDispoTypeDate(\DateTime $dateA, \DateTime $dateD, String $type)
    {
        $em = $this->getEntityManager();
        $sql = "SELECT h.id, h.nom ,h.adresse, h.ville, h.code_postal,h.complement_adresse,h.pays,h.nb_etoile 
from chambre c join hotel h on c.id_hotel_id=h.id WHERE (
            SELECT COUNT(*) from reservation r 
            JOIN reservation_chambre rc on rc.reservation_id=r.id
            JOIN chambre on rc.chambre_id=c.id
            WHERE (rc.chambre_id=c.id AND ( ?  BETWEEN r.date_arrivee AND r.date_depart ) or ( ? BETWEEN r.date_arrivee AND r.date_depart ) or (? < r.date_arrivee and ? > r.date_depart )
            ))=0 and c.type = ?";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Hotel', 'h');
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $dateA);
        $query->setParameter(2, $dateD);
        $query->setParameter(3, $dateA);
        $query->setParameter(4, $dateD);
        $query->setParameter(5, $type);
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

    public function getCities()
    {
        $data = [];
        $result = $this->createQueryBuilder('h')
            ->select('DISTINCT(h.Ville)')
            ->getQuery()
            ->getResult();
        foreach ($result as $item) {
            $data[] = $item['1'];
        }

        return $data;
    }


}
