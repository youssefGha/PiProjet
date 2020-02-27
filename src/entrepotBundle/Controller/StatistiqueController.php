<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Commandeachat;
use entrepotBundle\Entity\Commandevente;
use entrepotBundle\Entity\Fournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatistiqueController extends Controller implements \JsonSerializable
{
    public function indexAction()
    {
            $ids=[];
            $total=[];
            $i=0;
            $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findAll();
            foreach ($commandes as $commande){
                $ids[$i]=json_decode($commande->getId());
                $total[$i]=json_decode($commande->getTotal());
                $i++;
            }
            $fournisseurs=$this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
            $nbvente=[];
            $nbproduit=[];
            $j=0;
            $idf=[];
            foreach ($fournisseurs as $fournisseur){
                $idf[$j]=json_decode($fournisseur->getId());
                $commande=$this->getDoctrine()->getRepository(Commandeachat::class)->findBy(['idfournisseur'=>$fournisseur->getid()]);
                $nbvente[$j]=0;
                foreach ($commande as $c){
                    $nbvente[$j]++;
                }
                $j++;
            }
            dump($idf);
            return $this->render('@entrepot/Default/Statistiques/statistique.html.twig',['ids'=>$ids,'totals'=>$total,'nbvente'=>$nbvente,'idf'=>$idf]);


    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
