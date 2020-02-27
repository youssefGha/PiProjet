<?php


namespace entrepotBundle\Repository;


use Doctrine\ORM\EntityRepository;

class FournisseurRepository extends EntityRepository
{
    public function countcommande($id){
        $query=$this->getEntityManager()
            ->createQuery('select count(c) from entrepotBundle:Commandeachat c where c.idfournisseur=:idfournisseur ')
            ->setParameters(array('idfournisseur'=>$id));
        return $query->getResult();
    }
    public function countproduit($id){
        $query=$this->getEntityManager()
            ->createQuery('select count(c) from entrepotBundle:fournir c where c.idfournisseur=:idfournisseur ')
            ->setParameters(array('idfournisseur'=>$id));
        return $query->getResult();
    }

}