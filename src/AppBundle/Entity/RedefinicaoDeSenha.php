<?php
namespace AppBundle\Entity;

/**
 * Classe que representa uma redefinição de senha
 *
 * @author Vinicius Dias
 * @package AppBundle\Entity
 */
class RedefinicaoDeSenha
{
    private $senhaAtual;
    private $novaSenha;

    /**
     * @return string
     */
    public function getSenhaAtual(): ?string
    {
        return $this->senhaAtual;
    }

    /**
     * @param string $senhaAtual
     * @return RedefinicaoDeSenha
     */
    public function setSenhaAtual(string $senhaAtual): self
    {
        $this->senhaAtual = $senhaAtual;
        return $this;
    }

    /**
     * @return string
     */
    public function getNovaSenha(): ?string
    {
        return $this->novaSenha;
    }

    /**
     * @param string $novaSenha
     * @return RedefinicaoDeSenha
     */
    public function setNovaSenha(string $novaSenha): self
    {
        $this->novaSenha = $novaSenha;
        return $this;
    }
}
