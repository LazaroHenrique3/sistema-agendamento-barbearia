<?php

namespace App\Controllers;

//Recursos do Mini Framework
use MF\Controller\Action;
use MF\Model\Container;
use App\Controllers\Util\Utilidades;
use App\Controllers\GerarPDFController;

class AgendamentoController extends Action
{
    //Representam as Action(Métodos)
    public function indexAgendamento()
    {
        AuthController::validaAutenticacao();

        $this->carregaAgendamentos();

        $this->render('index', 'layout2');
    }

    public function cadastrarAgendamento()
    {

        AuthController::validaAutenticacao();

        $agendamento = Container::getModel('Agendamento');

        $agendamento->__set('idCliente', $_POST['id-agendamento-cliente']);
        $agendamento->__set('idServico', $_POST['id-agendamento-servico']);
        $agendamento->__set('valor', $_POST['valor-agendamento']);
        $agendamento->__set('dataHoraInicio', $_POST['data-inicio']);
        $agendamento->__set('dataHoraFim', $_POST['data-fim']);
        $agendamento->__set('observacao', $_POST['observacao-agendamento']);

        $agendamento->insert();

        $dataHoraInicioFormatada = Utilidades::formataDataHora($_POST['data-inicio']);
        $dataHoraFimFormatada = Utilidades::formataDataHora($_POST['data-fim']);

        if (isset($_POST['check-enviar-email']) && $_POST['check-enviar-email'] == "on") {
            EmailController::enviarAvisoAgendamentoUsuario($dataHoraInicioFormatada, $dataHoraFimFormatada, $_POST['valor-agendamento'], $_POST['servico-agendamento'], $_POST['email-agendamento-cliente'], $_POST['cliente-agendamento']);
        }

        echo json_encode(array(
            'success' => "SUCESSO: Agendamento cadastrado com sucesso!",
            'status' => 0
        ));
    }

    public function alterarAgendamento()
    {
        AuthController::validaAutenticacao();

        $agendamento = Container::getModel('Agendamento');

        $agendamento->__set('id', $_POST['id-agendamento-alterar']);
        $agendamento->__set('idCliente', $_POST['id-agendamento-cliente']);
        $agendamento->__set('idServico', $_POST['id-agendamento-servico']);
        $agendamento->__set('valor', $_POST['valor-agendamento']);
        $agendamento->__set('dataHoraInicio', $_POST['data-inicio']);
        $agendamento->__set('dataHoraFim', $_POST['data-fim']);
        $agendamento->__set('observacao', $_POST['observacao-agendamento']);

        $agendamento->update();

        $dataHoraInicioFormatada = Utilidades::formataDataHora($_POST['data-inicio']);
        $dataHoraFimFormatada = Utilidades::formataDataHora($_POST['data-fim']);

        if (isset($_POST['check-enviar-email']) && $_POST['check-enviar-email'] == "on") {
            EmailController::enviarAvisoAlteracaoAgendamentoUsuario($dataHoraInicioFormatada, $dataHoraFimFormatada, $_POST['valor-agendamento'], $_POST['servico-agendamento'], $_POST['email-agendamento-cliente'], $_POST['cliente-agendamento']);
        }

        echo json_encode(array(
            'success' => "SUCESSO: Cliente alterado com sucesso!",
            'status' => 0
        ));
    }

    public function excluirAgendamento()
    {

        AuthController::validaAutenticacao();

        $agendamento = Container::getModel('Agendamento');
        $agendamento->__set('id', $_POST['idAgendamento']);

        $agendamento->delete();

        echo json_encode(array(
            'success' => "SUCESSO: Agendamento excluído com sucesso!",
            'status' => 0
        ));
    }

