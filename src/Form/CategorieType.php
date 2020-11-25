<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $couleurs = [
            'Vert'  =>  'success',
            'Bleu Foncé'    =>  'primary',
            'Bleu Clair'    =>  'info',
            'Rouge'         =>  'danger',
            'Jaune'         =>  'warning',
            'Gris'          =>  'secondary'
        ];
        $builder
            ->add('libelle', TextType::class, [
                'label'     =>  'Libellé *',
                'attr'      =>  array(
                    'class' =>  'form-control'
                )
            ])
            ->add('couleur', ChoiceType::class, [
                'attr'      =>  array(
                    'class' =>  'form-control'
                ),
                'choices'   =>  $couleurs
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
