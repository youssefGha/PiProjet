<?php


namespace entrepotBundle\Controller;
use AppBundle\Entity\User;
use entrepotBundle\Entity\Client;
use entrepotBundle\Entity\Commandevente;
use entrepotBundle\Entity\Ligneventes;
use entrepotBundle\Entity\Livreur;
use entrepotBundle\Form\CommandeventeeType;
use entrepotBundle\Form\CommandeventeType;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Message;

class LivreurcommandeController extends Controller
{
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($id);
        $commandenontraite=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>null));
        $commandeenroute=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>$id,'etat'=>'en route'));
        $commandelivree=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>$id,'etat'=>'livrée'));
        $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(['idlivreur'=>$id,'etat'=>"livrée"]);
        $quantite=0;
        $benefice=0;
        foreach ($commandes as $commande)
        {
            $quantite=$quantite+1;
        }

        $benefice=6*$quantite;
        return $this->render('@entrepot/Default/Livreurcommandes/index.html.twig', array(
            'livreur' => $livreurs,
            'benefice' => $benefice,
            'quantite' => $quantite,
            'commande' =>$commandenontraite,
            'commandeenroute'=>$commandeenroute,
            'commandelivree'=>$commandelivree,


        ));


    }
    public function affichecommandenonlivreeAction($id)
    {   $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($id);
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>null));
        return $this->render('@entrepot/Default/Livreurcommandes/affiche.html.twig',array('commande'=>$commande,'livreur' => $livreurs));

    }
    public function affichecommandeadminAction($id)
    {   $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($id);
        $commandeenroute=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>$id,'etat'=>'en route'));
        $commandelivree=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>$id,'etat'=>'livrée'));
        return $this->render('@entrepot/Default/Livreurcommandes/listecommandeadmin.html.twig',array('commandeenroute'=>$commandeenroute,'livreur' => $livreurs,'commandelivree'=>$commandelivree,));

    }
    public function consulteradminAction($id,$li)
    {    $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($li);
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);

        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        return $this->render('@entrepot/Default/Livreurcommandes/consulteradmin.html.twig',array('commande'=>$commande,'ligneventes'=>$lignevente,'livreur' => $livreurs));


    }
    public function commandenonlivreedetailleAction(Request $request,$id,$li)
    {   $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($li);

        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $editForm=$this->createForm(CommandeventeeType::class,$commande);
        $editForm->handleRequest($request);
        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em =$this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute('valide',array('id'=>$commande->getId(),'li'=>$livreurs->getId()));
        }
        return $this->render('@entrepot/Default/Livreurcommandes/affichec.html.twig',array('commande'=>$commande,'ligneventes'=>$lignevente,'livreur' => $livreurs,'edit_form' => $editForm->createView(),));


    }
    public function valideAction($id,$li)
    {
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $livreur=new Livreur();
        $livreur=$this->getDoctrine()->getRepository(Livreur::class)->find($li);
        $commande->setIdlivreur($livreur);
        $commande->setEtat("en route");
        $tempsvalidation=$commande->getTempsvalidation();
        $time=new \DateTime('now');
        if ($tempsvalidation instanceof \DateTime){
        $stringValue1 = $tempsvalidation->format('Y-m-d H:i:s');
        }
        if ($time instanceof \DateTime){
            $stringValue2 = $tempsvalidation->format('Y-m-d H:i:s');
        }


        $diff = $time->diff($tempsvalidation);

        $commande->setTempsrestant( $diff->format('%h hours'));

        $em =$this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('neji_homepage',array('id'=>$livreur->getId()));
    }
    public function affichelistevalideAction($id)
    { $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($id);
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>$id,'etat'=>'en route'));
        return $this->render('@entrepot/Default/Livreurcommandes/affichel.html.twig',array('commande'=>$commande,'livreur' => $livreurs));

    }
    public function commandenonvalidedetailleAction($id,$li)
    { #$form=$this->createForm(EmailType::class);
        #$form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($li);
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);

        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        #if ($form->isSubmitted() && $form->isValid()) {


           # return $this->redirectToRoute('livree',array('id'=>$commande->getId(),'li'=>$livreurs->getId()));
       # }

        return $this->render('@entrepot/Default/Livreurcommandes/affichecl.html.twig',array('commande'=>$commande,'ligneventes'=>$lignevente,'livreur' => $livreurs,));


    }

    public function livreeAction($id,$li)
    {




        $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($li);
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $idcommande=$commande->getId();
        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        $html=$this->renderView("Default/affichecfinale.html.twig",array('commande'=>$commande,'ligneventes'=>$lignevente));

        $filename='commande numero'.$idcommande;
        $pdf = $this->get("knp_snappy.pdf")->getOutputFromHtml($html);
        $idclient=$commande->getIdclient();
        $client=$this->getDoctrine()->getRepository(Client::class)->find($idclient);
        $iduser=$client->getIduser();
        $user=$this->getDoctrine()->getRepository(User::class)->find($iduser);
        $mail=$user->getEmail();
        $commande->setEtat("livrée");
        $commande->setTempsrestant("");
        $em =$this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $message = Swift_Message::newInstance()

            ->setSubject($idcommande)
            ->setFrom('mohamedneji.ghazouani@esprit.tn' )
            ->setTo($mail)
            ->setBody("commande livree");
              $attachement = \Swift_Attachment::newInstance($pdf, $filename, 'application/pdf' );
              $message->attach($attachement);

             $this->get('mailer')->send($message);



        return $this->redirectToRoute('neji_homepage',array('id'=>$livreurs->getId()));
    }
    public function affichetAction($id)
        {   $em = $this->getDoctrine()->getManager();
            $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($id);
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(array('idlivreur'=>$id,'etat'=>'livrée'));
        return $this->render('@entrepot/Default/Livreurcommandes/affichet.html.twig',array('commande'=>$commande,'livreur' => $livreurs));

    }

    public function affichecfinaleAction($id,$li)
    {    $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('entrepotBundle:Livreur')->find($li);
        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);

        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        return $this->render('@entrepot/Default/Livreurcommandes/affichecfinale.html.twig',array('commande'=>$commande,'ligneventes'=>$lignevente,'livreur' => $livreurs));


    }
    public function generatepdfAction($id){

        $commande=$this->getDoctrine()->getRepository(Commandevente::class)->find($id);
        $lignevente=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$id]);
        $html=$this->renderView("Default/affichecfinale.html.twig",array('commande'=>$commande,'ligneventes'=>$lignevente));
        $snappy=$this->get("knp_snappy.pdf");
        $date=$commande->getDate()->format('d-m-Y');
        $filename='commande numero'.$commande->getId().' du '.$date.'.pdf';
        return new PdfResponse(
            $snappy->getOutputFromHtml($html),
            $filename

        );


    }









}