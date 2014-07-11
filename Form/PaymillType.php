<?php

namespace Memeoirs\PaymillBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Validator\Constraints\NotBlank,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\Translation\TranslatorInterface;

class PaymillType extends AbstractType
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface translator
     *
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tokenNotBlank = new NotBlank(array(
            'message' => $this->translator->trans('default', array(), 'errors')
        ));

        $builder
            ->add('number' , 'text',   array('required' => false, 'label' => 'Card number'))
            ->add('expiry' , 'text',   array('required' => false, 'label' => 'Expires'))
            ->add('holder' , 'text',   array('required' => false, 'label' => 'Name on card'))
            ->add('cvc'    , 'text',   array('required' => false, 'label' => 'Card code'))
            ->add('token'  , 'hidden', array(
                'required' => false,
                'constraints' => array($tokenNotBlank)
            ))
        ;
    }

    /**
     *
     * @param OptionsResolverInterface $resolver
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                $data = $form->getParent()->getData();

                // Perform validation only if the payment method is Paymill
                return $data->getPaymentSystemName() === $this->getName()
                    ? array('Default')
                    : array();
            }
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'paymill';
    }
}