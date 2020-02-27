<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Categorie;
use entrepotBundle\Entity\Commandeachat;
use entrepotBundle\Entity\Fournisseur;
use entrepotBundle\Entity\Livreur;
use entrepotBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $authChecker=$this->container->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_ADMIN')){

            return $this->redirectToRoute('fournisseur_select');
    }
        elseif ($authChecker->isGranted('ROLE_FOURNISSEUR')){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $idu=$user->getId();
            $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->findOneBy(['iduser'=>$idu]);
            $produits=$fournisseur->getProduits();
            $allproduits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
            $notproduit=[];
            $j=0;
            $nbproduit=0;
            $totalvente=0;
            $commandeachats=$this->getDoctrine()->getRepository(Commandeachat::class)->findAll();
            foreach ($commandeachats as $commandeachat)
            {
                if ($commandeachat->getIdfournisseur()==$fournisseur)
                {
                    $totalvente+=$commandeachat->getTotal();
                }
            }
            foreach ($fournisseur->getProduits() as $produit ){
                $nbproduit++;
            }
            foreach ($allproduits as $p){
                $trouve=0;
                foreach ($produits as $p1){
                    if ($p1->getId()==$p->getId()){
                        $trouve=1;
                    }
                }
                if ($trouve==0){
                    $notproduit[$j]=$p;
                }
                $j++;
            }
            return $this->render('@entrepot/Default/Homes/fournisseur_home.html.twig',['produits'=>$produits,'notproduits'=>$notproduit,'fournisseur'=>$fournisseur,'nbproduit'=>$nbproduit,'totalvente'=>$totalvente,'id'=>$fournisseur->getId()]);
        }
        elseif ($authChecker->isGranted('ROLE_LIVREUR')){


            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $id=$user->getId();
            $livreur=$this->getDoctrine()->getRepository(Livreur::class)->findOneBy(['iduser'=>$id]);
            return $this->redirectToRoute('neji_homepage',['id'=>$livreur->getId()]);
        }
        else {
            $categories=$this->getDoctrine()->getRepository(Categorie::class)->findAll();
            return $this->render('@entrepot/Default/index.html.twig',['categories'=>$categories]);
        }

    }
    public function offreAction()
    {

        return $this->render('@entrepot/Default/offre.html.twig');
    }
}
