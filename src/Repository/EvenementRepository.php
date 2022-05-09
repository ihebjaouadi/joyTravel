<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\CategoryEvent;
/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenement $entity, bool $flush = true): void
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
    public function remove(Evenement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


/*******************************Trie**********************************/
    public function OrderByPriceASC(){
        $em=$this ->getEntityManager();
        $query=$em->createQuery('select e from  App\Entity\Evenement e order by e.Prix ASC');
   return $query->getResult();

    }
    public function OrderByPriceDESC(){
        $em=$this ->getEntityManager();
        $query=$em->createQuery('select e from  App\Entity\Evenement e order by e.Prix DESC');
        return $query->getResult();
    }




    /*******************************Search with a Join Between Event and Category**********************************/
    /**
     * @param $value
     * @return  Evenement[]
     */
    public function findEventByValue($value)
    {
        $query=$this->createQueryBuilder('e')
            ->select('c', 'e')
            ->join('e.Category','c')
            ->andWhere('e.Nom LIKE :sujet or e.Prix Like :sujet  or c.Nom Like :sujet')
            ->setParameter('sujet', '%'.$value.'%');
        return $query->getQuery()->getResult();
    }


    public function findEventByValueJson($v)
    {

        $query=$this->createQueryBuilder('e')
            ->select( 'e')

           // ->andWhere('e.Nom LIKE :sujet or e.Prix Like :sujet  ')
           ->andWhere('e.Nom LIKE :sujet ')
            ->setParameter('sujet', '%'.$v.'%');

        return $query->getQuery()->getResult();
    }



    /************* Decrise the number of participants after a making a reservaiton*************/

    /**
     * @param $id
     * @return float|int|mixed|string
     */

public function DecriseNbrParticipants($id)
{
    $em = $this->getEntityManager();
    //  dd($id);
    $query = $em->createQuery('update   App\Entity\Evenement e SET e.Nombre_Participants= e.Nombre_Participants -1 where e.id = :idValue')
        ->setParameter('idValue', $id);
    return $query->getResult();
}

    /*************Update the Data Base without the intervation of the admin When The number of paricipants attent the value 0*************/
    /*************And also when an Event has already end it which means the Date of Ending is superior to the current date   ************/

    public function MiseAjourDeDataBase(){
        $em = $this->getEntityManager();
        $query = $em->createQuery('Delete from  App\Entity\Evenement e where e.Nombre_Participants = 0 or e.Date_fin < CURRENT_DATE() ');
        return $query->getResult();
    }



public function TrieDATE(){
    return $this->createQueryBuilder('e')
        ->where('e.Date_debut between :date1 and :date2')
        ->setParameter('date1', 'DateTime.Now.AddDays(7)')
        ->setParameter('date1', 'DateTime.Now')
        ->getQuery()->getResult();
}



    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
