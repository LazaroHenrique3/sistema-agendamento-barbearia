let idAgendamento = null;

//Arrumando o erro do Collapse não funcionar por interferencia do full calendar
$("#cadastro").click(function(){
    $('#ul-cadastro').toggleClass('show');
    $('#ul-movimentacoes').removeClass('show');
    $('#ul-usuario').removeClass('show');
});

$("#movimentacoes").click(function(){
    $('#ul-movimentacoes').toggleClass('show');
    $('#ul-cadastro').removeClass('show');
    $('#ul-usuario').removeClass('show');
});

$("#usuario").click(function(){
    $('#ul-usuario').toggleClass('show');
    $('#ul-cadastro').removeClass('show');
    $('#ul-movimentacoes').removeClass('show');
});

document.addEventListener('DOMContentLoaded', function () {
    atualizaFullCalendar();

    //Relacionado ao form de edição do Cliente
    $('.btn-canc-vis').on("click", function () {
        $('.visEvent').slideToggle();
        $('.formEditar').slideToggle();
        $('#btn-close-detalhes-agendamento').addClass('d-none');
    });

    $('.btn-canc-edit').on("click", function () {
        $('.formEditar').slideToggle();
        $('.visEvent').slideToggle();
        $('#btn-close-detalhes-agendamento').removeClass('d-none')
    });

});

//Teste de data
function getDateNow(data, tipoSelecao = '') {
    let today = new Date();
    let time = ''

    if (tipoSelecao == 'alterar') {
        time = data.slice(data.indexOf(' '), -3);
    } else {
        time = today.getHours().toString().padStart(2, '0') + ':' + today.getMinutes().toString().padStart(2, '0');
    }

    //Cortando somente a parte da data
    let dataFormatada = data.slice(0, data.indexOf(' '));
    dataFormatada = dataFormatada.split('/').reverse().join('-');

    return dataFormatada + 'T' + time.trim();
}

function atualizaFullCalendar() {

    $('#calendar').html("");
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        plugins: ['interaction', 'dayGrid'],
        //defaultDate: '2019-04-12',
        editable: true,
        eventLimit: true,
        events: '/agendamento/listar',
        extraParams: function () {
            return {
                cachebuster: new Date().valueOf()
            };
        },

        eventClick: function (info) {

            info.jsEvent.preventDefault();

            //Preenchendo as informações no modal de detalhes
            $('#visualizar #id').text(info.event.id)
            $('#visualizar #cliente').text(info.event.extendedProps.cliente)
            $('#visualizar #servico').text(info.event.extendedProps.servico)
            $('#visualizar #valor').text(info.event.extendedProps.valor)
            $('#visualizar #observacao').text(info.event.extendedProps.observacao)
            $('#visualizar #start').text(info.event.start.toLocaleString())
            $('#visualizar #end').text(info.event.end.toLocaleString())

            idAgendamento = info.event.id;

            $('#visualizar').modal('show')

            //Preenchendo as informações no modal de alteração
            $('#id-agendamento-alterar').val(info.event.id)

            $('#cliente-agendamento-alterar').val(info.event.extendedProps.cliente);
            $('#id-agendamento-cliente-alterar').val(info.event.extendedProps.clienteId);
            $('#servico-agendamento-alterar').val(info.event.extendedProps.servico);
            $('#id-agendamento-servico-alterar').val(info.event.extendedProps.servicoId);
            $('#valor-agendamento-alterar').val(info.event.extendedProps.valor);
            $('#div-editar #start-alterar').val(getDateNow(info.event.start.toLocaleString(), 'alterar'));
            $('#div-editar #end-alterar').val(getDateNow(info.event.end.toLocaleString(), 'alterar'));
            $('#observacao-agendamento-alterar').val(info.event.extendedProps.observacao);
            $("#email-agendamento-cliente-alterar").val(info.event.extendedProps.email);


        },

        selectable: true,

        select: function (info) {
            $('#modal-cadastrar').modal('show');
            $('#modal-cadastrar #start').val(getDateNow(info.start.toLocaleString()));
            $('#modal-cadastrar #end').val(getDateNow(info.start.toLocaleString()));
        }
    });

    calendar.render();

}

