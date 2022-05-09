<?php

namespace App\Repository;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

}
