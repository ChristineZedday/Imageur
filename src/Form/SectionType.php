<?php

namespace App\Form;

use App\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $titres = [];
        foreach ($options['articles'] as $article) {
            $titres[$article->getTitre()] = $article;

        }
        $builder
        ->add('titre',  TextType::class, [
            'required' =>false,
            'attr' => ['size' => '150']])
            ->add('Contenu',  TextareaType::class, ['label' => 'contenu', 'attr' => ['rows' => '15', 'cols' => '100']])
            ->add('article', ChoiceType::class,[
                    'choices' => $titres,
                    'multiple' => false,
                    'mapped' => true,
                    'required' => true])
            ->add('rang')
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
            'articles' => [],
        ]);
    }
}