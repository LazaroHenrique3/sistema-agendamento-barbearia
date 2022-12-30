<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use App\Controllers\Util\Utilidades;

class AuthController extends Action
{

    public function autenticar()
    {

        session_start();

        $usuario = Container::getModel('Usuario');

        //addslashes troca caracteres especiais por \
        $email = addslashes($_POST['email-login']);
        $senha = addslashes($_POST['senha']);

        $usuario->__set('email', $email);
        $usuario->__set('senha', $senha);

        //Recupera e Carrega o Id e nivel de Acesso
        $usuario->authenticate();
  
        if ($usuario->__get('id') != '' && $usuario->__get('nome') != '') {

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');
            $_SESSION['email'] = $usuario->__get('email');
            $_SESSION['cpf'] = $usuario->__get('cpf');
            $_SESSION['telefone'] = $usuario->__get('telefone');
            $_SESSION['sexo'] = $usuario->__get('sexo');
            $_SESSION['data_nascimento'] = $usuario->__get('dataNascimento');
            $_SESSION['nivel_acesso'] = $usuario->__get('nivelAcesso');

            //Solicita o acesso a página privada
            header('Location: /dashboard');
        } else {
            header("Location: /?msg=Erro: Email e/ou senha inválido(s)!&status=danger");
        }
    
    }

    public static function validaAutenticacao()
    {
        session_start();

        if (!isset($_SESSION['nome']) || $_SESSION['nome'] == '' || !isset($_SESSION['id']) || $_SESSION['id'] == '') {
            header("Location: /?msg=Erro: Usuário não autenticado!&status=danger");
        }
    }

    public function sair()
    {
        session_start();
        session_destroy();
        header("Location: /");
    }
}
