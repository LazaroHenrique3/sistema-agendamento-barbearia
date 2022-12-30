<?php

namespace App\Controllers;

//Recursos do Mini Framework
use MF\Controller\Action;
use MF\Model\Container;
use App\Controllers\Util\Utilidades;
use App\Controllers\GerarPDFController;

class ClienteController extends Action
{

    //Representam as Action(Métodos)
    public function indexCliente()
    {
        AuthController::validaAutenticacao();

        $this->carregaClientes();

        $this->render('index', 'layout2');
    }

    public function cadastroClienteNovo()
    {
        AuthController::validaAutenticacao();

        $cliente = Container::getModel('Cliente');
        $cliente->__set('nome', $_POST['nome-cliente']);
        $cliente->__set('cpf', Utilidades::apenasNumeros($_POST['cpf']));
        $cliente->__set('sexo', $_POST['sexo']);
        $cliente->__set('dataNascimento', $_POST['nascimento']);
        $cliente->__set('telefone', Utilidades::apenasNumeros($_POST['telefone']));
        $cliente->__set('email', $_POST['email']);
        $cliente->__set('endereco', $_POST['endereco']);
        $cliente->__set('cep', Utilidades::apenasNumeros($_POST['cep']));

        $cliente->insert();

        echo json_encode(array(
            'success' => "SUCESSO: {$cliente->__get('nome')} cadastrado com sucesso!",
            'status' => 0
        ));
    }

    public function alteracaoCliente()
    {
        AuthController::validaAutenticacao();

        $cliente = Container::getModel('Cliente');

        $cliente->__set('id', $_POST['id']);
        $cliente->__set('nome', $_POST['nome-cliente']);
        $cliente->__set('cpf', Utilidades::apenasNumeros($_POST['cpf']));
        $cliente->__set('sexo', $_POST['sexo']);
        $cliente->__set('dataNascimento', $_POST['nascimento']);
        $cliente->__set('telefone', Utilidades::apenasNumeros($_POST['telefone']));
        $cliente->__set('email', $_POST['email']);
        $cliente->__set('endereco', $_POST['endereco']);
        $cliente->__set('cep', Utilidades::apenasNumeros($_POST['cep']));

        $cliente->update();

        echo json_encode(array(
            'success' => "SUCESSO: Cliente alterado com sucesso!",
            'status' => 0
        ));
    }

    public function exclusaoCliente()
    {

        AuthController::validaAutenticacao();

        $cliente = Container::getModel('Cliente');
        $cliente->__set('id', $_POST['id']);

        $cliente->delete();

        echo json_encode(array(
            'success' => "SUCESSO: Cliente excluído com sucesso!",
            'status' => 0
        ));
    }

    public function gerarPdfCliente()
    {
        $nomePesquisa = (isset($_GET['nome'])) ? $_GET['nome'] : "";
        $ordemTipo = (isset($_GET['ordemTipo'])) ? $_GET['ordemTipo'] : 1;
        $ordem = (isset($_GET['ordem'])) ? $_GET['ordem'] : 1;

        $cliente = Container::getModel('Cliente');
        $clientes = $cliente->selectAll($nomePesquisa, $ordemTipo, $ordem);

        $table = '<table border=1 style="margin:auto;"';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<td align="center">Nome</td>';
        $table .= '<td align="center">Sexo</td>';
        $table .= '<td align="center">Telefone</td>';
        $table .= '<td align="center">Email</td>';
        $table .= '<td align="center">CPF</td>';
        $table .= '<td align="center">Endereço</td>';
        $table .= '</tr>';
        $table .= '</thead>';

        foreach ($clientes as $clientes => $cliente) {

            $sexo = ($cliente['sexo'] == 1) ? 'M' : 'F';
            $dataNascimento = Utilidades::formataData($cliente['data_nascimento']);
            $telefone = Utilidades::formataTelefone($cliente['telefone']);
            $cpf = Utilidades::formataCPF($cliente['cpf']);
            $cep = Utilidades::formataCEP($cliente['cep']);

            $table .= '<tbody>';

            $table .= '<tr><td>' . $cliente['nome'] . '</td>';
            $table .= '<td>' . $sexo . '</td>';
            $table .= '<td>' . $telefone . '</td>';
            $table .= '<td>' . $cliente['email'] . '</td>';
            $table .= '<td>' . $cpf . '</td>';
            $table .= '<td>' . $cliente['endereco'] . '</td>';

            $table .= '</tbody>';
        }

        $table .= '</table>';

        GerarPDFController::gerarPdf($table, 'Cliente');
    }

