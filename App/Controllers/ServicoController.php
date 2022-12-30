<?php

namespace App\Controllers;

//Recursos do Mini Framework
use MF\Controller\Action;
use MF\Model\Container;
use App\Controllers\Util\Utilidades;
use App\Controllers\GerarPDFController;

class ServicoController extends Action
{
    //Representam as Action(Métodos)
    public function indexServico()
    {
        AuthController::validaAutenticacao();

        $this->carregaServicos();

        $this->render('index', 'layout2');
    }

    public function cadastroServicoNovo()
    {
        AuthController::validaAutenticacao();
        $servico = Container::getModel('Servico');
        $servico->__set('descricao', $_POST['descricao']);
        $servico->__set('valor', ($_POST['valor']));

        $servico->insert();

        echo json_encode(array(
            'success' => "SUCESSO: {$servico->__get('descricao')} cadastrado com sucesso!",
            'status' => 0
        ));
    }

    public function alteracaoServico()
    {
        AuthController::validaAutenticacao();

        $servico = Container::getModel('Servico');

        $servico->__set('id', $_POST['id']);
        $servico->__set('descricao', $_POST['descricao']);
        $servico->__set('valor', $_POST['valor']);

        $servico->update();

        echo json_encode(array(
            'success' => "SUCESSO: Servico alterado com sucesso!",
            'status' => 0
        ));
    }

    public function exclusaoServico()
    {
        AuthController::validaAutenticacao();

        $servico = Container::getModel('Servico');
        $servico->__set('id', $_POST['id']);

        $servico->delete();

        echo json_encode(array(
            'success' => "SUCESSO: Servico excluído com sucesso!",
            'status' => 0
        ));
    }

    public function gerarPdfServico()
    {
        $nomePesquisa = (isset($_GET['nome'])) ? $_GET['nome'] : "";
        $ordemTipo = (isset($_GET['ordemTipo'])) ? $_GET['ordemTipo'] : 1;
        $ordem = (isset($_GET['ordem'])) ? $_GET['ordem'] : 1;

        $servico = Container::getModel('Servico');
        $servicos = $servico->selectAll($nomePesquisa, $ordemTipo, $ordem);

        $table = '<table border=1 style="margin:auto; width:100%"';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<td align="center">Descrição</td>';
        $table .= '<td align="center">Valor</td>';
        $table .= '</tr>';
        $table .= '</thead>';

        foreach ($servicos as $servicos => $servico) {

            $table .= '<tbody>';

            $table .= '<tr><td>' . $servico['descricao'] . '</td>';
            $table .= '<td>' . $servico['valor'] . '</td><tr>';

            $table .= '</tbody>';
        }

        $table .= '</table>';

        GerarPDFController::gerarPdf($table, 'Serviço');
    }

