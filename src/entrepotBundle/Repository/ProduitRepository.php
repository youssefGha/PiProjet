<?php


namespace entrepotBundle\Repository;



use Doctrine\ORM\EntityRepository;
use entrepotBundle\Data\SearchData;

class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
    function SearchCours($libelle){
        $query=$this->getEntityManager()->createQuery("select e from entrepotBundle:Produit e where e.lib LIKE '%$libelle%' or e.prixVente like '%$libelle%' or e.description like '%$libelle%' or e.prixAchat like '%$libelle%'   ");
        return $query->getResult();

    }
}