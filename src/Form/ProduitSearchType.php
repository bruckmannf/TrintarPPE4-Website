<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\ProduitSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Categorie::class,
                'multiple' => true
            ])
            ->add('libelle', null, [
                'label' => false,
                'required' => false,
            ])
            ->add('auteurs', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Auteur::class,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProduitSearch::class,
            'method' => 'get',
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