//Pesquisa do cliente (Ver Todos)
function buscar() {
    atualizaTabelaAgendamento();
}

//Option define qual tipo de Select será executado /Valores default
function atualizaTabelaAgendamento(pagina = 1) {

    //A atualização é trazida com base no que esta no campo de pesquisa
    var form = new FormData($("#form-pesquisar-agendamentos")[0]);
    form.append('pagina', pagina);

    jQuery.ajax({
        url: '/agendamento/atualizar',
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: form,
        timeout: 8000,
        success: function (result) {
            data = JSON.parse(result)

            $('#table-body').html(data.table);
            $('#page').html(data.paginacao);
            $('#pdf-agendamento').attr("href", `/agendamento/PDF?ordemTipo=${data.ordemTipo}&ordem=${data.ordem}&nome=${data.nome}`);//Vai adicionando gets que vão ser usados na hora de gerar o relatório

            //Para acionar o Popover do Cliente
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })
        },
        error: function (result) {
            $('#table-body').html('Houve um erro ao processar as informações.');
        }
    });

}

$('#btn-close-pesquisar-agendamento').click(function () {
    $('#nome-pesquisa').val('');
    $('#qtdPagForm').val(10);
});

////////////////Relacionado ao cadastro////////////////
//Mostra o modal de escolha de Serviços
$("#servico-agendamento").click(function () {
    $('#pesquisar-servico').modal('show');
});

//Mostra o modal de escolha de Clientes
$("#cliente-agendamento").click(function () {
    $('#pesquisar-cliente').modal('show');
});

//Resetar o modal ao clicar em fechar
$("#btn-close-pesquisar-servico").click(function () {
    limparModalServicoAgendamento();
});

//Resetar o modal ao clicar em fechar
$("#btn-close-pesquisar-cliente").click(function () {
    limparModalServicoAgendamento();
});

//Resetar o modal ao clicar em fechar
$("#btn-close-cadastro-agendamento").click(function () {
    limparModalCadastroAgendamento();
});
////////////////Relacionado ao cadastro////////////////


////////////////Relacionado a alteração////////////////
//Mostra o modal de escolha de Serviços
$("#servico-agendamento-alterar").click(function () {
    $('#pesquisar-servico-alterar').modal('show');
});

//Mostra o modal de escolha de Clientes
$("#cliente-agendamento-alterar").click(function () {
    $('#pesquisar-cliente-alterar').modal('show');
});

//Resetar o modal ao clicar em fechar
$("#btn-close-pesquisar-servico-alterar").click(function () {
    limparModalServicoAgendamento();
});

//Resetar o modal ao clicar em fechar
$("#btn-close-pesquisar-cliente-alterar").click(function () {
    limparModalServicoAgendamento();
});

//Resetar o modal ao clicar em fechar
$("#btn-close-cadastro-agendamento-alterar").click(function () {
    limparModalAlterarAgendamento();
});
////////////////Relacionado a alteração////////////////


//Essa função é ativada quando o Usuario seleciona algum Serviço do modal
function selecionarServicoAgendamento(descricaoServico, idServico, valorServico, tipoSelecao) {

    if (tipoSelecao == 'cadastro') {

        //Joga as informações para o outro modal
        $("#servico-agendamento").val(descricaoServico);
        $("#id-agendamento-servico").val(idServico);
        $("#valor-agendamento").val(valorServico);

        //Limpar tudo e fecha
        limparModalServicoAgendamento();
        $('#pesquisar-servico').modal('hide');

    } else if (tipoSelecao == 'alterar') {

        //Joga as informações para o outro modal
        $("#servico-agendamento-alterar").val(descricaoServico);
        $("#id-agendamento-servico-alterar").val(idServico);
        $("#valor-agendamento-alterar").val(valorServico);

        //Limpar tudo e fecha
        limparModalServicoAgendamento();
        $('#pesquisar-servico-alterar').modal('hide');

    }

}

