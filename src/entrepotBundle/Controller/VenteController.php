<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Form\rechercherReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Compiler\RepeatedPass;
use entrepotBundle\Entity\Question;
use entrepotBundle\Entity\Reclamation;
use entrepotBundle\Entity\Reponse;
use entrepotBundle\Entity\Responsable;
use entrepotBundle\Entity\Client;
use entrepotBundle\Form\ReclamationType;
use entrepotBundle\Form\ReponseType;
use entrepotBundle\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use entrepotBundle\Entity\Notification;
use entrepotBundle\Entity\Forum;
use entrepotBundle\Entity\RF;
use entrepotBundle\Form\ForumReponseType;
use entrepotBundle\Form\ForumType;



class VenteController extends Controller
{

    public function indexFrontAction()
    {
        return $this->render('@entrepot/Default/index.html.twig');
    }
    public function indexBackAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->findAll();
        $client = $em->getRepository(Client::class)->findAll();
        return $this->render('@entrepot/Default/Vente/listReclamation.html.twig', array(
            'client'=>$client,
            'reclamations' => $reclamation
        ));
    }
    //Client
    public function PasserReclamationAction(Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$id]);
        //$notification= $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $reclamation = new Reclamation();
        $responsable = $this->getDoctrine()->getRepository(Responsable::class)->find(3);
        $form = $this->createForm(ReclamationType::class,$reclamation);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $reclamation->setIdresponsable($responsable);
            $reclamation->setIdclient($client);
            $reclamation->setDate(new \DateTime('now'));
            $reclamation->setEtat("en cours");
            $em->persist($reclamation);
            $em->flush();
            //enregistrer une reponse
            $reponse = new Reponse();
            $reponse->setIdreclamation($reclamation);
            $reponse ->SetContenu("false");
            $reponse ->SetType("Responsable");
            $reponse->setDate(new \DateTime('now'));
            $am = $this->getDoctrine()->getManager();
            $am ->persist($reponse);
            $am ->flush();
            //enregistrer une question
            $q = new Question();
            $q ->setIdreclamation($reclamation);
            $q ->SetContenu($reclamation->getContenu());
            $q ->SetType("Client");
            $q->setDate(new \DateTime('now'));
            $f = $this->getDoctrine()->getManager();
            $f ->persist($q);
            $f ->flush();
            /**increment*/
            $w = $this->getDoctrine()->getRepository(Notification::class)->findall();
            $nbr = count($w);
            /**************** */

            $notif=$this->getDoctrine()->getManager();
            $notification = new Notification();

            $notification
                ->setTitle('New Reclamation')
                ->setDescription($reclamation->getMotif())
                ->setRoute('vente_listReclamation')// I suppose you have a show route for your entity
                ->setParameters(array('id' => $reclamation->getId()))
                ->setnbr($nbr+1)
            ;
            $notification->setIdreclamation($reclamation);;
            $notif ->persist($notification);
            $notif->flush();

            //$pusher = $this->get('mrad.pusher.notificaitons');
            //$pusher->trigger();
            return $this->redirectToRoute('vente_all');
        }
        return $this->render('@entrepot/Default/Vente/Front/ajouterReclamation.html.twig',array(
            'form'=>$form->createView(),
            'client'=>$client,
        ));
    }
    public function ConsulterReclamationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id=$user->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$id]);
        $reclamation = $em->getRepository(Reclamation::class)->findAll();
        $question=$this->getDoctrine()->getRepository(Question::class)->findOneBy(['idreclamation'=>$id]);
        $reponse = $em->getRepository(Reponse::class)->find(1);
        return $this->render('@entrepot/Default/Vente/Front/ConsulterReclamation.html.twig', array(
            'reponse'=>$reponse,
            'client'=>$client,
            'id'=>$id,
            'question'=>$question,
            'reclamations' => $reclamation
        ));
    }
    public function ModifierReclamationAction($id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $iduser=$user->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$iduser]);
        $c = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['idreclamation'=>$id]);
        $c->persist($reclamation);
        $c->flush();
        $question->SetContenu($reclamation->getContenu());
        $form = $this->createForm(ReclamationType::class,$reclamation);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('vente_all');
        }
        return $this->render('@entrepot/Default/Vente/Front/editReclamation.html.twig', array(
            'edit_form'=>$form->createView(),
            'reclamations' =>$reclamation,
            'client'=>$client,
            'question'=>$question
        ));
    }
    // Supprimer_reclamation par un client
    public function removeAction($id)
    {
        $reclamation =new Reclamation();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(['idreclamation'=>$id]);
        foreach ($questions as $question)
        {
            $this->getDoctrine()->getManager()->remove($question);
        }
        $reponses = $this->getDoctrine()->getRepository(Reponse::class)->findBy(['idreclamation'=>$id]);
        foreach ($reponses as $reponse)
        {
            $this->getDoctrine()->getManager()->remove($reponse);
        }
        $notification = $this->getDoctrine()->getRepository(Notification::class)->findBy(['idreclamation'=>$id]);
        foreach ($notification as $notifications)
        {
            $this->getDoctrine()->getManager()->remove($notifications);
        }
        $em = $this->getDoctrine()->getManager();
        //$reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em ->remove($reclamation);
        $em -> flush();
        return $this->redirectToRoute('vente_all');
    }
    //chat client


    //chat_room
    public function chatClientAction(Request $request, $id)
    {
        $login = $this->container->get('security.token_storage')->getToken()->getUser();
        $idclient=$login->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$idclient]);
        $am = $this->getDoctrine()->getManager();
        $rep = $this->getDoctrine()->getRepository(Reponse::class)->findOneBy(['idreclamation'=>$id]);
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['idreclamation'=>$id]);
        $am->persist($question);
        $am->flush();
        $new = new Question();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(QuestionType::class,$new);
        $form->handleRequest($request);
        /*historique*/
        $c = $this->getDoctrine()->getManager();
        $r = $this->getDoctrine()->getRepository(Reponse::class)->findBy(['idreclamation'=>$id]);
        $q = $this->getDoctrine()->getRepository(Question::class)->findBy(['idreclamation'=>$id]);
        foreach ($r as $R) {
            $R = $c->getRepository(Reponse::class)->findBy(['idreclamation'=>$id]);
        }
        foreach ($q as $Q) {
            $Q = $c->getRepository(Question::class)->findBy(['idreclamation'=>$id]);
        }
        if ($form->isValid() && $form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();
            $new->setType("Client");
            $new->setIdreclamation($reclamation);
            $new->setDate(new \DateTime('now'));
            //$reclamation->setContenu($question->getcontenu());
            $reclamation->SetEtat("en cours");
            $em->persist($new);
            $em->flush();
            return $this->redirectToRoute('vente_all');
        }
        return $this->render('@entrepot/Default/Vente/Front/chat_room.html.twig', array(
            'form'=>$form->createView(),
            //'q' =>$question,
            'reclamation'=>$reclamation,
            'client'=>$client,
            'r'=>$R,
            'q'=>$Q,
            'rep'=>$rep
        ));
    }
    /*********************************/
    //Admin
    //supprimer reclamation par admin
    public function deleteAction($id)
    {
        $reclamation =new Reclamation();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(['idreclamation'=>$id]);
        foreach ($questions as $question)
        {
            $this->getDoctrine()->getManager()->remove($question);
        }
        $reponses = $this->getDoctrine()->getRepository(Reponse::class)->findBy(['idreclamation'=>$id]);
        foreach ($reponses as $reponse)
        {
            $this->getDoctrine()->getManager()->remove($reponse);
        }
        $notification = $this->getDoctrine()->getRepository(Notification::class)->findBy(['idreclamation'=>$id]);
        foreach ($notification as $notifications)
        {
            $this->getDoctrine()->getManager()->remove($notifications);
        }
        $em = $this->getDoctrine()->getManager();
        //$reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em ->remove($reclamation);
        $em -> flush();
        return $this->redirectToRoute('vente_rech');
    }
    /****reponse responsable***/
    //modifier reponse d'un admin
    public function editReponseAction($id, Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $idadmin=$user->getId();
        $admin=$this->getDoctrine()->getRepository(Responsable::class)->findOneBy(['id'=>$idadmin]);
        $c = $this->getDoctrine()->getManager();
        $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findOneBy(['idreclamation'=>$id]);
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['idreclamation'=>$id]);
        $c->persist($reponse);
        $c->flush();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(ReponseType::class,$reponse);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $reponse->setType("responsable");
            $reponse->setIdreclamation($reclamation);
            $reclamation->SetEtat("Traité");
            $em->flush();
            return $this->redirectToRoute('vente_rech');
        }
        return $this->render('@entrepot/Default/Vente/Back/editReponse.html.twig', array(
            'edit_form'=>$form->createView(),
            'reponse' =>$reponse,
            'question'=>$question,
            'admin'=>$admin,
            'reclamation'=>$reclamation
        ));
    }
    //afficher la liste des reclamations pour l'admin
    public function listReclamationAction(Request $request)
    {
        $recherche=new Reclamation();
        $notification= $this->getDoctrine()->getRepository(Notification::class)->findAll();

        $form = $this->createForm(rechercherReclamationType::class,$recherche);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $recherche=$this->getDoctrine()->getRepository(Reclamation::class)
                ->findBy(array('motif'=>$recherche->getmotif()));
        }else {
            $recherche=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        }

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->findAll();
        $question =$em->getRepository(Question::class)->findAll();
        $client = $em->getRepository(Client::class)->findAll();
        return $this->render('@entrepot/Default/Vente/Back/listReclamation.html.twig', array(
            'form'=>$form->createView(),
            'client'=>$client,
            'question'=>$question,
            'recherche'=>$recherche,
            'notif'=>$notification,
            'reclamations' => $reclamation
        ));
    }


    public function chatAction($id,Request $request)
    {
        $c = $this->getDoctrine()->getManager();
        $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findOneBy(['idreclamation'=>$id]);
        $c->persist($reponse);
        $c->flush();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['idreclamation'=>$id]);
        $new = new Reponse();
        $client = $this->getDoctrine()->getRepository(client::class)->findOneBy(['iduser'=>$id]);
        $form = $this->createForm(ReponseType::class,$new);
        $form->handleRequest($request);
        /*historique*/
        $rep = $this->getDoctrine()->getRepository(Reponse::class)->findBy(['idreclamation'=>$id]);
        $ques = $this->getDoctrine()->getRepository(Question::class)->findBy(['idreclamation'=>$id]);
        foreach ($rep as $R) {
            $R = $c->getRepository(Reponse::class)->findBy(['idreclamation'=>$id]);
        }
        foreach ($ques as $Q) {
            $Q = $c->getRepository(Question::class)->findBy(['idreclamation'=>$id]);
        }
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $new->setType("responsable");
            $new->setIdreclamation($reclamation);
            $new->setDate(new \DateTime('now'));
            $reclamation->SetEtat("Traité");
            $em->persist($new);
            $em->flush();
            return $this->redirectToRoute('vente_rech');
        }
        return $this->render('@entrepot/Default/Vente/Back/chat.html.twig', array(
            'edit_form'=>$form->createView(),
            'reponse' =>$reponse,
            'question'=>$question,
            'client'=>$client,
            'r'=>$R,
            'q'=>$Q,
            'reclamation'=>$reclamation
        ));

    }
    public function rechercheReclamationAction(Request $request)
    {
        $recherche=new Reclamation();
        $notification= $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $cou = count($notification);
        $form = $this->createForm(rechercherReclamationType::class,$recherche);
        $form->handleRequest($request);
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        if($form->isSubmitted())
        {
            $recherche=$this->getDoctrine()->getRepository(Reclamation::class)
                ->findBy(array('motif'=>$recherche->getmotif()));
        }else {
            $recherche=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        }
        return $this->render('@entrepot/Default/Vente/Back/rech.html.twig', array(
            'form'=>$form->createView(),
            'recherche'=>$recherche,
            'cou'=>$cou
        ,'reclamation'=>$reclamation
        ,'notif'=>$notification
        ));
    }
    public function deleteNotificationAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $notification= $em->getRepository(Notification::class)->find($id);
        //$reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em ->remove($notification);
        $em -> flush();
        return $this->redirectToRoute('vente_rech');
    }
    public function allNotificationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notif= $em->getRepository(Notification::class)->findAll();
        foreach ($notif as $n){
            $em ->remove($n);
            $em -> flush();
        }
        return $this->redirectToRoute('vente_rech');
    }
    public function ForumAction(Request $request)
    {
        $login = $this->container->get('security.token_storage')->getToken()->getUser();
        $idc=$login->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$idc]);
        $am = $this->getDoctrine()->getManager();
        $forum = $this->getDoctrine()->getRepository(Forum::class)->findAll();
        $nbr = count($forum);
        $newForum = new Forum();

        $am->flush();
        $form = $this->createForm(ForumType::class,$newForum);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();

            $newForum->setidclient($client);
            $newForum->setDate(new \DateTime('now'));
            $em->persist($newForum);
            $em->flush();

            $newReponse = new RF();
            $newReponse->setContenu($newForum->getContenu());
            $newReponse->setIdforum($newForum);
            $newReponse->setDate(new \DateTime('now'));
            $newReponse->setIdclient($client);
            $om = $this->getDoctrine()->getManager();
            $om->persist($newReponse);
            $om->flush();


            return $this->redirectToRoute('vente_all');
        }
        return $this->render('@entrepot/Default/Vente/Front/Forum.html.twig', array(
            'form'=>$form->createView(),
            'forum' =>$forum,
            'client'=>$client,
            'id'=>$idc,
            'nbr'=>$nbr
        ));
    }
    public function ForumchatAction($id,Request $request)
    {
        $login = $this->container->get('security.token_storage')->getToken()->getUser();
        $idc=$login->getId();
        $client=$this->getDoctrine()->getRepository(Client::class)->findOneBy(['iduser'=>$idc]);
        $c = $this->getDoctrine()->getManager();
        $Forum = $this->getDoctrine()->getRepository(RF::class)->findBy(['idforum'=>$id]);
        $question = $this->getDoctrine()->getRepository(Forum::class)->findOneBy(['id'=>$id]);
        //$RF = $this->getDoctrine()->getRepository(RF::class)->findOneBy(['id'=>$id]);
        foreach ($Forum as $fo) {
            $fo = $c->getRepository(RF::class)->findBy(['idforum'=>$id]);
        }
        //$c->persist($Forum);
        $c->flush();
        $newReponse = new RF();
        $form = $this->createForm(ForumReponseType::class,$newReponse);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $newReponse->setIdforum($question);
            $newReponse->setIdclient($client);
            $newReponse->setDate(new \DateTime('now'));
            $om = $this->getDoctrine()->getManager();
            $om->persist($newReponse);
            $om->flush();


            return $this->redirectToRoute('vente_all');
        }
        return $this->render('@entrepot/Default/Vente/Front/reponseForum.html.twig'
            , array(
                'form'=>$form->createView(),'reponse'=>$Forum,'question'=>$question,'val'=>$fo
            ));
    }
}