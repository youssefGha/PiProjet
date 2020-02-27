<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Commandeachat;
use entrepotBundle\Entity\Fournisseur;
use entrepotBundle\Entity\Produit;
use entrepotBundle\Entity\Demande;
use entrepotBundle\Form\DemandeType;

use entrepotBundle\Form\FournisseurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use MessageBird\Client;
use MessageBird\Exceptions\HttpException;
use MessageBird\Exceptions\RequestException;
use MessageBird\Exceptions\ServerException;
use MessageBird\Objects\Message;
class FournisseurController extends Controller
{
    public function indexAction()
    {
        $fournisseurs=$this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $nbvente=[];
        $nbproduit=[];
        $total=[];
        foreach ($fournisseurs as $fournisseur){
            $total[$fournisseur->getId()]=0;
            $nbproduit[$fournisseur->getId()]=0;
            foreach ($fournisseur->getProduits() as $produit ){
                $nbproduit[$fournisseur->getId()]++;
            }
            $commande=$this->getDoctrine()->getRepository(Commandeachat::class)->findBy(['idfournisseur'=>$fournisseur->getid()]);
            $nbvente[$fournisseur->getId()]=0;
            foreach ($commande as $c){
                $nbvente[$fournisseur->getId()]++;
                $total[$fournisseur->getId()]+=$c->getTotal();
            }
        }
        return $this->render('@entrepot/Default/fournisseurs.html.twig',array('fournisseurs'=>$fournisseurs,'nbproduit'=>$nbproduit,'nbvente'=>$nbvente,'total'=>$total));
    }
    public function fourniAction()
    {
        $fournisseurs=$this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findAll();

        return $this->render('@entrepot/Default/fournisseurss.html.twig',array('fournisseurs'=>$fournisseurs,'produits'=>$produits));
    }
    public   function  afficheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $Fournisseur =  $em->getRepository(Fournisseur::class)->findAll();
        if($request->isMethod("POST"))
        {
            $nom =$request->get("nom");
            $fournisseur  = $em->getRepository("entrepotBundle:Fournisseur")->findBy(array("nom"=>$nom));
        }
        //   var_dump($candidat);
        return $this->render("@entrepot/Default/Fournisseur/view.html.twig",array("Fournisseur"=>$Fournisseur));
    }


    public function ajoutAction(Request $request)
    {
        $Fournisseur  = new  Fournisseur();
        $form =$this->createForm(FournisseurType::class,$Fournisseur);
        $form->handleRequest($request);
        // $candidat->setSujet(null);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $Fournisseur->setNumerotel("+216".$Fournisseur->getNumerotel());
            $em->persist($Fournisseur);
            $em->flush();
            return $this->redirectToRoute('fournisseur_affichef');
        }
        return $this->render("@entrepot/Default/Fournisseur/add.html.twig",array("form"=>$form->createView()));
    }

    public function supprimerAction($id)
    {

        $em = $this->getDoctrine()->getManager() ;
        $Fournisseur = $em->getRepository(Fournisseur::class)->find($id) ;
        $em->remove($Fournisseur);
        $em->flush();
        return $this->redirectToRoute('fournisseur_select');

    }

    public function updateAction(Request $request , $id)
    {
        $Fournisseur = $this->getDoctrine()->getRepository(fournisseur::class)->find($id);
        $form = $this->createForm(FournisseurType::class , $Fournisseur) ;
        $form = $form->handleRequest($request);

        if($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager() ;
            $Fournisseur->setImage("fournisseur3.jpg");
            $em->persist($Fournisseur);
            $em->flush();
            //      return $this->redirectToRoute('nesrineafficheevent');
            return $this->redirectToRoute('fournisseur_select');
        }
        return $this->render('@entrepot/Default/Fournisseur/updatef.html.twig' , array('form'=>$form->createView())) ;

    }

    public function rechercheAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $Fournisseur=$em->getRepository("entrepotBundle:Fournisseur")->findAll();
        if($request->isMethod('Post'))
        {
            $matriculefiscale=$request->get('matriculefiscale');
            $Fournisseur=$em->getRepository("entrepotBundle:Fournisseur")->findBy(array("matriculefiscale"=>$matriculefiscale));
        }
        return $this->render('@entrepot/Default/Fournisseur/rech.html.twig', array('Fournisseur'=>$Fournisseur));
    }


    public function barAction()
    {
        $MessageBird = new Client('xki2mcHQjDwUiATfN6SVHtpCt');
        $Message = new Message();
        $Message->originator = "PIfinal";
        $Message->recipients = array(+21655652600);
        $Message->body = 'Alert';

        try {
            $MessageBird->messages->create($Message);
        } catch (HttpException $e) {
        } catch (RequestException $e) {
        } catch (ServerException $e) {
        }
        return $this->redirectToRoute("entrepot_homepage");
    }
    /**testtt
     */


    public function updateFournisseurByIdAction(request $request)
    {
        try {
            $id = $request->get('id');
            $description = $request->get('description');
            $image = $request->get('image');
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $numerotel = $request->get('numerotel');
            $matriculefiscale = $request->get('matriculefiscale');
            $email = $request->get('email');


            $em = $this->getDoctrine()->getManager();
            $Fournisseur = $em->getRepository("entrepotBundle:Fournisseur")->find($id);
            $Fournisseur->setDescription($description);
            $Fournisseur->setImage($image);
            $Fournisseur->setNom($nom);
            $Fournisseur->setNumerotel($prenom);
            $Fournisseur->setMatriculefiscale($matriculefiscale);
            $Fournisseur->setEmail($email);
            $Fournisseur->setEnabled(0);
            $Fournisseur->setCancled(0);

            $em->persist($Fournisseur);
            $em->flush();
            return $this->json(array('error' => false));
        } catch (Exception $ex) {
            return $this->json(array('error' => true));
        }
    }
    public function demandeAction(Request $request , $id)
    {
        $demande = new Demande();
        $fournisseur = $this->getDoctrine()->getManager()->getRepository(Fournisseur::class)->find($id);
        $form=$this->createForm(DemandeType::class,$demande);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $demande->setFournisseur($fournisseur);
            $em->persist($demande);
            $em->flush();
            return $this->redirectToRoute("entrepot_homepage");
        }
        return $this->render('@entrepot/Default/Fournisseur/demande.html.twig',array('form'=>$form->createView()));
    }
    public function demandeafficheAction($id)
    {
        $fournisseur = $this->getDoctrine()->getManager()->getRepository(Fournisseur::class)->find($id);
        $demandes = $this->getDoctrine()->getManager()->getRepository(Demande::class)->findAll();
        return $this->render('@entrepot/Default/Fournisseur/demandeaffiche.html.twig',array('demandes'=>$demandes,'fournisseur'=>$fournisseur));
    }
    public function supprimerDAction($id)
    {

        $em = $this->getDoctrine()->getManager() ;
        $Demande = $em->getRepository(Demande::class)->find($id) ;
        $em->remove($Demande);
        $em->flush();
        return $this->redirectToRoute('fournisseur_select');
    }
}
