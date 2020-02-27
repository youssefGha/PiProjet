<?php

namespace entrepotBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('motif',ChoiceType::class,[
            'choices' => [
                '--Aucun--'=>'Aucun',
                '- J\'ai reçu un mauvais article' => 'J\'ai reçu un mauvais article',
                '- J\'ai reçu un article different de celui que j\'ai commandé' => 'J\'ai reçu un article different de celui que j\'ai commandé',
                '- J\'ai reçu un article incomplet' =>'J\'ai reçu un article incomplet',
                '- J\'ai reçu un article défectueux / endommagé' =>'J\'ai reçu un article défectueux ',
                '- J\'ai un probleme avec la taille de l\'article' => 'J\'ai un probleme avec la taille de l\'article'
                //'- Je n\'en ai plus l\'utilité / j\'ai changé d\'avis' =>'Je n\'en ai plus l\'utilité / j\'ai changé d\'avis'

            ],
        ])
            ->add('id')
            ->add('contenu');
        //->add('Confirmer',SubmitType::class);
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'entrepotBundle\Entity\Reclamation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'entrepotbundle_reclamation';
    }


}