//Essa função é ativada quando o Usuario seleciona algum cliente do modal
function selecionarClienteAgendamento(nomeCliente, idCliente, tipoSelecao, emailCliente) {

    if (tipoSelecao == 'cadastro') {

        //Joga as informações para o outro modal
        $("#cliente-agendamento").val(nomeCliente);
        $("#id-agendamento-cliente").val(idCliente);
        $("#email-agendamento-cliente").val(emailCliente);

        //Limpar tudo e fecha
        limparModalServicoAgendamento();
        $('#pesquisar-cliente').modal('hide');

    } else if (tipoSelecao == 'alterar') {

        //Joga as informações para o outro modal
        $("#cliente-agendamento-alterar").val(nomeCliente);
        $("#id-agendamento-cliente-alterar").val(idCliente);
        $("#email-agendamento-cliente-alterar").val(emailCliente);

        //Limpar tudo e fecha
        limparModalServicoAgendamento();
        $('#pesquisar-cliente-alterar').modal('hide');

    }

}

////////////////////Realacionado ao cadastro////////////////////
//Faz a pesquisa dinâmica dos Serviços
$("#servico-agendamento-pesquisar").keyup(function () {
    let nomePesquisa = $("#servico-agendamento-pesquisar").val();

    //Começa pesquisar a partir do terceiro caractere
    if (nomePesquisa.length >= 3) {
        var form = new FormData();
        form.append('nome-pesquisa', nomePesquisa);
        //Diz se é com a intenção de alterar ou cadastrar
        form.append('tipo-pesquisa', 'cadastro');

        jQuery.ajax({
            url: '/servico/buscar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {
                data = JSON.parse(result)

                $('#table-servico-pesquisar-body').html(data.table);
            },
            error: function (result) {
                $('#table-servico-pesquisar-body').html('Houve um erro ao processar as informações.');
            }
        });
    }
});

//Faz a pesquisa dinâmica dos Clientes
$("#cliente-agendamento-pesquisar").keyup(function () {
    let nomePesquisa = $("#cliente-agendamento-pesquisar").val();

    //Começa pesquisar a partir do 2 Caractere
    if (nomePesquisa.length > 2) {
        var form = new FormData();
        form.append('nome-pesquisa', nomePesquisa);
        //Diz se é com a intenção de alterar ou cadastrar
        form.append('tipo-pesquisa', 'cadastro');

        jQuery.ajax({
            url: '/cliente/buscar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {

                data = JSON.parse(result)

                $('#table-cliente-pesquisar-body').html(data.table);

                //Para acionar o Popover do Cliente
                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl)
                })

            },
            error: function (result) {
                $('#table-cliente-pesquisar-body').html('Houve um erro ao processar as informações.');
            }
        });
    }
});
////////////////////Realacionado ao cadastro////////////////////

////////////////////Realacionado a alteração////////////////////
//Faz a pesquisa dinâmica dos Serviços
$("#servico-agendamento-pesquisar-alterar").keyup(function () {
    let nomePesquisa = $("#servico-agendamento-pesquisar-alterar").val();

    //Começa pesquisar a partir do terceiro caractere
    if (nomePesquisa.length >= 3) {
        var form = new FormData();
        form.append('nome-pesquisa', nomePesquisa);
        //Diz se é com a intenção de alterar ou cadastrar
        form.append('tipo-pesquisa', 'alterar');

        jQuery.ajax({
            url: '/servico/buscar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {
                data = JSON.parse(result)

                $('#table-servico-pesquisar-body-alterar').html(data.table);
            },
            error: function (result) {
                $('#table-servico-pesquisar-body-alterar').html('Houve um erro ao processar as informações.');
            }
        });
    }
});

//Faz a pesquisa dinâmica dos Clientes
$("#cliente-agendamento-pesquisar-alterar").keyup(function () {
    let nomePesquisa = $("#cliente-agendamento-pesquisar-alterar").val();

    //Começa pesquisar a partir do 2 Caractere
    if (nomePesquisa.length > 2) {
        var form = new FormData();
        form.append('nome-pesquisa', nomePesquisa);
        //Diz se é com a intenção de alterar ou cadastrar
        form.append('tipo-pesquisa', 'alterar');

        jQuery.ajax({
            url: '/cliente/buscar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {

                data = JSON.parse(result)

                $('#table-cliente-pesquisar-body-alterar').html(data.table);

                //Para acionar o Popover do Cliente
                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl)
                })

            },
            error: function (result) {
                $('#table-cliente-pesquisar-body-alterar').html('Houve um erro ao processar as informações.');
            }
        });
    }
});
////////////////////Realacionado a alteração////////////////////