    public function exibirAgendamentos()
    {

        AuthController::validaAutenticacao();

        $agendamento = Container::getModel('Agendamento');

        //Pegando o timezone correto
        date_default_timezone_set('America/Sao_Paulo');
        
        $agendamento->__set('dataHoraInicio', date('Y-m-d') . " " . "00:00:00");
        $agendamento->__set('dataHoraFim', date('Y-m-d') . " " . "23:59:59");

        $agendamentosHoje = $agendamento->selectAgendamentosHoje();

        $retorno = '';
        if (count($agendamentosHoje) > 0) {

            $idCollapse = 1;
            $retorno .= '<h2 class="title-h2">Agendamentos para hoje</h2>';
            foreach ($agendamentosHoje as $key => $value) {

                $retorno .= '<div class="col-sm-6 col-md-4 mb-3">';
                $retorno .= '<div class="card">';
                $retorno .= '<div class="card-body">';

                $retorno .= '<div>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Cliente: </span> ' . $value['nomeCliente'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Serviço: </span> ' . $value['nomeServico'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Início: </span> ' . substr($value['start'], 10, -3) . ' </p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Fim: </span> ' . substr($value['end'], 10, -3) . ' </p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Valor: </span>R$ ' . str_replace(".", ",", $value['valor']) . '</p>';
                $retorno .= '</div>';

                $retorno .= '<p>';
                $retorno .= '<a class="btn button-primary-outline" data-bs-toggle="collapse" href="#infoClienteHoje' . $idCollapse . '" role="button" aria-expanded="false" aria-controls="collapseExample">';
                $retorno .= '<i class="fa-solid fa-circle-info"></i>';
                $retorno .= '</a>';
                $retorno .= '</p>';

                $retorno .= '<div class="collapse mb-3" id="infoClienteHoje' . $idCollapse . '">';
                $retorno .= '<div class="card card-body">';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Telefone: </span>' . Utilidades::formataTelefone($value['telefone']) . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Email: </span>' . $value['email'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Endereço: </span>' . $value['endereco'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Observações: </span>' . $value['observacao'] . '</p>';
                $retorno .= '</div>';
                $retorno .= '</div>';

                $idCollapse++;


                $retorno .= '<button style="margin-right: 3px;" id="concluir-agendamento" type="button" class="btn button-primary-outline" onclick="concluirAgendamento(' . $value['id'] . ')">';
                $retorno .= '<i class="fa-solid fa-check"></i> Concluir';
                $retorno .= '</button>';

                $retorno .= '<button id="cancelar-agendamento" type="button" class="btn button-secondary-outline" onclick="cancelarAgendamento(' . $value['id'] . ')">';
                $retorno .= '<i class="fa-solid fa-xmark"></i> Cancelar';
                $retorno .= '</button>';

                $retorno .= '</div>';
                $retorno .= '</div>';
                $retorno .= '</div>';
            }
        }

        echo json_encode(array(
            'agendamentos' => $retorno
        ));
    }

    public function exibirAgendamentosAtrasados()
    {

        AuthController::validaAutenticacao();

        $agendamento = Container::getModel('Agendamento');

        //Pegando o timezone correto
        date_default_timezone_set('America/Sao_Paulo');

        $agendamento->__set('dataHoraInicio', date('Y-m-d') . " " . "00:00:00");

        $agendamentosHojeAtrasados = $agendamento->selectAgendamentosAtrasados();

        $retorno = '';

        //Para diferenciar o collapse de cada um
        $idCollapse = 1;
        if (count($agendamentosHojeAtrasados) > 0) {

            $retorno .= '<h2 class="title-h2">Agendamentos pendentes (Atrasados)</h2>';

            foreach ($agendamentosHojeAtrasados as $key => $value) {

                $retorno .= '<div class="col-sm-6 col-md-4 mb-3">';
                $retorno .= '<div class="card">';
                $retorno .= '<div class="card-body">';

                $retorno .= '<div>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Cliente: </span> ' . $value['nomeCliente'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Serviço: </span> ' . $value['nomeServico'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Início: </span> ' . Utilidades::formataData(substr($value['start'], 0, -8)) . " - " . substr($value['start'], 10, -3) . ' </p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Fim: </span> ' . Utilidades::formataData(substr($value['end'], 0, -8)) . " - " . substr($value['end'], 10, -3) . ' </p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Valor: </span>R$ ' . str_replace(".", ",", $value['valor']) . '</p>';
                $retorno .= '</div>';

                $retorno .= '<p>';
                $retorno .= '<a class="btn button-primary-outline" data-bs-toggle="collapse" href="#infoCliente' . $idCollapse . '" role="button" aria-expanded="false" aria-controls="collapseExample">';
                $retorno .= '<i class="fa-solid fa-circle-info"></i>';
                $retorno .= '</a>';
                $retorno .= '</p>';

                $retorno .= '<div class="collapse mb-3" id="infoCliente' . $idCollapse . '">';
                $retorno .= '<div class="card card-body">';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Telefone: </span>' . Utilidades::formataTelefone($value['telefone']) . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Email: </span>' . $value['email'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Endereço: </span>' . $value['endereco'] . '</p>';
                $retorno .= '<p class="mb-1 card-info-cliente"><span class="card-text-destaque">Observações: </span>' . $value['observacao'] . '</p>';
                $retorno .= '</div>';
                $retorno .= '</div>';

                $idCollapse++;

                $retorno .= '<button style="margin-right: 3px;" id="concluir-agendamento" type="button" class="btn button-primary-outline" onclick="concluirAgendamento(' . $value['id'] . ')">';
                $retorno .= '<i class="fa-solid fa-check"></i> Concluir';
                $retorno .= '</button>';

                $retorno .= '<button id="cancelar-agendamento" type="button" class="btn button-secondary-outline" onclick="cancelarAgendamento(' . $value['id'] . ')">';
                $retorno .= '<i class="fa-solid fa-xmark"></i> Cancelar';
                $retorno .= '</button>';

                $retorno .= '</div>';
                $retorno .= '</div>';
                $retorno .= '</div>';
            }
        }

        echo json_encode(array(
            'agendamentos' => $retorno
        ));
    }

    public function concluirAgendamento()
    {

        AuthController::validaAutenticacao();

        $agendamento = Container::getModel('Agendamento');
        $agendamento->__set('id', $_POST['id-agendamento']);

        $agendamento->concluirAgendamento();
    }

    public function cancelarAgendamento()
    {
        AuthController::validaAutenticacao();

        $agendamento = Container::getModel('Agendamento');
        $agendamento->__set('id', $_POST['id-agendamento']);

        $agendamento->cancelarAgendamento();
    }

    public function verificarExclusaoCliente()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['idCliente'])) {
            $agendamento = Container::getModel('Agendamento');
            $agendamento->__set('idCliente', $_POST['idCliente']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($agendamento->existeAgendamentoCliente()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificarExclusaoServico()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['idServico'])) {
            $agendamento = Container::getModel('Agendamento');
            $agendamento->__set('idServico', $_POST['idServico']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($agendamento->existeAgendamentoServico()) ? 1 : 0;

            echo json_encode(array(
                'status' => $status
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

        $Agendamento = Container::getModel('Agendamento');
        $agendamentos = $Agendamento->selectOrderName(trim($_POST['nome-pesquisa']), $_POST['select-ordem'], $_POST['select-ordem-tipo'], $inicio, $qnt_result_pg);

        $table = '<tr>';

        $numeroRefModal = 1;
        foreach ($agendamentos as $agendamentos => $agendamento) {

            $table .= '<td>' . Utilidades::formataStatusAgendamento($agendamento["status"]) . '</td>';

            $telefone = Utilidades::formataTelefone($agendamento['telefone']);
            $infoCliente = "Telefone: {$telefone} <br> Email: {$agendamento["email"]} <br> Endereço: {$agendamento["endereco"]}";
            $table .= '<td>' . $agendamento['nomeCliente'] . ' <a tabindex="0" role="button" data-bs-html="true" data-bs-sanitize="false" data-bs-toggle="popover" data-bs-trigger="focus" title="Informações do Cliente" data-bs-content="' . $infoCliente . '"><i style="color: #00ff88;" class="fa-solid fa-circle-info"></i></a> </td>';

            $table .= '<td>' . $agendamento['nomeServico'] . '</td>';
            $table .= '<td>' . Utilidades::formataValor($agendamento['valor']) . '</td>';
            $table .= '<td>' . Utilidades::formataData(substr($agendamento['start'], 0, -8)) . " - " . substr($agendamento['start'], 10, -3) . '</td>';
            $table .= '<td>' . Utilidades::formataData(substr($agendamento['end'], 0, -8)) . " - " . substr($agendamento['end'], 10, -3) . '</td>';

            $table .= '<td class="d-flex">';

            //Usuários nivel 3 não podem excluir Agendamentos
            if ($_SESSION['nivel_acesso'] != 3) {
                $formExclusao = "'#form-excluir-cliente-{$numeroRefModal}'";

                $table .= '<form id="form-excluir-cliente-' . $numeroRefModal . '" action="/cliente/excluir" method="post">';
                $table .= '<button type="button" name="excluir-cliente" id="excluir-cliente" value="excluir" class="btn button-primary-outline btn-sm mx-1" onclick="excluirAgendamento(' . $agendamento['idAgendamento'] . ', ' . $agendamento['status'] . ')">';
                $table .= '<i class="far fa-trash-alt"></i>';
                $table .= '</button>';
                $table .= '</form>';
            }

            $table .= '</td>';
            $table .= '</tr>';

            $numeroRefModal++;
        }

        //Quantidade de pagina
        $quantidadeAgendamento = $Agendamento->countAgendamentosTabela(trim($_POST['nome-pesquisa']));
        $quantidade_pg = ceil($quantidadeAgendamento['total'] / $qnt_result_pg);

        $paginacao = "";

        if ($quantidadeAgendamento['total'] > $qnt_result_pg) {
            $anterior = ($pagina > 1) ? $pagina - 1 : $pagina;
            $proxima = ($pagina < $quantidade_pg) ? $pagina + 1 : $pagina;

            $paginacao .= '<li class="page-item">
                <a class="page-link bg-page-link" aria-label="Next" onclick="atualizaTabelaAgendamento(' . $anterior . ')">
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
                    $paginacao .= '<li class="page-item"><a class="page-link bg-page-link ' . $active . '" onclick="atualizaTabelaAgendamento(' . $pag . ')">' . $pag . '</a></li>';
                }

                $paginacao .= '<li class="page-item disabled"><a class="page-link bg-page-link ">...</a></li>';

                //Listando as 5 ultimas paginas
                for ($pag = $quantidade_pg - 4; $pag <= $quantidade_pg; $pag++) {
                    $active = ($pag == $pagina) ? 'bg-page-link-active' : '';
                    $paginacao .= '<li class="page-item"><a class="page-link bg-page-link ' . $active . '" onclick="atualizaTabelaAgendamento(' . $pag . ')">' . $pag . '</a></li>';
                }
            } else {

                for ($pag = 1; $pag <= $quantidade_pg; $pag++) {
                    $active = ($pag == $pagina) ? 'bg-page-link-active' : '';
                    $paginacao .= '<li class="page-item"><a class="page-link bg-page-link ' . $active . '" onclick="atualizaTabelaAgendamento(' . $pag . ')">' . $pag . '</a></li>';
                }
            }

            $paginacao .= '<li class="page-item">
                <a class="page-link bg-page-link" aria-label="Next" onclick="atualizaTabelaAgendamento(' . $proxima . ')">
                    <span aria-hidden="true">&raquo</span>
                </a>
            </li>';
        }

        echo json_encode(array(
            'table' => $table,
            'qtdResultados' => $quantidadeAgendamento['total'],
            'paginacao' =>  $paginacao,
            'ordemTipo' =>  $_POST['select-ordem-tipo'],
            'ordem' => $_POST['select-ordem'],
            'nome' => trim($_POST['nome-pesquisa'])
        ));
    }

    public function listarAgendamento()
    {
        /*
        AuthController::validaAutenticacao();
        $agendamento = Container::getModel('Agendamento');

        $agendamentos = $agendamento->selectAll();

        $eventos = [];

        while ($row_events = $agendamentos) {
            $id = $row_events['id'];
            $title = $row_events['nome'] . " - " . $row_events['descricao'];
            $color = '#0071c5';
            $start = $row_events['start'];
            $end = $row_events['end'];

            $eventos[] = [
                'id' => $id,
                'title' => $title,
                'color' => $color,
                'start' => $start,
                'end' => $end
            ];
        }

        echo json_encode($eventos);
        */

        define('HOST', 'localhost');
        define('USER', 'root');
        define('PASS', '');
        define('DBNAME', 'barbearia_ze');

        $conn = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME . ';', USER, PASS);

        $query_events = "SELECT 
                    a.id, 
                    a.id_cliente AS idCliente, 
                    cli.nome, 
                    cli.email AS email,
                    a.id_servico AS idServico, 
                    serv.descricao, 
                    a.valor, 
                    start, 
                    end, 
                    observacao 
                 FROM agendamento AS a
                 JOIN cliente AS cli
                 ON cli.id = a.id_cliente
                 JOIN  servico AS serv
                 ON serv.id = a.id_servico
                 WHERE a.status_agendamento = 1";

        $resultado_events = $conn->prepare($query_events);
        $resultado_events->execute();

        $eventos = [];

        while ($row_events = $resultado_events->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row_events['id'];
            $title = $row_events['nome'] . " - " . $row_events['descricao'];
            $color = '#0071c5';
            $start = $row_events['start'];
            $end = $row_events['end'];

            $eventos[] = [
                'id' => $id,
                'title' => $title,
                'observacao' => $row_events['observacao'],
                'cliente' => $row_events['nome'],
                'clienteId' => $row_events['idCliente'],
                'servico' => $row_events['descricao'],
                'servicoId' => $row_events['idServico'],
                'valor' => $row_events['valor'],
                'color' => $color,
                'start' => $start,
                'end' => $end,
                'email' => $row_events['email']
            ];
        }

        echo json_encode($eventos);
    }


    public function carregaAgendamentos()
    {
        $agendamento = Container::getModel('Agendamento');
        $agendamentos = $agendamento->selectAll();
        $totalAgendamentos = $agendamento->countAgendamentos();


        $this->view->agendamentos = $agendamentos;
        $this->view->totalAgendamentos = $totalAgendamentos;

        return $agendamentos;
    }

    public function verificaDuplicacaoData()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['start']) && isset($_POST['end'])) {

            $agendamento = Container::getModel('Agendamento');
            $agendamento->__set('dataHoraInicio', $_POST['start']);
            $agendamento->__set('dataHoraFim', $_POST['end']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($agendamento->duplicacaoData() == 'ocupado') ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function verificaDuplicacaoAlterarData()
    {
        AuthController::validaAutenticacao();

        if (isset($_POST['start']) && isset($_POST['end'])) {

            $agendamento = Container::getModel('Agendamento');
            $agendamento->__set('dataHoraInicio', $_POST['start']);
            $agendamento->__set('dataHoraFim', $_POST['end']);
            $agendamento->__set('id', $_POST['id-agendamento']);

            //0-False(Não existe) 1-True(Existe)
            $status = ($agendamento->duplicacaoAlterarData() == 'ocupado') ? 1 : 0;

            echo json_encode(array(
                'status' => $status
            ));
        }
    }

    public function gerarPdfAgendamento()
    {
        $nomePesquisa = (isset($_GET['nome'])) ? $_GET['nome'] : "";
        $ordemTipo = (isset($_GET['ordemTipo'])) ? $_GET['ordemTipo'] : 1;
        $ordem = (isset($_GET['ordem'])) ? $_GET['ordem'] : 1;

        $Agendamento = Container::getModel('Agendamento');
        $agendamentos = $Agendamento->selectAllPdf($nomePesquisa, $ordemTipo, $ordem);

        $table = '<table border=1 style="margin:auto;"';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<td align="center">Status</td>';
        $table .= '<td align="center">Cliente</td>';
        $table .= '<td align="center">Serviço</td>';
        $table .= '<td align="center">Valor</td>';
        $table .= '<td align="center">Início</td>';
        $table .= '<td align="center">Término</td>';
        $table .= '</tr>';
        $table .= '</thead>';

        foreach ($agendamentos as $agendamentos => $agendamento) {
            $table .= '<tbody>';

            $table .= '<tr><td>' . Utilidades::formataStatusAgendamento($agendamento["status"])  . '</td>';
            $table .= '<td>' . $agendamento['nomeCliente'] . '</td>';
            $table .= '<td>' . $agendamento['nomeServico'] . '</td>';
            $table .= '<td>' . Utilidades::formataValor($agendamento['valor']) . '</td>';
            $table .= '<td>' . Utilidades::formataData(substr($agendamento['start'], 0, -8)) . " - " . substr($agendamento['start'], 10, -3) . '</td>';
            $table .= '<td>' . Utilidades::formataData(substr($agendamento['start'], 0, -8)) . " - " . substr($agendamento['end'], 10, -3) . '</td>';

            $table .= '</tbody>';
        }

        $table .= '</table>';

        GerarPDFController::gerarPdf($table, 'Agendamento');
    }
}