    public function verificaDuplicacaoEmail()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['email'])) {
            $cliente = Container::getModel('Cliente');
            $cliente->__set('email', trim($_POST['email']));

            //0-False(Não existe) 1-True(Existe)
            $status = ($cliente->duplicacaoEmail()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificaDuplicacaoAlterarEmail()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['email']) && isset($_POST['id-cliente'])) {
            $cliente = Container::getModel('Cliente');
            $cliente->__set('email', trim($_POST['email']));
            $cliente->__set('id', $_POST['id-cliente']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($cliente->duplicacaoAlterarEmail()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }


    public function verificaDuplicacaoCpf()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['cpf'])) {
            $cliente = Container::getModel('Cliente');
            $cliente->__set('cpf', trim(Utilidades::apenasNumeros($_POST['cpf'])));

            //0-False(Não existe) 1-True(Existe)
            $status = ($cliente->duplicacaoCpf()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificaDuplicacaoAlterarCpf()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['cpf']) && isset($_POST['id-cliente'])) {
            $cliente = Container::getModel('Cliente');
            $cliente->__set('cpf', trim(Utilidades::apenasNumeros($_POST['cpf'])));
            $cliente->__set('id', $_POST['id-cliente']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($cliente->duplicacaoAlterarCpf()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    //Gera a tabela de pesquisa de cliente no Agendamento
    public function buscarPesquisaAgendamentoCliente()
    {

        if (isset($_POST['nome-pesquisa']) && $_POST['nome-pesquisa'] != '') {

            $Cliente = Container::getModel('Cliente');
            $clientes = $Cliente->selectAll(trim($_POST['nome-pesquisa']));

            $table = '<tr>';

            foreach ($clientes as $clientes => $cliente) {
                $table .= '<td style="padding: 5px; width:100%;">' . $cliente["nome"] . '</td>';

                $table .= '<td class="d-flex justify-content-center" style="padding: 5px; width:100%;">';

                $atributos =  "'{$cliente["nome"]}', {$cliente["id"]}, '{$_POST["tipo-pesquisa"]}', '{$cliente["email"]}'";

                $table .= '<button type="button" class="btn button-secondary-outline btn-sm mx-1" onclick="selecionarClienteAgendamento(' . $atributos . ')">';
                $table .= '<i class="fa-solid fa-square-check"></i>';
                $table .= '</button>';

                $telefone = Utilidades::formataTelefone($cliente["telefone"]);
                $infoCliente = "Telefone: {$telefone} <br> Email: {$cliente["email"]} <br> Endereço: {$cliente["endereco"]}";

                $table .= '<a tabindex="0" class="btn button-primary-outline btn-sm mx-1" role="button" data-bs-html="true" data-bs-sanitize="false" data-bs-toggle="popover" data-bs-trigger="focus" title="Informações do Cliente" data-bs-content="' . $infoCliente . '">';
                $table .= '<i class="fa-solid fa-circle-info"></i>';
                $table .= '</a>';

                $table .= '</td>';
                $table .= '</tr>';
            }

            echo json_encode(array(
                'table' => $table
            ));
        } else {
            echo json_encode(array(
                'table' => ''
            ));
        }
    }

    public function atualizaTabelaCliente()
    {
        AuthController::validaAutenticacao();

        $pagina = $_POST['pagina'];
        $qnt_result_pg = $_POST['qtdPagForm'];

        //calcular o inicio visualização
        $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

        $Cliente = Container::getModel('Cliente');
        $clientes = $Cliente->selectOrderName(trim($_POST['nome-pesquisa']), $_POST['select-ordem'], $_POST['select-ordem-tipo'], $inicio, $qnt_result_pg);

        $table = '<tr>';
        $numeroRefModal = 1;

        foreach ($clientes as $clientes => $cliente) {

            $sexo = ($cliente['sexo'] == 1) ? 'Masculino' : 'Feminino';
            $dataNascimento = Utilidades::formataData($cliente['data_nascimento']);
            $telefone = Utilidades::formataTelefone($cliente['telefone']);
            $cpf = Utilidades::formataCPF($cliente['cpf']);
            $cep = Utilidades::formataCEP($cliente['cep']);

            $table .= '<td>' . $cliente["id"] . '</td>';
            $table .= '<td>' . $cliente['nome'] . '</td>';
            $table .= '<td>' . $sexo . '</td>';
            $table .= '<td>' . $dataNascimento . '</td>';
            $table .= '<td>' . $telefone . '</td>';
            $table .= '<td>' . $cliente['email'] . '</td>';
            $table .= '<td>' . $cpf . '</td>';
            $table .= '<td>' . $cliente['endereco'] . '</td>';
            $table .= '<td>' . $cep . '</td>';

            $table .= '<td class="d-flex">';

            $table .= '<button type="button" class="btn button-secondary-outline btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modalAlterarCliente' . $numeroRefModal . '">';
            $table .= '<i class="far fa-edit"></i>';
            $table .= '</button>';

            $table .= '<div class="modal fade modal-lg" id="modalAlterarCliente' . $numeroRefModal . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
            $table .= '<div class="modal-dialog">';
            $table .= '<div class="modal-content">';
            $table .= '<div class="modal-header">';
            $table .= '<h1 class="modal-title fs-5" id="exampleModalLabel">Alterar Cliente</h1>';
            $table .= '<button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"></button>';
            $table .= '</div>';
            $table .= '<div class="modal-body">';
            $table .= '<form id="formAlterarCliente' . $numeroRefModal . '">';
            $table .= '<div class="row input-wrapper">';
            $table .= '<input type="hidden" name="id" id="id" value="' . $cliente['id'] . '">';

            $table .= '<div class="col-md-4 mb-3">';
            $table .= '<label for="nome" class="form-label">Nome</label>';
            $table .= '<input type="text" class="form-control" id="nome-cliente-alterar-' . $numeroRefModal . '" name="nome-cliente" value="' . $cliente['nome'] . '">';
            $table .= '<small id="text-nome-cliente-alterar-' . $numeroRefModal . '"></small>';
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
            $table .= '<input type="date" class="form-control" id="nascimento-alterar-' . $numeroRefModal . '" name="nascimento" value="' . $cliente['data_nascimento'] . '">';
            $table .= '<small id="text-nascimento-alterar-' . $numeroRefModal . '"></small>';
            $table .= '</div>';

            $table .= '<div class="col-md-4 mb-3">';
            $table .= '<label for="telefone" class="form-label">Telefone</label>';
            $table .= '<input type="tel" class="form-control telefone-mask" id="telefone-alterar-' . $numeroRefModal . '" name="telefone" value="' . $telefone . '">';
            $table .= '<small id="text-telefone-alterar-' . $numeroRefModal . '"></small>';
            $table .= '</div>';

            $table .= '<div class="col-md-4 mb-3">';
            $table .= '<label for="email" class="form-label">Email</label>';
            $table .= '<input type="email" class="form-control" id="email-alterar-' . $numeroRefModal . '" name="email" value="' . $cliente['email'] . '">';
            $table .= '<small id="text-email-alterar-' . $numeroRefModal . '"></small>';
            $table .= '</div>';

            $table .= '<div class="col-md-6 mb-3">';
            $table .= '<label for="cep" class="form-label">CEP</label>';
            $table .= '<input type="text" class="form-control cep-mask" id="cep-alterar-' . $numeroRefModal . '" name="cep" value="' . $cep . '">';
            $table .= '<small id="text-cep-alterar-' . $numeroRefModal . '"></small>';
            $table .= '</div>';

            $table .= '<div class="col-md-6 mb-3">';
            $table .= '<label for="endereco" class="form-label">Endereço</label>';
            $table .= '<input type="text" class="form-control" id="endereco-alterar-' . $numeroRefModal . '" name="endereco" value="' . $cliente['endereco'] . '">';
            $table .= '<small id="text-endereco-alterar-' . $numeroRefModal . '"></small>';
            $table .= '</div>';

            $table .= '<div class="row">';
            $table .= '<div class="mb-3 d-flex justify-content-end">';
            $table .= '<button type="button" class="btn button-secondary-outline" name="cadastrar" onclick="alteracaoCliente(' . $numeroRefModal . ', formAlterarCliente' . $numeroRefModal . ', modalAlterarCliente' . $numeroRefModal . ',' . $pagina . ',' . $cliente['id'] . ')">';
            $table .= '<i class="far fa-plus-square"></i> Alterar';
            $table .= '</button>';

            $table .= '<button type="button" class="btn button-primary-outline mx-3" data-bs-dismiss="modal">';
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

            //Usuários nivel 3 não podem excluir clientes
            if ($_SESSION['nivel_acesso'] != 3) {
                $formExclusao = "'#form-excluir-cliente-{$numeroRefModal}'";

                $table .= '<form id="form-excluir-cliente-' . $numeroRefModal . '" action="/cliente/excluir" method="post">';
                $table .= '<input type="hidden" name="id" id="id" value="' . $cliente['id'] . '?>">';
                $table .= '<button type="button" name="excluir-cliente" id="excluir-cliente" value="excluir" class="btn button-primary-outline btn-sm mx-1" onclick="exclusaoCliente(' . $formExclusao . ',' . $pagina . ',' . $cliente['id'] . ')">';
                $table .= '<i class="far fa-trash-alt"></i>';
                $table .= '</button>';
                $table .= '</form>';
            }


            $table .= '</td>';
            $table .= '</tr>';

            $numeroRefModal++;
        }

        //Quantidade de pagina
        $quantidadeClientes = $Cliente->countClientes(trim($_POST['nome-pesquisa']));
        $quantidade_pg = ceil($quantidadeClientes['total'] / $qnt_result_pg);

        $paginacao = "";

        if ($quantidadeClientes['total'] > $qnt_result_pg) {
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
            'qtdResultados' => $quantidadeClientes['total'],
            'paginacao' =>  $paginacao,
            'ordemTipo' =>  $_POST['select-ordem-tipo'],
            'ordem' => $_POST['select-ordem'],
            'nome' => trim($_POST['nome-pesquisa'])
        ));
    }

    public function carregaClientes()
    {
        $cliente = Container::getModel('Cliente');
        $clientes = $cliente->selectAll();
        $totalClientes = $cliente->countClientes();


        $this->view->clientes = $clientes;
        $this->view->totalClientes = $totalClientes;

        return $clientes;
    }
}
