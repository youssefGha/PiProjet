<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Fournisseur;
use entrepotBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends Controller
{
    public function indexAction(Request $request)
    {
        $session=$request->getSession();
        $panier=$session->get('panier',[]);
        $panierwithdata=[];
        foreach ($panier as $id=>$quantite){
            $panierwithdata[]=[
                'produit'=>$this->getDoctrine()->getRepository(Produit::class)->find($id),
                'quantite'=>$quantite
            ];
        }
        $total=0;
        foreach ($panierwithdata as $item)
        {
            $totalitem=$item['produit']->getPrixvente() * $item['quantite'];
            $total+=$totalitem;
        }
        $session->set('total',$total);
        return $this->render('@entrepot/Default/panier/panier.html.twig',['items'=>$panierwithdata,'total'=>$total]);
    }
    public function indexachatAction(Request $request)
    {
        $session=$request->getSession();
        $panier=$session->get('panier',[]);
        $panierwithdata=[];
        foreach ($panier as $id=>$quantite){
            $panierwithdata[]=[
                'produit'=>$this->getDoctrine()->getRepository(Produit::class)->find($id),
                'quantite'=>$quantite
            ];
        }
        $total=0;
        foreach ($panierwithdata as $item)
        {
            $totalitem=$item['produit']->getPrixachat() * $item['quantite'];
            $total+=$totalitem;
        }
        $session->set('total',$total);
        $idf=$session->get('idfournisseur');
        return $this->render('@entrepot/Default/panier/panier_fournisseur.html.twig',['items'=>$panierwithdata,'total'=>$total,'idfournisseur'=>$idf]);
    }
    public function addAction($id,Request $request)
    {
        $session=$request->getSession();
        $panier=$session->get('panier',[]);
        if (!empty($panier[$id])){
            $panier[$id]++;
        }
        else {
            $panier[$id]=1;
        }
        $session->set('panier',$panier);
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@entrepot/Default/Produits/produits.html.twig',array('produits'=>$produits));
    }
    public function addfournisseurAction($id,Request $request)
    {
        $session=$request->getSession();
        $panier=$session->get('panier',[]);
        if (!empty($panier[$id])){
            $panier[$id]++;
        }
        else {
            $panier[$id]=1;
        }
        $session->set('panier',$panier);
        $idf=$session->get('idfournisseur');
        $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->find($idf);
        $produits=new Produit();
        $produits=$fournisseur->getProduits();
        return $this->render('@entrepot/Default/Produits/produits_fournisseur.html.twig',array('produits'=>$produits));
    }
    public function removeAction($id,Request $request){
        $session=$request->getSession();
        $panier=$session->get('panier',[]);
        if (!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier_select');
    }
    public function removeresponsableAction($id,Request $request){
        $session=$request->getSession();
        $panier=$session->get('panier',[]);
        if (!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier_responsable_select');
    }
}
