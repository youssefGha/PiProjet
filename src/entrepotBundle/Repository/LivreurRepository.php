<?php


namespace entrepotBundle\Repository;


class LivreurRepository extends \Doctrine\ORM\EntityRepository
{
    function Searchlivreur($libelle){
        $query=$this->getEntityManager()->createQuery("select e from entrepotBundle:Livreur e where  e.id like '%$libelle%' or e.nom like '%$libelle%' or e.prenom like '%$libelle%' or e.cin like '%$libelle%' " );
        return $query->getResult();

    }

}