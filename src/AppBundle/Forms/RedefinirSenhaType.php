<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    PasswordType, RepeatedType, SubmitType
};
use Symfony\Component\Form\FormBuilderInterface;

class RedefinirSenhaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('novaSenha', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Os valores devem ser iguais',
                'required' => true,
                'first_options'  => ['label' => false, 'attr' => ['placeholder' => 'Digite sua nova senha']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Repita sua nova senha']],
                'mapped' => false
            ])
            ->add('salvar', SubmitType::class, [
                'label' => 'Salvar',
                'attr' => [
                    'class' => 'btn btn-primary btn-block btn-flat'
                ]
            ]);
    }
}
