

//Gera a tabela ao carregar a página
$(document).ready(function () {
    atualizaTabela();
})

//AJAX do cadastro de cliente
function cadastrarServico() {

    var form = new FormData($("#form-servico")[0]);

    //Campos de input a serem validados no cadastro 
    let descricao = $("#descricao");
    let valor = $("#valor");

    let inputs = new Array();
    inputs.push(descricao, valor);

    if (verificaInputsServico(inputs)) {
        jQuery.ajax({
            url: '/servico/cadastrar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {

                data = JSON.parse(result)

                if (data.status == 0) {

                    $('form').trigger("reset");

                    $('#alert-body').removeClass("d-none")
                    $('#alert-body').addClass("alert-success")
                    $('#alert-body').fadeIn().html(data.success);

                    atualizaTabela();
                    resetInputs(descricao, valor);
                    $('#qtd').html(parseInt($('#qtd').text()) + 1);

                    setTimeout(function () {
                        $('#alert-body').fadeOut('Slow');
                    }, 4000);

                } else {
                    $('#alert-erro').addClass("alert-danger")
                    $('#alert-erro').fadeIn().html(data.erro);

                    setTimeout(function () {
                        $('#alert-erro').fadeOut('Slow');
                        $('#alert-body').addeClass("d-none")
                    }, 4000);
                }

            }
        });
    }
};

//Pesquisa do cliente
function buscar() {
    atualizaTabela();
}

//AJAX de etualização de cliente
function alteracaoServico(numRef, idForm, idModal, pag, idCliente) {
    //numRef diz a respeito do número do form, sendo assim eu consigo saber qual os numeros dos inputs dentro dele

    let form = new FormData($(idForm)[0]);
    let inputs = new Array();

    if (verificaInputsServico(inputs, 0, numRef, idCliente)) {

        jQuery.ajax({
            url: '/servico/alterar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {

                data = JSON.parse(result)

                if (data.status == 0) {

                    $('#alert-body').removeClass("d-none")
                    $('#alert-body').addClass("alert-success")
                    $('#alert-body').fadeIn().html(data.success);
                    $(idModal).modal('hide');


                    atualizaTabela(pag);

                    setTimeout(function () {
                        $('#alert-body').fadeOut('Slow');
                        $('#alert-body').addClass("d-none")
                    }, 5000);

                } else {
                    $('#alert-body').removeClass("d-none")
                    $('#alert-body').addClass("alert-danger")
                    $('#alert-body').fadeIn().html(data.erro);

                    setTimeout(function () {
                        $('#alert-body').fadeOut('Slow');
                        $('#alert-body').addClass("d-none")
                    }, 5000);
                }
            }
        });

    }
}

//AJAX da exlusão de cliente
function exclusaoServico(idForm, pagina, idServico) {

    let confirmar = confirm('Confirma exclusão?');

    if (confirmar == true) {

        if (VerfificaServicoExclusao(idServico)) {

            $('#alert-body').removeClass("d-none")
            $('#alert-body').addClass("alert-danger")
            $('#alert-body').fadeIn().html('Falha na exclusão: Este serviço já esta cadastrado em algum agendamento(Agendado,Concluído ou Cancelado)');

            setTimeout(function () {
                $('#alert-body').fadeOut('Slow');
                $('#alert-body').addClass("d-none")
            }, 5000);

        } else {

            var form = new FormData($(idForm)[0]);

            jQuery.ajax({
                url: '/servico/excluir',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: form,
                timeout: 8000,
                success: function (result) {

                    data = JSON.parse(result)

                    if (data.status == 0) {

                        $('#alert-body').removeClass("d-none")
                        $('#alert-body').addClass("alert-success")
                        $('#alert-body').fadeIn().html(data.success);

                        atualizaTabela(pagina);

                        setTimeout(function () {
                            $('#alert-body').fadeOut('Slow');
                            $('#alert-body').addClass("d-none")

                        }, 5000);
                    } else {

                        $('#alert-body').removeClass("d-none")
                        $('#alert-body').addClass("alert-danger")
                        $('#alert-body').fadeIn().html(data.erro);

                        setTimeout(function () {
                            $('#alert-body').fadeOut('Slow');
                            $('#alert-body').addClass("d-none")
                        }, 5000);
                    }
                }
            });

        }
    }
}