function limparModalServicoAgendamento() {
    $('#table-servico-pesquisar-body').html('');
    $("#servico-agendamento-pesquisar").val('');

    $('#table-cliente-pesquisar-body').html('');
    $("#cliente-agendamento-pesquisar").val('');
}

function limparModalCadastroAgendamento() {

    $("#cliente-agendamento").val('').removeClass('border border-success border-danger');
    $("#text-cliente-agendamento").val('').removeClass('text-success text-danger');
    $("#id-agendamento-cliente").val('');

    $("#servico-agendamento").val('').removeClass('border border-success border-danger');
    $("#text-servico-agendamento").val('').removeClass('text-success text-danger');
    $("#id-servico-cliente").val('');

    $("#valor-agendamento").val('').removeClass('border border-success border-danger');
    $("#text-valor-agendamento").val('').removeClass('text-success text-danger');

    $("#modal-cadastrar #start").removeClass('border border-success border-danger');
    $("#text-start").val('').removeClass('text-success text-danger');

    $("#modal-cadastrar #end").removeClass('border border-success border-danger');
    $("#text-end").val('').removeClass('text-success text-danger');

    $("#observacao-agendamento").val('');

    $('#check-enviar-email').prop("checked", false);

}

function limparModalAlterarAgendamento() {

    $("#cliente-agendamento-alterar").val('').removeClass('border border-success border-danger');
    $("#text-cliente-agendamento-alterar").html('').removeClass('text-success text-danger');
    $("#id-agendamento-cliente-alterar").val('');

    $("#servico-agendamento-alterar").val('').removeClass('border border-success border-danger');
    $("#text-servico-agendamento-alterar").html('').removeClass('text-success text-danger');
    $("#id-servico-cliente-alterar").val('');

    $("#valor-agendamento-alterar").val('').removeClass('border border-success border-danger');
    $("#text-valor-agendamento-alterar").html('').removeClass('text-success text-danger');

    $("#start-alterar").removeClass('border border-success border-danger');
    $("#text-start-alterar").html(' ').removeClass('text-success text-danger');

    $("#end-alterar").removeClass('border border-success border-danger');
    $("#text-end-alterar").html('').removeClass('text-success text-danger');

    $("#observacao-agendamento-alterar").val('');

    $('#check-enviar-email-alterar').prop("checked", false);

}

function cadastrarAgendamento() {

    var form = new FormData($("#form-agendamento")[0]);

    //Campos de input a serem validados no cadastro 
    let cliente = $("#cliente-agendamento");
    let servico = $("#servico-agendamento");
    let valor = $("#valor-agendamento");
    let start = $("#modal-cadastrar #start");
    let end = $("#modal-cadastrar #end");
    let observacao = $("#observacao-agendamento");

    let inputs = new Array();
    inputs.push(cliente, servico, valor, end, start, observacao);

    if (verificaInputsAgendamento(start, end, inputs)) {

        //Mudando o status do botão até o fim da requisição
        $('#submit-agendamento').prop("disabled", true);

        jQuery.ajax({
            url: '/agendamento/cadastrar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {
                data = JSON.parse(result);

                if (data.status == 0) {

                    //Voltando a propriedade do botão
                    $('#submit-agendamento').prop("disabled", false);

                    $('#modal-cadastrar').modal('hide');
                    limparModalCadastroAgendamento();

                    $('#alert-agendamento').addClass("alert-success")
                    $('#alert-agendamento').fadeIn().html(data.success);

                    atualizaFullCalendar();
                    $('#qtd').html(parseInt($('#qtd').text()) + 1);

                    setTimeout(function () {
                        $('#alert-agendamento').fadeOut('Slow');
                    }, 4000);

                } else {

                    //Voltando a propriedade do botão
                    $('#submit-agendamento').prop("disabled", false);

                    $('#alert-erro').addClass("alert-danger")
                    $('#alert-erro').fadeIn().html(data.erro);

                    setTimeout(function () {
                        $('#alert-erro').fadeOut('Slow');
                    }, 4000);
                }

            }
        });
    }
}

