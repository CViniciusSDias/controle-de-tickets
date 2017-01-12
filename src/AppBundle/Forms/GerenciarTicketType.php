<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\{AbstractType,FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\{
    CheckboxType, IntegerType, TextType, SubmitType
};

class GerenciarTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aberto', CheckboxType::class, ['required' => false])
            ->add('prioridade', IntegerType::class)
            ->add('resposta', TextType::class)
            ->add('salvar', SubmitType::class, ['label' => 'Salvar']);
    }
}