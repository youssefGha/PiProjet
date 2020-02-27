<?php


namespace entrepotBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class livreureditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('matriculevoiture')
            ->add('assurance')
            ->add('entretien')
            ->add('nom')
            ->add('prenom')
            ->add('cin')
            ->add('adresse')
            ->add('numtel')

            ->add('imagev',FileType::class, array(
            'data_class' => null ));


    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'entrepotBundle\Entity\Livreur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'entrepotbundle_livreur';
    }


}