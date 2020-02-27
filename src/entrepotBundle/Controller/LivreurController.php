<?php


namespace entrepotBundle\Controller;
use entrepotBundle\Entity\Livreur;
use entrepotBundle\Entity\Ligneventes;
use entrepotBundle\Entity\Commandevente;
use entrepotBundle\Form\ClientType;
use entrepotBundle\Form\FournisseurType;
use entrepotBundle\Form\livreureditType;
use entrepotBundle\Form\livreurType;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LivreurController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $livreurs = $em->getRepository('entrepotBundle:Livreur')->findAll();

        return $this->render('@entrepot/Default/Livreur/index.html.twig', array(
            'livreurs' => $livreurs,
        ));


    }

    public function newAction(Request $request)
    {
        $livreur = new Livreur();
        $form = $this->createForm('entrepotBundle\Form\livreurType', $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             *
             */
            $file = $livreur->getImagev();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('image_directory'), $fileName
            );
            $livreur->setImagev($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();

            return $this->redirectToRoute('livreur_show', array('id' => $livreur->getId()));
        }
        return $this->render('@entrepot/Default/Livreur/new.html.twig', array(
            'livreur' => $livreur,
            'form' => $form->createView(),
        ));

    }
    public function showAction(Livreur $livreur)
    {
        $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(['idlivreur'=>$livreur->getId(),'etat'=>"livrée"]);
        $quantite=0;
        $benefice=0;
        foreach ($commandes as $commande)
        {
            $quantite=$quantite+1;
        }

        $benefice=6*$quantite;



        return $this->render('@entrepot/Default/Livreur/show.html.twig', array(
            'livreur' => $livreur,
            'benefice' => $benefice,
            'quantite' => $quantite,


        ));
    }
    public function editAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $livreur= $em->getRepository('entrepotBundle:Livreur')->find($id);
        $editForm=$this->createForm(livreurType::class,$livreur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /**
             * @var UploadedFile $file
             *
             */
            $file=$livreur->getImagev();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('image_directory'),$fileName
            );
            $livreur->setImagev($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
            return $this->redirectToRoute('livreur_index');
        }

        return $this->render('@entrepot/Default/Livreur/edit.html.twig', array(

            'livreur' => $livreur,
            'edit_form' => $editForm->createView(),

        ));
    }



    public function editclientAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $livreur= $em->getRepository('entrepotBundle:Client')->find($id);
        $editForm=$this->createForm(ClientType::class,$livreur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
            return $this->redirectToRoute('entrepot_homepage');
        }

        return $this->render('@entrepot/Default/Livreur/registerclient.html.twig', array(

            'livreur' => $livreur,
            'edit_form' => $editForm->createView(),

        ));
    }
    public function editfournisseurAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $livreur= $em->getRepository('entrepotBundle:Fournisseur')->find($id);
        $editForm=$this->createForm(FournisseurType::class,$livreur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $livreur->setImage("fournisseur3.jpg");
            $em->persist($livreur);
            $em->flush();
            return $this->redirectToRoute('entrepot_homepage');
        }

        return $this->render('@entrepot/Default/Livreur/registerfournisseur.html.twig', array(

            'livreur' => $livreur,
            'edit_form' => $editForm->createView(),

        ));
    }

    public function editfrontAction(Request $request, $id)
{
    $em=$this->getDoctrine()->getManager();
    $livreur= $em->getRepository('entrepotBundle:Livreur')->find($id);
    $editForm=$this->createForm(livreureditType::class,$livreur);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
        /**
         * @var UploadedFile $file
         *
         */
        $file=$livreur->getImagev();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('image_directory'),$fileName
        );
        $livreur->setImagev($fileName);
        $livreur->setRating('0.0');
        $em = $this->getDoctrine()->getManager();
        $em->persist($livreur);
        $em->flush();
        return $this->redirectToRoute('neji_homepage',array('id'=>$livreur->getId()));
    }

    return $this->render('@entrepot/Default/Livreur/editfront.html.twig', array(

        'livreur' => $livreur,
        'edit_form' => $editForm->createView(),

    ));
}
    public function editfrontlivreurAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $livreur= $em->getRepository('entrepotBundle:Livreur')->find($id);
        $editForm=$this->createForm(livreurType::class,$livreur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /**
             * @var UploadedFile $file
             *
             */
            $file=$livreur->getImagev();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('image_directory'),$fileName
            );
            $livreur->setImagev($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
            return $this->redirectToRoute('neji_homepage',array('id'=>$livreur->getId()));
        }
        return $this->render('@entrepot/Default/Livreur/editfrontlivreur.html.twig', array(

        'livreur' => $livreur,
        'edit_form' => $editForm->createView(),

    ));
}
    public function deleteAction($qdt)
    {

        $livreur= new Livreur();

        $livreur = $this->getDoctrine()->getRepository(Livreur::class)->find($qdt);
        $commandes=$this->getDoctrine()->getRepository(Commandevente::class)->findBy(['idlivreur'=>$qdt]);
        $em= $this->getDoctrine()->getManager();
        foreach ($commandes as $commande)
        {
            $ligneventes=$this->getDoctrine()->getRepository(Ligneventes::class)->findBy(['idcommande'=>$commande]);
            foreach ($ligneventes as $lignevente)
            {
                $em->remove($lignevente);
                $em->flush();
            }
            $em->remove($commande);
            $em->flush();
        }
        $em->remove($livreur);
        $em->flush();
        return $this->redirectToRoute('livreur_index');


    }
    public function searchlivreurAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $libelle = $request->get('q');
        $rec = $em->getRepository(Livreur::class)->Searchlivreur($libelle);
        if (!$rec) {
            $result['livreur']['error'] = "Livreur Not found 😞 ";
        } else {
            $result['livreur'] = $this->getRealEntities($rec);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($rec)
    {
        foreach ($rec as $rec) {
            $realEntities[$rec->getId()] = [$rec->getNom(),$rec->getPrenom(),$rec->getCin()];

        }
        return $realEntities;
    }


}