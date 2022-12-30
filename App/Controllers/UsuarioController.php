<?php

namespace App\Controllers;

//Recursos do Mini Framework
use MF\Controller\Action;
use MF\Model\Container;
use App\Controllers\Util\Utilidades;
use App\Controllers\EmailController;
use App\Controllers\GerarPDFController;

class UsuarioController extends Action
{

    //Representam as Action(Métodos)
    public function indexUsuario()
    {
        AuthController::validaAutenticacao();

        $this->carregaUsuarios();

        $this->render('index', 'layout2');
    }


    public function cadastroUsuarioNovo()
    {
        AuthController::validaAutenticacao();

        $usuario = Container::getModel('Usuario');

        //Gerando uma senha aleatória para o Usuário
        $senha = Utilidades::gerarSenha(6, true, true, true, false);

        $usuario->__set('nome', $_POST['nome-usuario']);
        $usuario->__set('cpf', Utilidades::apenasNumeros($_POST['cpf']));
        $usuario->__set('sexo', $_POST['sexo']);
        $usuario->__set('dataNascimento', $_POST['nascimento']);
        $usuario->__set('telefone', Utilidades::apenasNumeros($_POST['telefone']));
        $usuario->__set('email', trim($_POST['email']));
        $usuario->__set('senha', password_hash($senha, PASSWORD_DEFAULT));
        $usuario->__set('nivelAcesso', $_POST['nivel-acesso']);

        $usuario->insert();

        EmailController::enviarEmailSenhaTemporaria($senha, trim($_POST['email']), trim($_POST['nome-usuario']));

        echo json_encode(array(
            'success' => "SUCESSO: {$usuario->__get('nome')} cadastrado com sucesso!",
            'status' => 0
        ));
    }

    public function alteracaoUsuario()
    {

        AuthController::validaAutenticacao();

        $usuario = Container::getModel('Usuario');

        $usuario->__set('id', $_POST['id']);
        $usuario->__set('nome', $_POST['nome-usuario']);
        $usuario->__set('cpf', Utilidades::apenasNumeros($_POST['cpf']));
        $usuario->__set('sexo', $_POST['sexo']);
        $usuario->__set('dataNascimento', $_POST['nascimento']);
        $usuario->__set('telefone', Utilidades::apenasNumeros($_POST['telefone']));
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('nivelAcesso', $_POST['nivel-acesso']);

        $usuario->update();

        echo json_encode(array(
            'success' => "SUCESSO: Usuario alterado com sucesso!",
            'status' => 0
        ));
    }

    public function alteracaoUsuarioLogado()
    {
        AuthController::validaAutenticacao();

        $usuario = Container::getModel('Usuario');

        $usuario->__set('id', $_POST['id']);
        $usuario->__set('nome', $_POST['nome-usuario']);
        $usuario->__set('cpf', Utilidades::apenasNumeros($_POST['cpf']));
        $usuario->__set('sexo', $_POST['sexo']);
        $usuario->__set('dataNascimento', $_POST['nascimento']);
        $usuario->__set('telefone', Utilidades::apenasNumeros($_POST['telefone']));
        $usuario->__set('email', $_POST['email']);
        if (isset($_POST['senha']) && trim($_POST['senha']) != "") {
            $usuario->__set('senha', password_hash($_POST['senha'], PASSWORD_DEFAULT));
        }

        $usuarioAtualizado = $usuario->update();

        //Atualizando a sinformações da sessão
        $_SESSION['id'] = $usuarioAtualizado['id'];
        $_SESSION['nome'] = $usuarioAtualizado['nome'];
        $_SESSION['email'] = $usuarioAtualizado['email'];
        $_SESSION['cpf'] = $usuarioAtualizado['cpf'];
        $_SESSION['telefone'] = $usuarioAtualizado['telefone'];
        $_SESSION['sexo'] = $usuarioAtualizado['sexo'];
        $_SESSION['data_nascimento'] = $usuarioAtualizado['data_nascimento'];

        echo json_encode(array(
            'success' => "SUCESSO: Usuario alterado com sucesso!",
            'status' => 0,
            'infoAtualizada' => $usuarioAtualizado
        ));
    }

    public function exclusaoUsuario()
    {

        AuthController::validaAutenticacao();

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_POST['id']);

        $usuario->delete();

