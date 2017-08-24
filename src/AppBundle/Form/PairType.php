<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PairType
 * @package AppBundle\Form
 */
class PairType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sourceCurrency', EntityType::class, [
                'class' => 'AppBundle:Currency',
                'choice_label' => 'name',
            ])
            ->add('targetCurrency', EntityType::class, [
                'class' => 'AppBundle:Currency',
                'choice_label' => 'name',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'AppBundle\Entity\Pair',
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_pair_type';
    }
}
