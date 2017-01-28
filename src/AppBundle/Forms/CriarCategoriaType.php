<?php
namespace AppBundle\Forms;

use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType};

/**
 * Formulário de criação de uma nova categoria
 *
 * @author Vinicius Dias
 * @package AppBundle\Forms
 */
class CriarCategoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class)
            ->add('salvar', SubmitType::class, ['label' => 'Salvar']);
    }
}
