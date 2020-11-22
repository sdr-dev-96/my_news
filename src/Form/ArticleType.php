<?php

namespace App\Form;

use App\Entity\Article;
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

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label'     =>  'Titre',
                'attr'      => array(
                    'class' => 'form-control'
                )
            ])
            ->add('contenu', TextareaType::class, [
                'label'     =>  'Contenu',
                'attr'      => array(
                    'class' => 'form-control'
                )
            ])
            ->add('image', FileType::class, [
                'label'     =>  'Image',
                'attr'      => array(
                    'class' => 'form-control'
                )
            ])
            ->add('ecrivain', TextType::class, [
                'label'     =>  'Ecrivain',
                'disabled'  => true,
                'attr'      => array(
                    'class' => 'form-control'
                )
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