    public function verificaDuplicacaoDescricao()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['descricao'])) {
            $servico = Container::getModel('Servico');
            $servico->__set('descricao', trim($_POST['descricao']));

            //0-False(Não existe) 1-True(Existe)
            $status = ($servico->duplicacaoDescricao()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificaDuplicacaoAlterarDescricao()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['descricao']) && isset($_POST['id-servico'])) {
            $servico = Container::getModel('Servico');
            $servico->__set('descricao', trim($_POST['descricao']));
            $servico->__set('id', $_POST['id-servico']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($servico->duplicacaoAlterarDescricao()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    //Gera a tabela de pesquisa de serviço no Agendamento
    public function buscarPesquisaAgendamentoServico()
    {

        AuthController::validaAutenticacao();

        if (isset($_POST['nome-pesquisa']) && $_POST['nome-pesquisa'] != '') {
            $Servico = Container::getModel('Servico');
            $servicos = $Servico->selectAll($_POST['nome-pesquisa']);

            $table = '<tr>';

            foreach ($servicos as $servicos => $servico) {
                $table .= '<td style="padding: 5px; width:100%;">' . $servico["descricao"] . '</td>';

                $table .= '<td class="d-flex justify-content-center" style="padding: 5px; width:100%;">';

                $atributos =  "'{$servico["descricao"]}', {$servico["id"]}, {$servico["valor"]}, '{$_POST["tipo-pesquisa"]}'";

                $table .= '<button type="button" class="btn button-secondary-outline btn-sm mx-1" onclick="selecionarServicoAgendamento('.$atributos.')">';
                $table .= '<i class="fa-solid fa-square-check"></i>';
                $table .= '</button>';

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

    public function atualizaTabelaServico()
    {
        AuthController::validaAutenticacao();

        $pagina = $_POST['pagina'];
        $qnt_result_pg = $_POST['qtdPagForm'];

        //calcular o inicio visualização
        $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

        $Servico = Container::getModel('Servico');
        $servicos = $Servico->selectOrderName(trim($_POST['nome-pesquisa']), $_POST['select-ordem'], $_POST['select-ordem-tipo'], $inicio, $qnt_result_pg);

        $table = '<tr>';
        $numeroRefModal = 1;

        foreach ($servicos as $servicos => $servico) {

            $table .= '<td>' . $servico["id"] . '</td>';
            $table .= '<td>' . $servico['descricao'] . '</td>';
            $table .= '<td>' . Utilidades::formataValor($servico['valor']) . '</td>';

            $table .= '<td class="d-flex justify-content-center">';

            $table .= '<button type="button" class="btn button-secondary-outline btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modalAlterarServico' . $numeroRefModal . '">';
            $table .= '<i class="far fa-edit"></i>';
            $table .= '</button>';

            $table .= '<div class="modal fade modal-lg" id="modalAlterarServico' . $numeroRefModal . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
            $table .= '<div class="modal-dialog">';
            $table .= '<div class="modal-content">';
            $table .= '<div class="modal-header">';
            $table .= '<h1 class="modal-title fs-5" id="exampleModalLabel">Alterar Servico</h1>';
            $table .= '<button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"></button>';
            $table .= '</div>';
            $table .= '<div class="modal-body">';
            $table .= '<form id="formAlterarServico' . $numeroRefModal . '">';
            $table .= '<div class="row input-wrapper">';
            $table .= '<input type="hidden" name="id" id="id" value="' . $servico['id'] . '">';

            $table .= '<div class="col-md-6 mb-3">';
            $table .= '<label for="descricao" class="form-label">Descrição</label>';
            $table .= '<input type="text" class="form-control" id="descricao-servico-alterar-' . $numeroRefModal . '" name="descricao" value="' . $servico['descricao'] . '">';
            $table .= '<small id="text-descricao-servico-alterar-' . $numeroRefModal . '"></small>';
            $table .= '</div>';

            $table .= '<div class="col-md-6 mb-3">';
            $table .= '<label for="valor" class="form-label">Valor</label>';
            $table .= '<input type="number" class="form-control" id="valor-alterar-' . $numeroRefModal . '" name="valor" value="' . $servico['valor'] . '">';
            $table .= '<small id="text-valor-alterar-' . $numeroRefModal . '"></small>';
            $table .= '</div>';

            $table .= '<div class="row">';
            $table .= '<div class="mb-3 d-flex justify-content-end">';
            $table .= '<button type="button" class="btn button-secondary-outline" name="cadastrar" onclick="alteracaoServico(' . $numeroRefModal . ', formAlterarServico' . $numeroRefModal . ', modalAlterarServico' . $numeroRefModal . ',' . $pagina . ',' . $servico['id'] . ')">';
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

            //Só quem pode excluir serviços são Usuarios do nivel 3 para cima
            if ($_SESSION['nivel_acesso'] != 3) {
                $formExclusao = "'#form-excluir-servico-{$numeroRefModal}'";

                $table .= '<form id="form-excluir-servico-' . $numeroRefModal . '" action="/servico/excluir" method="post">';
                $table .= '<input type="hidden" name="id" id="id" value="' . $servico['id'] . '?>">';
                $table .= '<button type="button" name="excluir-servico" id="excluir-servico" value="excluir" class="btn button-primary-outline btn-sm mx-1" onclick="exclusaoServico(' . $formExclusao . ',' . $pagina . ','. $servico['id'] .')">';
                $table .= '<i class="far fa-trash-alt"></i>';
                $table .= '</button>';
                $table .= '</form>';
            }

            $table .= '</td>';
            $table .= '</tr>';

            $numeroRefModal++;
        }

        //Quantidade de pagina
        $quantidadeServicos = $Servico->countServicos(trim($_POST['nome-pesquisa']));
        $quantidade_pg = ceil($quantidadeServicos['total'] / $qnt_result_pg);

        $paginacao = "";

        if ($quantidadeServicos['total'] > $qnt_result_pg) {
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
            'qtdResultados' => $quantidadeServicos['total'],
            'paginacao' =>  $paginacao,
            'ordemTipo' =>  $_POST['select-ordem-tipo'],
            'ordem' => $_POST['select-ordem'],
            'nome' => trim($_POST['nome-pesquisa'])
        ));
    }

    public function carregaServicos()
    {
        $servico = Container::getModel('Servico');
        $servicos = $servico->selectAll();
        $totalServicos = $servico->countServicos();


        $this->view->servicos = $servicos;
        $this->view->totalServicos = $totalServicos;

        return $servicos;
    }
}