//Option define qual tipo de Select será executado /Valores default
function atualizaTabela(pagina = 1) {

    //A atualização é trazida com base no que esta no campo de pesquisa
    var form = new FormData($("#form-pesquisar-servico")[0]);
    form.append('pagina', pagina);

    jQuery.ajax({
        url: '/servico/atualizar',
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: form,
        timeout: 8000,
        success: function (result) {
            data = JSON.parse(result)

            $('#table-body').html(data.table);
            $('#qtd').html(data.qtdResultados);
            $('#page').html(data.paginacao);
            $('#pdf-servico').attr("href", `/servico/PDF?ordemTipo=${data.ordemTipo}&ordem=${data.ordem}&nome=${data.nome}`);//Vai adicionando gets que vão ser usados na hora de gerar o relatório
        },
        error: function (result) {
            $('#table-body').html('Houve um erro ao processar as informações.');
        }
    });

}

//Funções utilitarias de validação

//Essa função serve para limpar as classes dos inputs após alguma ação bem sucedida
function resetInputs(...inputs) {
    let statusMsg;
    inputs.forEach(element => {
        statusMsg = $(`#text-${element.attr('id')}`);
        element.removeClass('border border-success border-danger').html('');
        statusMsg.removeClass('text-success text-danger').html('');
    });
}

//Função principal que irá validar e permitir ou não o envio do form
function verificaInputsServico(inputs, tipoForm = 1, numRef, idServico = null) {

    //Tipo form diz se é de cadastro ou de alteração
    //1-Cadastro / 2-Alteração
    let status = true;
    let statusMsg;

    //Esse aqui é no caso da alteração, já que os dois(cadastro e alteração) vão usar a mesma função
    if (tipoForm == 0) {

        //Campos de input a serem validados na alteração
        let descricao = $("#descricao-servico-alterar-" + numRef);
        let valor = $("#valor-alterar-" + numRef);

        inputs.push(descricao, valor);
    }

    inputs.forEach(element => {

        statusMsg = $(`#text-${element.attr('id')}`);

        if (element.attr('id') == 'descricao' || element.attr('id') == 'descricao-servico-alterar-' + numRef) {//Descrição

            if ($.trim(element.val()) == '') {//Descrição foi digitado?
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                status = false;
            } else {
                //Caso seja o form de alteração a descrição deve ser tratada de forma diferente, uma vez que ele já consta no banco
                if (tipoForm == 0) {
                    if (verificaDuplicacaoDescricao(element.val(), tipoForm, idServico) == 0) {
                        element.removeClass('border-danger').addClass('border-success border');
                        statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                    } else {
                        element.removeClass('border-success').addClass('border border-danger');
                        statusMsg.removeClass('text-success').addClass('text-danger').html('Este serviço já foi cadastrado!');
                        status = false;
                    }

                    //Esse é executado quando for o form de cadastro
                } else {

                    if (verificaDuplicacaoDescricao(element.val()) == 0) {
                        element.removeClass('border-danger').addClass('border-success border');
                        statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                    } else {
                        element.removeClass('border-success').addClass('border border-danger');
                        statusMsg.removeClass('text-success').addClass('text-danger').html('Este serviço já foi cadastrado!');
                        status = false;
                    }

                }
            }

        } else if (element.attr('id') == 'valor' || element.attr('id') == 'valor-alterar-' + numRef) {//Se cair aqui é apenas os campos que só precisa validar para não ser vazio

            if (element.val() == 0 || element.val() == null) {
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                status = false;
            } else {
                if (element.val() <= 0) {
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('Valor não pode ser menor que 0!');
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

//Verificando duplicação de CPF
function verificaDuplicacaoDescricao(descricao, tipoForm = 1, idServico) {

    //Setando a rota de acordo com o form
    let rota = (tipoForm == 1) ? '/servico/verificar/descricao' : '/servico/verificar/alterar/descricao';

    let status;
    let form = new FormData();
    form.append('descricao', descricao);

    //Caso for para alteração eu devo passar o id do Cliente
    if (idServico != undefined) {
        form.append('id-servico', idServico);
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

//Verifica se o serviço já esta em algum agendamento
function VerfificaServicoExclusao(idServico) {

    let status;
    let form = new FormData();
    form.append('idServico', idServico);

    jQuery.ajax({
        url: '/servico/verificar/exclusao',
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