        echo json_encode(array(
            'success' => "SUCESSO: Usuário excluído com sucesso!",
            'status' => 0
        ));
    }

    public function gerarPdfUsuario()
    {
        $nomePesquisa = (isset($_GET['nome'])) ? $_GET['nome'] : "";
        $ordemTipo = (isset($_GET['ordemTipo'])) ? $_GET['ordemTipo'] : 1;
        $ordem = (isset($_GET['ordem'])) ? $_GET['ordem'] : 1;

        $usuario = Container::getModel('Usuario');
        $usuarios = $usuario->selectAll($nomePesquisa, $ordemTipo, $ordem);

        $table = '<table border=1 style="margin:auto;"';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<td align="center">Nome</td>';
        $table .= '<td align="center">Sexo</td>';
        $table .= '<td align="center">Telefone</td>';
        $table .= '<td align="center">Email</td>';
        $table .= '<td align="center">CPF</td>';
        $table .= '<td align="center">N. Acesso</td>';
        $table .= '</tr>';
        $table .= '</thead>';

        foreach ($usuarios as $usuarios => $usuario) {

            $sexo = ($usuario['sexo'] == 1) ? 'M' : 'F';
            $telefone = Utilidades::formataTelefone($usuario['telefone']);
            $cpf = Utilidades::formataCPF($usuario['cpf']);

            $table .= '<tbody>';

            $table .= '<tr><td>' . $usuario['nome'] . '</td>';
            $table .= '<td>' . $sexo . '</td>';
            $table .= '<td>' . $telefone . '</td>';
            $table .= '<td>' . $usuario['email'] . '</td>';
            $table .= '<td>' . $cpf . '</td>';
            $table .= '<td>' . Utilidades::formataNivelAcesso($usuario['nivel_acesso']) . '</td>';

            $table .= '</tbody>';
        }

        $table .= '</table>';

        GerarPDFController::gerarPdf($table, 'Usuário');
    }

    public function recuperarSenha()
    {

        $emailRecuperacao = trim($_POST['email']);

        if (isset($emailRecuperacao)) {
            $usuario = Container::getModel('Usuario');

            $usuario->__set('email', $emailRecuperacao); 
            $usuarioRecuperar = $usuario->selectUsuarioByEmail();
            
            if ($usuarioRecuperar['id'] != '') {

                $usuario->__set('id', $usuarioRecuperar['id']);

                //Gerando uma senha aleatória para o Usuário
                $senha = Utilidades::gerarSenha(6, true, true, true, false);

                //Definindo o fuso
                date_default_timezone_set('America/Sao_Paulo');
                $usuario->setarTokenSenha(password_hash($senha, PASSWORD_DEFAULT), date('Y-m-d H:i:s'), date('Y-m-d H:i:s', strtotime('+1 hours')));

                EmailController::enviarEmailSenhaRecuperar($senha, trim($_POST['email']), $usuarioRecuperar['nome']);
                
                echo json_encode(array(
                    'status' => 0,
                    'msg' => "Um token de verificação foi enviado ao seu email!"
                ));

            } else {
                echo json_encode(array(
                    'status' => 1,
                    'msg' => "Não há nenhum usuario com este email!"
                ));
            }
    
        }
    }

    public function verificaDuplicacaoEmail()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['email'])) {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('email', trim($_POST['email']));

            //0-False(Não existe) 1-True(Existe)
            $status = ($usuario->duplicacaoEmail()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificaDuplicacaoAlterarEmail()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['email']) && isset($_POST['id-usuario'])) {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('email', trim($_POST['email']));
            $usuario->__set('id', $_POST['id-usuario']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($usuario->duplicacaoAlterarEmail()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificaDuplicacaoCpf()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['cpf'])) {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('cpf', trim(Utilidades::apenasNumeros($_POST['cpf'])));

            //0-False(Não existe) 1-True(Existe)
            $status = ($usuario->duplicacaoCpf()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificaDuplicacaoAlterarCpf()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['cpf']) && isset($_POST['id-usuario'])) {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('cpf', trim(Utilidades::apenasNumeros($_POST['cpf'])));
            $usuario->__set('id', $_POST['id-usuario']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($usuario->duplicacaoAlterarCpf()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function atualizaTabelaUsuario()
    {
        AuthController::validaAutenticacao();

        $pagina = $_POST['pagina'];
        $qnt_result_pg = $_POST['qtdPagForm'];

        //calcular o inicio visualização
        $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

        $Usuario = Container::getModel('Usuario');
        $usuarios = $Usuario->selectOrderName(trim($_POST['nome-pesquisa']), $_POST['select-ordem'], $_POST['select-ordem-tipo'], $inicio, $qnt_result_pg);

        $table = '<tr>';
        $numeroRefModal = 1;

        foreach ($usuarios as $usuarios => $usuario) {

            $sexo = ($usuario['sexo'] == 1) ? 'Masculino' : 'Feminino';

            //Verificando o nível de acesso
            $nivelAcesso = '';
            switch ($usuario['nivel_acesso']) {
                case 1:
                    $nivelAcesso = '1 - Root';
                    break;

                case 2:
                    $nivelAcesso = '2 - Total';
                    break;

                case 3:
                    $nivelAcesso = '3 - Parcial';
                    break;
            }

            $dataNascimento = Utilidades::formataData($usuario['data_nascimento']);
            $telefone = Utilidades::formataTelefone($usuario['telefone']);
            $cpf = Utilidades::formataCPF($usuario['cpf']);

            $style = ($usuario['id'] == $_SESSION['id']) ? 'color: #00ff88;' : '';

            $table .= '<td style="' . $style . '">' . $usuario["id"] . '</td>';
            $table .= '<td style="' . $style . '">' . $usuario['nome'] . '</td>';
            $table .= '<td style="' . $style . '">' . $sexo . '</td>';
            $table .= '<td style="' . $style . '">' . $dataNascimento . '</td>';
            $table .= '<td style="' . $style . '">' . $telefone . '</td>';
            $table .= '<td style="' . $style . '">' . $usuario['email'] . '</td>';
            $table .= '<td style="' . $style . '">' . $cpf . '</td>';
            $table .= '<td style="' . $style . '">' . $nivelAcesso . '</td>';

            //Usuários do tipo parcial, não podem excluir nem alterar outros usuários
            if ($_SESSION['nivel_acesso'] <= 2) {

                //Para ninguém conseguir alterar as informações do usuario Root
                if ($_SESSION['nivel_acesso'] != 1 && $usuario["nivel_acesso"] == 1) {
                    $table .= '<td class="d-flex" style="' . $style . '">';

                    $table .= '<button type="button" class="btn button-secondary-outline btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modalAlterarUsuario' . $numeroRefModal . '">';
                    $table .= '<i class="fa-solid fa-ban"></i>';
                    $table .= '</button>';

                    $table .= '</td>';
                } else {
                    $table .= '<td class="d-flex" style="' . $style . '">';

                    //Aqui vai verificar se esta listando o Usuário que esta logado, caso positive ele vai abrir um modal específico 
                    if ($_SESSION['id'] == $usuario["id"]) {
                        $table .= '<button type="button" class="btn button-secondary-outline btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modalAlterarUsuarioLogado">';
                        $table .= '<i class="far fa-edit"></i>';
                        $table .= '</button>';
                    } else {
                        $table .= '<button type="button" class="btn button-secondary-outline btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modalAlterarUsuario' . $numeroRefModal . '">';
                        $table .= '<i class="far fa-edit"></i>';
                        $table .= '</button>';
                    }

                    $table .= '<div class="modal fade modal-lg" id="modalAlterarUsuario' . $numeroRefModal . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                    $table .= '<div class="modal-dialog">';
                    $table .= '<div class="modal-content">';
                    $table .= '<div class="modal-header">';
                    $table .= '<h1 class="modal-title fs-5" id="exampleModalLabel">Alterar Usuario</h1>';
                    $table .= '<button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close" onclick="cancelarEdicao()"></button>';
                    $table .= '</div>';
                    $table .= '<div class="modal-body">';
                    $table .= '<form id="formAlterarUsuario' . $numeroRefModal . '">';
                    $table .= '<div class="row input-wrapper">';
                    $table .= '<input type="hidden" name="id" id="id" value="' . $usuario['id'] . '">';

                    $table .= '<div class="col-md-4 mb-3">';
                    $table .= '<label for="nome" class="form-label">Nome</label>';
                    $table .= '<input type="text" class="form-control" id="nome-usuario-alterar-' . $numeroRefModal . '" name="nome-usuario" value="' . $usuario['nome'] . '">';
                    $table .= '<small id="text-nome-usuario-alterar-' . $numeroRefModal . '"></small>';
                    $table .= '</div>';

                    $table .= '<div class="col-md-4 mb-3">';
                    $table .= '<label for="cpf" class="form-label">CPF</label>';
                    $table .= '<input type="text" class="form-control cpf-mask" id="cpf-alterar-' . $numeroRefModal . '" name="cpf" value="' . $cpf . '">';
                    $table .= '<small id="text-cpf-alterar-' . $numeroRefModal . '"></small>';
                    $table .= '</div>';

                    $table .= '<div class="col-md-4 mb-3">';
                    $table .= '<label for="sexo" class="form-label">Sexo</label>';
                    $table .= '<select class="form-select" id="sexo-alterar-' . $numeroRefModal . '" name="sexo" aria-label="Default select example">';
                    $table .= '<option value="2" ' . ($sexo == 'Feminino' ? 'selected' : '') . '>Feminino</option>';
                    $table .= '<option value="1" ' . ($sexo == 'Masculino' ? 'selected' : '') . '>Masculino</option>';
                    $table .= '</select>';
                    $table .= '<small id="text-sexo-alterar-' . $numeroRefModal . '"></small>';
                    $table .= '</div>';

                    $table .= '<div class="col-md-4 col-xl-4 mb-3">';
                    $table .= '<label for="data_nascimento" class="form-label">Data Nascimento</label>';
                    $table .= '<input type="date" class="form-control" id="nascimento-alterar-' . $numeroRefModal . '" name="nascimento" value="' . $usuario['data_nascimento'] . '">';
                    $table .= '<small id="text-nascimento-alterar-' . $numeroRefModal . '"></small>';
                    $table .= '</div>';

                    $table .= '<div class="col-md-4 mb-3">';
                    $table .= '<label for="telefone" class="form-label">Telefone</label>';
                    $table .= '<input type="tel" class="form-control telefone-mask" id="telefone-alterar-' . $numeroRefModal . '" name="telefone" value="' . $telefone . '">';
                    $table .= '<small id="text-telefone-alterar-' . $numeroRefModal . '"></small>';
                    $table .= '</div>';

                    $table .= '<div class="col-md-4 mb-3">';
                    $table .= '<label for="email" class="form-label">Email</label>';
                    $table .= '<input type="email" class="form-control" id="email-alterar-' . $numeroRefModal . '" name="email" value="' . $usuario['email'] . '">';
                    $table .= '<small id="text-email-alterar-' . $numeroRefModal . '"></small>';
                    $table .= '</div>';

                    //Só quem pode mudar os níveis de acesso é o usuário Root e nivel 2 caso o usário seja nível 1
                    if (($_SESSION['nivel_acesso'] == 1) || ($_SESSION['nivel_acesso'] == 2 && $usuario['nivel_acesso'] == 3)) {
                        $table .= '<div class="col-md-4 mb-3">';
                        $table .= '<label for="nivel-acesso" class="form-label">Nível de acesso</label>';
                        $table .= '<select class="form-select" id="nivel-acesso-alterar-' . $numeroRefModal . '" name="nivel-acesso" aria-label="Default select example">';
                        $table .= '<option value="2" ' . ($usuario['nivel_acesso'] == 2 ? 'selected' : '') . '>Total</option>';
                        $table .= '<option value="3" ' . ($usuario['nivel_acesso'] == 3 ? 'selected' : '') . '>Parcial</option>';
                        $table .= '</select>';
                        $table .= '<small id="text-nivel-acesso-alterar-' . $numeroRefModal . '"></small>';
                        $table .= '</div>';
                    }

                    $table .= '<div class="row">';
                    $table .= '<div class="mb-3 d-flex justify-content-end">';
                    $table .= '<button type="button" class="btn button-secondary-outline" name="alterar" onclick="alteracaoUsuario(' . $numeroRefModal . ', formAlterarUsuario' . $numeroRefModal . ', modalAlterarUsuario' . $numeroRefModal . ',' . $pagina . ',' . $usuario['id'] . ')">';
                    $table .= '<i class="far fa-plus-square"></i> Alterar';
                    $table .= '</button>';

                    $table .= '<button type="button" class="btn button-primary-outline mx-3" data-bs-dismiss="modal" onclick="cancelarEdicao()">';
                    $table .= '<i class="fa-solid fa-arrow-left"></i> Cancelar';
                    $table .= '</button>';
                    $table .= '</div>';
                    $table .= '</div>';

                    $table .= '</div>';
                    $table .= '</form>';
                    $table .= '</div>';
                    $table .= '<div class="modal-footer">';

                    $table .= '</div>';
                    $table .= '</div>';

                    $table .= '</div>';
                    $table .= '</div>';

                    //Só quem pode excluir Usuários é o o Usuário Root
                    if ($_SESSION['nivel_acesso'] == 1 && $usuario["id"] != $_SESSION['id']) {

                        $formExclusao = "'#form-excluir-usuario-{$numeroRefModal}'";

                        $table .= '<form id="form-excluir-usuario-' . $numeroRefModal . '" action="/usuario/excluir" method="post">';
                        $table .= '<input type="hidden" name="id" id="id" value="' . $usuario['id'] . '?>">';
                        $table .= '<button type="button" name="excluir-usuario" id="excluir-usuario" value="excluir" class="btn button-primary-outline btn-sm mx-1" onclick="exclusaoUsuario(' . $formExclusao . ',' . $pagina . ')">';
                        $table .= '<i class="far fa-trash-alt"></i>';
                        $table .= '</button>';
                        $table .= '</form>';
                    }

                    $table .= '</td>';
                }
            }
            $table .= '</tr>';

            $numeroRefModal++;
        }

        //Quantidade de pagina
        $quantidadeUsuarios = $Usuario->countUsuarios(trim($_POST['nome-pesquisa']));
        $quantidade_pg = ceil($quantidadeUsuarios['total'] / $qnt_result_pg);

        $paginacao = "";

        if ($quantidadeUsuarios['total'] > $qnt_result_pg) {
            $anterior = ($pagina > 1) ? $pagina - 1 : $pagina;
            $proxima = ($pagina < $quantidade_pg) ? $pagina + 1 : $pagina;

            $paginacao .= '<li class="page-item">
                <a class="page-link bg-page-link" aria-label="Next" onclick="atualizaTabela(' . $anterior . ')">
                    <span aria-hidden="true">&laquo</span>
                </a>
            </li>';

            if ($quantidade_pg > 10) { //Caso for necessário mais de 10 páginas para listar                

                //Só Deus e eu sabe o que foi feito aqui, agora só ele
                if ($pagina >= 5 && $pagina < $quantidade_pg - 5) {
                    $primeiraPag = $pagina - 3;
                    $paginaFinal = $pagina + 1;
                } else if ($pagina >= $quantidade_pg - 5) {
                    $primeiraPag = ($quantidade_pg - 4) - 5;
                    $paginaFinal = $quantidade_pg - 5;
                } else {
                    $primeiraPag = 1;
                    $paginaFinal = 5;
                }

                for ($pag = $primeiraPag; $pag <= $paginaFinal; $pag++) {
                    $active = ($pag == $pagina) ? 'bg-page-link-active' : '';
                    $paginacao .= '<li class="page-item"><a class="page-link bg-page-link ' . $active . '" onclick="atualizaTabela(' . $pag . ')">' . $pag . '</a></li>';
                }

                $paginacao .= '<li class="page-item disabled"><a class="page-link bg-page-link ">...</a></li>';

                //Listando as 5 ultimas paginas
                for ($pag = $quantidade_pg - 4; $pag <= $quantidade_pg; $pag++) {
                    $active = ($pag == $pagina) ? 'bg-page-link-active' : '';
                    $paginacao .= '<li class="page-item"><a class="page-link bg-page-link ' . $active . '" onclick="atualizaTabela(' . $pag . ')">' . $pag . '</a></li>';
                }
            } else {
                for ($pag = 1; $pag <= $quantidade_pg; $pag++) {
                    $active = ($pag == $pagina) ? 'bg-page-link-active' : '';
                    $paginacao .= '<li class="page-item"><a class="page-link bg-page-link ' . $active . '" onclick="atualizaTabela(' . $pag . ')">' . $pag . '</a></li>';
                }
            }

            $paginacao .= '<li class="page-item">
                <a class="page-link bg-page-link" aria-label="Next" onclick="atualizaTabela(' . $proxima . ')">
                    <span aria-hidden="true">&raquo</span>
                </a>
            </li>';
        }

        echo json_encode(array(
            'table' => $table,
            'qtdResultados' => $quantidadeUsuarios['total'],
            'paginacao' =>  $paginacao,
            'ordemTipo' =>  $_POST['select-ordem-tipo'],
            'ordem' => $_POST['select-ordem'],
            'nome' => trim($_POST['nome-pesquisa'])
        ));
        
    }

    public function carregaUsuarios()
    {
        $usuario = Container::getModel('Usuario');
        $usuarios = $usuario->selectAll();
        $totalUsuarios = $usuario->countUsuarios();


        $this->view->usuarios = $usuarios;
        $this->view->totalUsuarios = $totalUsuarios;

        return $usuarios;
    }
}
