<?php

namespace entrepotBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use entrepotBundle\Entity\Client;
use entrepotBundle\Entity\Commandeachat;
use entrepotBundle\Entity\Commandevente;
use entrepotBundle\Entity\Fournisseur;
use entrepotBundle\Entity\Ligneachats;
use entrepotBundle\Entity\Ligneventes;
use entrepotBundle\Entity\Livreur;
use entrepotBundle\Entity\Produit;
use entrepotBundle\Entity\Responsable;
use entrepotBundle\Form\CommandeventeType;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommandeController extends Controller
{
    public function indexAction(Request $request)
    {
       $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$id]);
        $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(['idclient'=>$client->getId()]);
        $em=$this->getDoctrine()->getManager();

        $paginator=$this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $commandes, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 5)
        );
        return $this->render('@entrepot/Default/Commandes/commande.html.twig',array('commandes'=>$commandes,'pagination' => $pagination));
    }
    public function indexachatAction()
    {
        $totalachat=0;
        $totalvente=0;
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $responsable=$this->getDoctrine()->getRepository(Responsable::class)->findOneBy(['iduser'=>$id]);
        $commandes=$this->getDoctrine()->getRepository(Commandeachat::class)->findBy(['idresponsable'=>$responsable->getId()]);
        $ventes=$this->getDoctrine()->getRepository(Commandevente::class)->findAll();
        foreach ($commandes as $commande){
            $totalachat+=$commande->getTotal();
        }
        foreach ($ventes as $vente){
            $totalvente+=$vente->getTotal();
        }
        return $this->render('@entrepot/Default/Commandes/commande_responsable.html.twig',array('commandes'=>$commandes,'ventes'=>$ventes,'totalachat'=>$totalachat,'totalvente'=>$totalvente));
    }
    public function detailventeAction(Request $request,$id){
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $form=$this->createForm(CommandeventeType::class,$commande);
        $form->handleRequest($request);
        if ($commande->getEtat() =="en route")
        {
            $tempsvalidation=$commande->getTempsvalidation();
            $time=new \DateTime('now');
            if ($tempsvalidation instanceof \DateTime){
                $stringValue1 = $tempsvalidation->format('Y-m-d H:i:s');
            }
            if ($time instanceof \DateTime){
                $stringValue2 = $tempsvalidation->format('Y-m-d H:i:s');
            }
            $diff = $time->diff($tempsvalidation);
            $commande->setTempsrestant( $diff->format('%d jours %h heures %i minutes '));
            $em =$this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
        }
        if ($form->isSubmitted() && $form->isValid())
        {
            $em =$this->getDoctrine()->getManager();
            $livreur=new Livreur();
            $livreur=$commande->getIdlivreur();
            if ($livreur->getRating()!=null){
                $livreur->setRating(($livreur->getRating()+$commande->getRating())/2);
            }
            else $livreur->setRating($commande->getRating());
            $em->persist($commande);
            $em->flush();
            //return $this->redirectToRoute('affiche');
        }

        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        return $this->render('@entrepot/Default/Commandes/detail_commande.html.twig',array('commande'=>$commande,'ligneventes'=>$lignevente,'form'=>$form->createView()));
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
            $paginator=$this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $commandes, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                $request->query->getInt('limit', 5)
            );
            return $this->redirectToRoute('commande_select',array('commandes'=>$commandes,'pagination' => $pagination));
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
    public function removeAction($id,Request $request){
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
        $paginator=$this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $commandes, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 5));
        return $this->redirectToRoute('commande_select',array('commandes'=>$commandes,'pagination' => $pagination));
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
    public function generatepdfAction($id){
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        $html=$this->renderView("Default/Commandes/detail_commande.html.twig",array('commande'=>$commande,'ligneventes'=>$lignevente));
        $snappy=$this->get("knp_snappy.pdf");
        $date=$commande->getDate()->format('d-m-Y');
        $filename='commande numero'.$commande->getId().' du '.$date.'.pdf';
        return new PdfResponse(
            $snappy->getOutputFromHtml($html),
            $filename

        );

    }
    public function generatepdfadminAction($id){
        $commande=$this->getDoctrine()->getRepository(Commandeachat::class)->find($id);
        $ligneachat=$this->getDoctrine()->getRepository(Ligneachats::class)->findBy(['idcommande'=>$id]);
        $html=$this->renderView("Default/Commandes/detail_responsable_commande.html.twig",array('commande'=>$commande,'ligneachats'=>$ligneachat));
        $snappy=$this->get("knp_snappy.pdf");
        $date=$commande->getDate()->format('d-m-Y');
        $filename='commande numero'.$commande->getId().' du '.$date.'.pdf';
        return new PdfResponse(
            $snappy->getOutputFromHtml($html),
            $filename

        );

    }
}