function alterarAgendamento() {

    var form = new FormData($("#form-agendamento-alterar")[0]);

    //Campos de input a serem validados no cadastro 
    let idAgendamento = $('#id-agendamento-alterar').val();
    let cliente = $("#cliente-agendamento-alterar");
    let servico = $("#servico-agendamento-alterar");
    let valor = $("#valor-agendamento-alterar");
    let start = $("#div-editar #start-alterar");
    let end = $("#div-editar #end-alterar");
    let observacao = $("#observacao-agendamento-alterar");

    let inputs = new Array();
    inputs.push(cliente, servico, valor, end, start, observacao);

    if (verificaInputsAgendamento(start, end, inputs, 0, idAgendamento)) {

        //Mudando o status do botão até o fim da requisição
        $('#submit-agendamento-alterar').prop("disabled", true);

        jQuery.ajax({
            url: '/agendamento/alterar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {
                data = JSON.parse(result)

                if (data.status == 0) {

                    //Voltando a propriedade do botão
                    $('#submit-agendamento-alterar').prop("disabled", false);

                    $('#visualizar').modal('hide');
                    limparModalAlterarAgendamento();

                    atualizaFullCalendar();

                    setTimeout(function () {
                        $('#alert-agendamento').fadeOut('Slow');
                    }, 4000);

                } else {

                    //Voltando a propriedade do botão
                    $('#submit-agendamento-alterar').prop("disabled", false);

                    $('#alert-erro').addClass("alert-danger")
                    $('#alert-erro').fadeIn().html(data.erro);

                    setTimeout(function () {
                        $('#alert-erro').fadeOut('Slow');
                    }, 4000);
                }

            }
        });
    }
}

function excluirAgendamento(idAgendamentoExcluir = null, statusAgendamento = 0) {

    let confirmar = confirm('Confirma exclusão?');

    if (confirmar) {

        var form = new FormData();

        let tipoExclusao;

        if (idAgendamentoExcluir != null) {
            form.append('idAgendamento', idAgendamentoExcluir);
            tipoExclusao = 'tabela';
        } else {
            form.append('idAgendamento', idAgendamento);
            tipoExclusao = 'calendar';
        }

        if (idAgendamento != null || idAgendamentoExcluir != null) {
            jQuery.ajax({
                url: '/agendamento/excluir',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: form,
                timeout: 8000,
                success: function (result) {
                    data = JSON.parse(result)

                    if (data.status == 0) {
                        $('#visualizar').modal('hide');

                        atualizaFullCalendar();
                        atualizaTabelaAgendamento();

                        //Verificando se precisa decrementar, ja que só é feito caso seja agendamento que estava em aberto
                        (tipoExclusao == 'calendar' || statusAgendamento == 1) ? $('#qtd').html(parseInt($('#qtd').text()) - 1) : '';

                        //Definindo onde será exibida a mensagem
                        if (tipoExclusao == 'calendar') {
                            $('#alert-agendamento').addClass("alert-success")
                            $('#alert-agendamento').fadeIn().html(data.success);

                            setTimeout(function () {
                                $('#alert-agendamento').fadeOut('Slow');
                            }, 4000);
                        } else if (tipoExclusao == 'tabela') {
                            $('#alert-agendamento-tabela').addClass("alert-success")
                            $('#alert-agendamento-tabela').fadeIn().html(data.success);

                            setTimeout(function () {
                                $('#alert-agendamento-tabela').fadeOut('Slow');
                            }, 4000);
                        }

                    } else {
                        $('#alert-erro').addClass("alert-danger")
                        $('#alert-erro').fadeIn().html(data.erro);

                        setTimeout(function () {
                            $('#alert-erro').fadeOut('Slow');
                        }, 4000);
                    }

                }
            });
        }

    }

}

