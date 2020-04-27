<?php

namespace App\Form;

use App\Entity\Sexe;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('description')
            ->add('email')
            ->add('telephone')
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('password', PasswordType::class)
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('adresse')
            ->add('lng', HiddenType::class)
            ->add('lat', HiddenType::class)
            ->add('codePostal')
            ->add('ville')
            ->add('departement')
            ->add('pays')
            ->add('idSexe', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Sexe::class,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
