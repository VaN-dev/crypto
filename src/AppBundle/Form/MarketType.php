<?php

namespace AppBundle\Form;

use AppBundle\Entity\Pair;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MarketType
 * @package AppBundle\Form
 */
class MarketType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * MarketType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('enabled')
            ->add('pairs', EntityType::class, [
                'class' => 'AppBundle\Entity\Pair',
                'choice_label' => function($value) {
                    /**
                     * @var Pair $value
                     */
                    return $value->getSourceCurrency()->getName() . ' / ' . $value->getTargetCurrency()->getName();
                },
                'mapped' => false,
                'multiple' => true,
                'expanded' => true,
//                'choice_attr' => [
//                    'class' => 'col-sm-4',
//                ],
//                'label_attr' => [
//                    'class' => 'col-sm-4',
//                ],
                'data' => $this->getValues($builder),
            ])
        ;
    }

    private function getValues($builder)
    {
        $t = $this->em->getRepository("AppBundle:Pair")->fetchPairsByMarket($builder->getData());

        return $t;

//        return $this->em->getRepository("AppBundle:MarketPair")->findBy(["market" => null]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'AppBundle\Entity\Market',
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_market_type';
    }
}