//Função principal que irá validar e permitir ou não o envio do form
function verificaInputsAgendamento(start, end, inputs, tipoForm = 1, idAgendamento = null) {

    //Tipo form diz se é de cadastro ou de alteração
    //1-Cadastro / 2-Alteração
    let status = true;
    let statusMsg;

    //Para uma posterior validação
    let startData = Date.parse(start.val());
    let endData = Date.parse(end.val());

    //As datas porém em formato de String
    let startDataString = start.val();
    let endDataString = end.val();

    inputs.forEach(element => {

        statusMsg = $(`#text-${element.attr('id')}`);

        //Verificando a data de nascimento
        if (element.attr('id') == 'start' || element.attr('id') == 'start-alterar' || element.attr('id') == 'end' || element.attr('id') == 'end-alterar') {

            //Verificando se foi informado
            if (element.val() != '') {

                let dataAgendamento = Date.parse(element.val());

                let data = new Date();
                //Pegando a data Atual
                let dia = String(data.getDate()).padStart(2, '0');
                let mes = String(data.getMonth() + 1).padStart(2, '0');
                let ano = data.getFullYear();

                //Pegando o horário Atual
                let time = data.getHours().toString().padStart(2, '0') + ':' + data.getMinutes().toString().padStart(2, '0');

                //DataHora Atual
                let dataAtual = Date.parse(ano + '-' + mes + '-' + dia + 'T' + time);

                //Verificando se a data informada para cadastro é menor que atual
                if (dataAgendamento < dataAtual) {

                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('A data dever ser maior ou igual hoje!');
                    status = false;

                } else if (startData != null && endData != null && startData >= endData) {

                    //Verificando se a data do inicio é maior que a data do término
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('Data/Hora do início não pode ser maior que Data/Hora Término!');
                    status = false;

                } else {

                    //Verificar duplicação das datas
                    if (tipoForm == 0) {

                        if (verificaDuplicacaoData(startDataString, endDataString, tipoForm, idAgendamento) == 0) {
                            element.removeClass('border-danger').addClass('border-success border');
                            statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                        } else {
                            element.removeClass('border-success').addClass('border border-danger');
                            statusMsg.removeClass('text-success').addClass('text-danger').html('Conflito de horários! Já existem agendamento neste intervalo.');
                            status = false;
                        }

                    } else {

                        //Esse é executado quando for o form de cadastro
                        if (verificaDuplicacaoData(startDataString, endDataString) == 0) {
                            element.removeClass('border-danger').addClass('border-success border');
                            statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                        } else {
                            element.removeClass('border-success').addClass('border border-danger');
                            statusMsg.removeClass('text-success').addClass('text-danger').html('Conflito de horários! Já existem agendamento neste intervalo.');
                            status = false;
                        }

                    }
                }

            } else {

                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                status = false;
            }

        } else {//Se cair aqui é apenas os campos que só precisam validar para não ser vazio

            //O campo de observação é opcional
            if (element.attr('id') != 'observacao-agendamento' && element.attr('id') != 'observacao-agendamento-alterar') {

                if ($.trim(element.val()) == '') {
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                    status = false;
                } else {
                    element.removeClass('border-danger').addClass('border-success border');
                    statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                }

            }
        }
    });

    return status;
}

function verificaDuplicacaoData(start, end, tipoForm = 1, idAgendamento = null) {

    //Setando a roda d eacordo com o form
    let rota = (tipoForm == 1) ? '/agendamento/verificar/data' : '/agendamento/verificar/alterar/data';

    let status;
    let form = new FormData();
    form.append('start', start);
    form.append('end', end);

    //Caso for para alteração eu devo passar o id do Cliente
    if (idAgendamento != null) {
        form.append('id-agendamento', idAgendamento);
    }

    jQuery.ajax({
        url: rota,
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: form,
        async: false,
        timeout: 8000,
        success: function (result) {
            data = JSON.parse(result);

            status = data.status;
        },
        error: function (result) {
            alert('Houve um erro!');
        }
    });

    return status;
}


