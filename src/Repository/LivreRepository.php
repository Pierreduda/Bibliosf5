<?php

namespace App\Repository;

use App\Entity\Livre;
use App\Entity\Emprunt;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository // findAll() vient de ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * @return Livre[] Returns an array of Livre objects
     */

    public function recherche($mot)
    {
        //SELECT l.* FROM `livre` l WHERE l.titre like "%une%"
        return $this->createQueryBuilder('l')
            ->Where('l.titre LIKE :mot OR l.auteur LIKE :mot')
            //->orWhere('l.auteur LIKE :titre')
            //->andWhere()
            ->setParameter('mot', "%$mot%")
            ->orderBy('l.auteur', 'ASC')
            ->addOrderBy('l.titre')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */

     //SELECT l.* FROM `livre` l JOIN emprunt e ON l.id = e.livre_id WHERE e.date_retour IS NULL
    public function findLivresIndisponibles()
    {
        return $this->createQueryBuilder('l')
            ->join(Emprunt::class, "e", "WITH","l.id = e.livre")
            ->where('e.date_retour IS NULL')
            ->orderBy('l.auteur', 'ASC')
            ->addOrderBy('l.titre')
            ->getQuery()
            ->getResult();
    }
}
