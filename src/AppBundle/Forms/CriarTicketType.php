<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\{
    SubmitType, TextareaType, TextType
};

/**
 * Formulário de abertura de novo ticket
 *
 * @author Vinicius Dias
 * @package AppBundle\Forms
 */
class CriarTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', TextType::class)
            ->add('descricao', TextareaType::class, ['required' => false])
            ->add(
                'categoria',
                EntityType::class,
                [
                    'class' => 'AppBundle:Categoria',
                    'choice_label' => 'nome',
                    'placeholder' => 'Selecione'
                ]
            )->add('salvar', SubmitType::class, ['label' => 'Salvar']);
    }
}
