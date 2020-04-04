<?php

namespace App\Form;

use App\Entity\MagasinSearch;
use App\Entity\optionMagasin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class MagasinSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                'label' => false,
                'required' => false,
            ])
            ->add('options', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => optionMagasin::class,
                'multiple' => true
            ])
            ->add('distance', ChoiceType::class, [
                'choices' => [
                    '10 km' => 10,
                    '100 km' => 100,
                    '1000 km' => 1000
                ]
            ])
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MagasinSearch::class,
            'method' => 'get',
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
