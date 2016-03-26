<?php
namespace Yoda\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Yoda\UserBundle\Entity\User;


class RegisterFormType extends AbstractType
{
    const NAME = 'user_register';

    public function getName()
    {
        return self::NAME;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text')
            ->add('email', 'email', [
                'label' => 'Email Address',
                'attr' => ['class' => 'C-3PO']
            ])
            ->add('plainPassword', 'repeated', [
                'type' => 'password'
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
       ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view['email']->vars['help'] = 'Hint: It should have an @ symbol';
    }


}