<?php

namespace App\Form;

use App\Entity\Article;
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

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label'     =>  'Titre *',
                'attr'      =>  array(
                    'class' =>  'form-control'
                )
            ])
            ->add('contenu', TextareaType::class, [
                'label'     =>  'Contenu *',
                'attr'      =>  array(
                    'class' => 'form-control'
                )
            ])
            ->add('categorie', EntityType::class, [
                'label'         =>  'Catégorie *',
                'attr'          =>  array(
                    'class'     => 'form-control'
                ),
                'class'         =>  Categorie::class,
                'choice_label'  =>  'libelle'
            ])
            ->add('image', FileType::class, [
                'label'         =>  'Image',
                'required'      =>  false,
                'data_class'    =>  null,
                'mapped'        =>  false,
                'attr'          =>  array(
                    'class'     => 'form-control'
                ),
            ])
            ->add('online', ChoiceType::class, [
                'label' =>  'En ligne *',
                'choices'   =>  [
                    'Oui'   =>  true,
                    'Non'   =>  false
                ],
                'required'  =>  true,
                'expanded'  =>  true,
                'multiple'  =>  false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
