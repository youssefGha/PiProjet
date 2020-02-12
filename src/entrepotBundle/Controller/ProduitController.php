<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Fournisseur;
use entrepotBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends Controller
{
    public function indexAction()
    {
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@entrepot/Default/Produits/produits.html.twig',array('produits'=>$produits));
    }
    public function indexfournisseurAction($id,Request $request)
    {
        $session=$request->getSession();
        $session->set('idfournisseur',$id);
        $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $produits=new Produit();
        $produits=$fournisseur->getProduits();
        $idf=$session->get('idfournisseur');
        return $this->render('@entrepot/Default/Produits/produits_fournisseur.html.twig',array('produits'=>$produits,'idfournisseur'=>$idf));
    }
}
