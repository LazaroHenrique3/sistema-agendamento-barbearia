<?php

//NameSpace deve ser compátivel com o diretório
namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

    //Rotas existentes, e define qual controlador será executado
    protected function initRoutes()
    {

        //Configurando as rotas
        $routes['home'] = array(
            'route' => '/',
            'controller' => 'IndexController',
            'action' => 'index'
        );

        $routes['dashboard'] = array(
            'route' => '/dashboard',
            'controller' => 'AppController',
            'action' => 'dashboard'
        );

        //AuthController
        $routes['autenticar'] = array(
            'route' => '/autenticar',
            'controller' => 'AuthController',
            'action' => 'autenticar'
        );

        $routes['sair'] = array(
            'route' => '/sair',
            'controller' => 'AuthController',
            'action' => 'sair'
        );
         
        //ClienteController
        $routes['clienteIndex'] = array(
            'route' => '/cliente/index',
            'controller' => 'ClienteController',
            'action' => 'indexCliente'
        );

        $routes['clienteBuscar'] = array(
            'route' => '/cliente/buscar',
            'controller' => 'ClienteController',
            'action' => 'buscarPesquisaAgendamentoCliente'
        );

        $routes['clienteCadastrar'] = array(
            'route' => '/cliente/cadastrar',
            'controller' => 'ClienteController',
            'action' => 'cadastroClienteNovo'
        );

        $routes['clienteVerificarEmail'] = array(
            'route' => '/cliente/verificar/email',
            'controller' => 'ClienteController',
            'action' => 'verificaDuplicacaoEmail'
        );

        $routes['clienteVerificarAlterarEmail'] = array(
            'route' => '/cliente/verificar/alterar/email',
            'controller' => 'ClienteController',
            'action' => 'verificaDuplicacaoAlterarEmail'
        );

        $routes['clienteVerificarCpf'] = array(
            'route' => '/cliente/verificar/cpf',
            'controller' => 'ClienteController',
            'action' => 'verificaDuplicacaoCpf'
        );

        $routes['clienteVerificarAlterarCpf'] = array(
            'route' => '/cliente/verificar/alterar/cpf',
            'controller' => 'ClienteController',
            'action' => 'verificaDuplicacaoAlterarCpf'
        );
        
        $routes['clienteAlterar'] = array(
            'route' => '/cliente/alterar',
            'controller' => 'ClienteController',
            'action' => 'alteracaoCliente'
        );

        $routes['clienteExcluir'] = array(
            'route' => '/cliente/excluir',
            'controller' => 'ClienteController',
            'action' => 'exclusaoCliente'
        );

        $routes['clienteAtualizar'] = array(
            'route' => '/cliente/atualizar',
            'controller' => 'ClienteController',
            'action' => 'atualizaTabelaCliente'
        );

        $routes['clienteGerarPDF'] = array(
            'route' => '/cliente/PDF',
            'controller' => 'ClienteController',
            'action' => 'gerarPdfCliente'
        );

        //UsuarioController
        $routes['usuarioIndex'] = array(
            'route' => '/usuario/index',
            'controller' => 'UsuarioController',
            'action' => 'indexUsuario'
        );

        $routes['usuarioCadastrar'] = array(
            'route' => '/usuario/cadastrar',
            'controller' => 'UsuarioController',
            'action' => 'cadastroUsuarioNovo'
        );

        $routes['usuarioVerificarEmail'] = array(
            'route' => '/usuario/verificar/email',
            'controller' => 'UsuarioController',
            'action' => 'verificaDuplicacaoEmail'
        );

        $routes['usuarioVerificarAlterarEmail'] = array(
            'route' => '/usuario/verificar/alterar/email',
            'controller' => 'UsuarioController',
            'action' => 'verificaDuplicacaoAlterarEmail'
        );

        $routes['usuarioVerificarCpf'] = array(
            'route' => '/usuario/verificar/cpf',
            'controller' => 'UsuarioController',
            'action' => 'verificaDuplicacaoCpf'
        );

        $routes['usuarioVerificarAlterarCpf'] = array(
            'route' => '/usuario/verificar/alterar/cpf',
            'controller' => 'UsuarioController',
            'action' => 'verificaDuplicacaoAlterarCpf'
        );
        
        $routes['usuarioAlterar'] = array(
            'route' => '/usuario/alterar',
            'controller' => 'UsuarioController',
            'action' => 'alteracaoUsuario'
        );

        $routes['usuarioLogadoAlterar'] = array(
            'route' => '/usuario/logado/alterar',
            'controller' => 'UsuarioController',
            'action' => 'alteracaoUsuarioLogado'
        );

        $routes['usuarioExcluir'] = array(
            'route' => '/usuario/excluir',
            'controller' => 'UsuarioController',
            'action' => 'exclusaoUsuario'
        );

        $routes['usuarioAtualizar'] = array(
            'route' => '/usuario/atualizar',
            'controller' => 'UsuarioController',
            'action' => 'atualizaTabelaUsuario'
        );

        $routes['usuarioGerarPDF'] = array(
            'route' => '/usuario/PDF',
            'controller' => 'UsuarioController',
            'action' => 'gerarPdfUsuario'
        );

        $routes['recuperarSenha'] = array(
            'route' => '/usuario/recuperar/senha',
            'controller' => 'UsuarioController',
            'action' => 'recuperarSenha'
        );

        //ServicoController
        $routes['servicoIndex'] = array(
            'route' => '/servico/index',
            'controller' => 'ServicoController',
            'action' => 'indexServico'
        );

        $routes['servicoBuscar'] = array(
            'route' => '/servico/buscar',
            'controller' => 'ServicoController',
            'action' => 'buscarPesquisaAgendamentoServico'
        );

        $routes['servicoCadastrar'] = array(
            'route' => '/servico/cadastrar',
            'controller' => 'ServicoController',
            'action' => 'cadastroServicoNovo'
        );

        $routes['servicoVerificarDescricao'] = array(
            'route' => '/servico/verificar/descricao',
            'controller' => 'ServicoController',
            'action' => 'verificaDuplicacaoDescricao'
        );

        $routes['servicoVerificarAlterarDescricao'] = array(
            'route' => '/servico/verificar/alterar/descricao',
            'controller' => 'ServicoController',
            'action' => 'verificaDuplicacaoAlterarDescricao'
        );

        $routes['servicoAlterar'] = array(
            'route' => '/servico/alterar',
            'controller' => 'ServicoController',
            'action' => 'alteracaoServico'
        );

        $routes['servicoExcluir'] = array(
            'route' => '/servico/excluir',
            'controller' => 'ServicoController',
            'action' => 'exclusaoServico'
        );

        $routes['servicoAtualizar'] = array(
            'route' => '/servico/atualizar',
            'controller' => 'ServicoController',
            'action' => 'atualizaTabelaServico'
        );

        $routes['servicoGerarPDF'] = array(
            'route' => '/servico/PDF',
            'controller' => 'ServicoController',
            'action' => 'gerarPdfServico'
        );

        //AgendamentoController
        $routes['agendamentoIndex'] = array(
            'route' => '/agendamento/index',
            'controller' => 'AgendamentoController',
            'action' => 'indexAgendamento'
        );

        $routes['agendamentoListar'] = array(
            'route' => '/agendamento/listar',
            'controller' => 'AgendamentoController',
            'action' => 'listarAgendamento'
        );

        $routes['agendamentoCadastrar'] = array(
            'route' => '/agendamento/cadastrar',
            'controller' => 'AgendamentoController',
            'action' => 'cadastrarAgendamento'
        );

        $routes['agendamentoAtualizar'] = array(
            'route' => '/agendamento/atualizar',
            'controller' => 'AgendamentoController',
            'action' => 'atualizaTabelaCliente'
        );

        $routes['agendamentoAlterar'] = array(
            'route' => '/agendamento/alterar',
            'controller' => 'AgendamentoController',
            'action' => 'alterarAgendamento'
        );

        $routes['agendamentoExcluir'] = array(
            'route' => '/agendamento/excluir',
            'controller' => 'AgendamentoController',
            'action' => 'excluirAgendamento'
        );

        $routes['agendamentoConcluir'] = array(
            'route' => '/agendamento/concluir',
            'controller' => 'AgendamentoController',
            'action' => 'concluirAgendamento'
        );

        $routes['agendamentoCancelar'] = array(
            'route' => '/agendamento/cancelar',
            'controller' => 'AgendamentoController',
            'action' => 'cancelarAgendamento'
        );

        $routes['agendamentoExibir'] = array(
            'route' => '/agendamento/exibir',
            'controller' => 'AgendamentoController',
            'action' => 'exibirAgendamentos'
        );

        $routes['agendamentoAtrasados'] = array(
            'route' => '/agendamento/atrasados',
            'controller' => 'AgendamentoController',
            'action' => 'exibirAgendamentosAtrasados'
        );

        $routes['agendamentoVerificarData'] = array(
            'route' => '/agendamento/verificar/data',
            'controller' => 'AgendamentoController',
            'action' => 'verificaDuplicacaoData'
        );

        $routes['agendamentoVerificarAlterarData'] = array(
            'route' => '/agendamento/verificar/alterar/data',
            'controller' => 'AgendamentoController',
            'action' => 'verificaDuplicacaoAlterarData'
        );

        $routes['clienteVerificarExclusao'] = array(
            'route' => '/cliente/verificar/exclusao',
            'controller' => 'AgendamentoController',
            'action' => 'verificarExclusaoCliente'
        );

        $routes['servicoVerificarExclusao'] = array(
            'route' => '/servico/verificar/exclusao',
            'controller' => 'AgendamentoController',
            'action' => 'verificarExclusaoServico'
        );

        $routes['agendamentoGerarPDF'] = array(
            'route' => '/agendamento/PDF',
            'controller' => 'AgendamentoController',
            'action' => 'gerarPdfAgendamento'
        );

        $this->setRoutes($routes);
    }
}
