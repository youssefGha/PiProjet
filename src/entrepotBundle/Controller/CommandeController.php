<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Client;
use entrepotBundle\Entity\Commandeachat;
use entrepotBundle\Entity\Commandevente;
use entrepotBundle\Entity\Fournisseur;
use entrepotBundle\Entity\Ligneachats;
use entrepotBundle\Entity\Ligneventes;
use entrepotBundle\Entity\Livreur;
use entrepotBundle\Entity\Produit;
use entrepotBundle\Entity\Responsable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends Controller
{
    public function indexAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$id]);
        $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(['idclient'=>$client->getId()]);
        return $this->render('@entrepot/Default/Commandes/commande.html.twig',array('commandes'=>$commandes));
    }
    public function indexachatAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $responsable=$this->getDoctrine()->getRepository(Responsable::class)->findOneBy(['iduser'=>$id]);
        $commandes=$this->getDoctrine()->getRepository(Commandeachat::class)->findBy(['idresponsable'=>$responsable->getId()]);
        return $this->render('@entrepot/Default/Commandes/commande_responsable.html.twig',array('commandes'=>$commandes));
    }
    public function detailventeAction($id){
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        return $this->render('@entrepot/Default/Commandes/detail_commande.html.twig',array('commande'=>$commande,'ligneventes'=>$lignevente));
    }
    public function detailachatAction($id){
        $commande=$this->getDoctrine()->getRepository(Commandeachat::class)->find($id);
        $ligneachat=$this->getDoctrine()->getRepository(Ligneachats::class)->findBy(['idcommande'=>$id]);
        return $this->render('@entrepot/Default/Commandes/detail_responsable_commande.html.twig',array('commande'=>$commande,'ligneachats'=>$ligneachat));
    }
    public function addventeAction(Request $request){
        $session=$request->getSession();
        $commande = new Commandevente();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$id]);
        $commande->setIdclient($client);
        $commande->setTotal($session->get('total'));
        $commande->setDate(new \DateTime('now'));
        $commande->setIdlivreur(null);
        $commande->setEtat("non expédiée");
        $em=$this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(['idclient'=>$client]);
        $panier=$session->get('panier');

        foreach ($panier as $id=>$quantite){
            $produit=new Produit();
            $lignevente = new Ligneventes();
            $produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
            $lignevente->setIdcommande($commande);
            $lignevente->setIdproduit($produit);
            $lignevente->setPu($produit->getPrixvente());
            $lignevente->setQt($quantite);
            $em=$this->getDoctrine()->getManager();
            $em->persist($lignevente);
            $em->flush();
        }
        $session->set('panier',[]);
        $authchecker=$this->container->get('security.authorization_checker');
        if($authchecker->isGranted('ROLE_CLIENT')){
            return $this->render('@entrepot/Default/Commandes/commande.html.twig',array('commandes'=>$commandes));
        }
        else {
            return $this->render('@entrepot/Default/index.html.twig');
        }

    }
    public function addachatAction(Request $request){
        $session=$request->getSession();
        $commande=new Commandeachat();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $responsable=$this->getDoctrine()->getRepository(Responsable::class)->findOneBy(['iduser'=>$id]);
        $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->find($session->get('idfournisseur'));
        $commande->setIdfournisseur($fournisseur);
        $commande->setIdresponsable($responsable);
        $commande->setTotal($session->get('total'));
        $commande->setDate(new \DateTime('now'));
        $commande->setEtat("non expédiée");
        $em=$this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $responsable=$this->getDoctrine()->getRepository(Responsable::class)->findOneBy(['iduser'=>$id]);
        $commandes=$this->getDoctrine()->getRepository(Commandeachat::class)->findBy(['idresponsable'=>$responsable->getId()]);
        $panier=$session->get('panier');

        foreach ($panier as $id=>$quantite){
            $produit=new Produit();
            $ligneachat = new Ligneachats();
            $produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
            $ligneachat->setIdcommande($commande);
            $ligneachat->setIdproduit($produit);
            $ligneachat->setPu($produit->getPrixachat());
            $ligneachat->setQt($quantite);
            $em=$this->getDoctrine()->getManager();
            $em->persist($ligneachat);
            $em->flush();
        }
        $session->set('panier',[]);
        return $this->render('@entrepot/Default/Commandes/commande_responsable.html.twig',array('commandes'=>$commandes));
    }
    public function removeAction($id){
        $commande=new Commandevente();
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $ligneventes=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        foreach ($ligneventes as $lignevente){
            $this->getDoctrine()->getManager()->remove($lignevente);
        }
        $this->getDoctrine()->getManager()->remove($commande);
        $this->getDoctrine()->getManager()->flush();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$id]);
        $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(['idclient'=>$client->getId()]);
        return $this->render('@entrepot/Default/Commandes/commande.html.twig',array('commandes'=>$commandes));
    }
    public function removeresponsableAction($id){
        $commande=new Commandevente();
        $commande=$this->getDoctrine()->getRepository(Commandeachat::class)->find($id);
        $lignesachats=$this->getDoctrine()->getRepository(Ligneachats::class)->findBy(['idcommande'=>$id]);
        foreach ($lignesachats as $lignesachat){
            $this->getDoctrine()->getManager()->remove($lignesachat);
        }
        $this->getDoctrine()->getManager()->remove($commande);
        $this->getDoctrine()->getManager()->flush();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $responsable=$this->getDoctrine()->getRepository(Responsable::class)->findOneBy(['iduser'=>$id]);
        $commandes=$this->getDoctrine()->getRepository(Commandeachat::class)->findBy(['idresponsable'=>$responsable->getId()]);
        return $this->render('@entrepot/Default/Commandes/commande_responsable.html.twig',array('commandes'=>$commandes));
    }
}
