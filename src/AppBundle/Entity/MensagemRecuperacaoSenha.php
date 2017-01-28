<?php
namespace AppBundle\Entity;

use Swift_Message;

/**
 * Classe que representa a mensagem de e-mail para recuperação de senha
 *
 * @author Vinicius Dias
 * @package AppBundle\Entity
 */
class MensagemRecuperacaoSenha extends Swift_Message
{
    public static function newInstance($subject = null, $body = null, $contentType = null, $charset = null)
    {
        $subject = 'Recuperação de senha';
        $body = '';
        $contentType = 'text/html';
        $charset = 'UTF-8';

        return parent::newInstance($subject, $body, $contentType, $charset);
    }
}
