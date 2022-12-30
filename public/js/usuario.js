//Macaras dos campos de cadastro de Usuario
$('.telefone-mask').mask("(99) 99999-9999");
$('.cpf-mask').mask("999.999.999-99");

//Gera a tabela ao carregar a página
$(document).ready(function () {
    atualizaTabela();
})

//AJAX do cadastro de usuario
function cadastrarUsuario() {

    var form = new FormData($("#form-usuario")[0]);

    //Campos de input a serem validados no cadastro 
    let nome = $("#nome-usuario");
    let cpf = $("#cpf");
    let sexo = $("#sexo");
    let nascimento = $("#nascimento");
    let telefone = $("#telefone");
    let email = $("#email");
    let nivelAcesso = $("#nivel-acesso");

    let inputs = new Array();
    inputs.push(nome, cpf, sexo, nascimento, telefone, email, nivelAcesso);


    if (verificaInputsUsuario(inputs)) {

        //Mudando o status do botão até o fim da requisição
        $('#submit-usuario').prop("disabled", true);

        jQuery.ajax({
            url: '/usuario/cadastrar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {

                data = JSON.parse(result)

                if (data.status == 0) {

                    //Mudando a propriedade do botão
                    $('#submit-usuario').prop("disabled", false);

                    $('form').trigger("reset");

                    $('#alert-cadastrar-usuario').removeClass("d-none")
                    $('#alert-cadastrar-usuario').addClass("alert-success")
                    $('#alert-cadastrar-usuario').fadeIn().html(data.success);

                    atualizaTabela();
                    resetInputs(nome, cpf, sexo, nascimento, telefone, email, nivelAcesso);
                    $('#qtd').html(parseInt($('#qtd').text()) + 1);

                    setTimeout(function () {
                        $('#alert-cadastrar-usuario').fadeOut('Slow');
                        $('##alert-cadastrar-usuario').addClass("d-none")
                    }, 5000);

                } else {

                    //Mudando a propriedade do botão
                    $('#submit-usuario').prop("disabled", false);

                    $('#alert-cadastrar-usuario').removeClass("d-none")
                    $('#alert-cadastrar-usuario').addClass("alert-success")
                    $('#alert-cadastrar-usuario').fadeIn().html(data.erro);

                    setTimeout(function () {
                        $('#alert-erro').fadeOut('Slow');
                        $('##alert-cadastrar-usuario').addClass("d-none")
                    }, 5000);
                }

            }
        });
    }
};

//Pesquisa do usuario
function buscar() {
    atualizaTabela();
}

//Atualizar a tabela ao cancelar e fechar o modal
function cancelarEdicao() {
    atualizaTabela();
};

//AJAX de etualização de usuario
function alteracaoUsuario(numRef, idForm, idModal, pag, idUsuario) {
    //numRef diz a respeito do número do form, sendo assim eu consigo saber qual os numeros dos inputs dentro dele

    let form = new FormData($(idForm)[0]);
    let inputs = new Array();

    if (verificaInputsUsuario(inputs, 0, numRef, idUsuario)) {

        jQuery.ajax({
            url: '/usuario/alterar',
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
                    $('#alert-body').addClass("alert-success")
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

//AJAX da exlusão de usaurio
function exclusaoUsuario(idForm, pagina) {

    let confirmar = confirm('Confirma exclusão?');

    if (confirmar == true) {

        var form = new FormData($(idForm)[0]);

        jQuery.ajax({
            url: '/usuario/excluir',
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
                    $('#alert-body').addClass("alert-success")
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

//Option define qual tipo de Select será executado /Valores default
function atualizaTabela(pagina = 1) {

    //A atualização é trazida com base no que esta no campo de pesquisa
    var form = new FormData($("#form-pesquisar-usuario")[0]);
    form.append('pagina', pagina);

    jQuery.ajax({
        url: '/usuario/atualizar',
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
            $('#pdf-usuario').attr("href", `/usuario/PDF?ordemTipo=${data.ordemTipo}&ordem=${data.ordem}&nome=${data.nome}`);//Vai adicionando gets que vão ser usados na hora de gerar o relatório
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
function verificaInputsUsuario(inputs, tipoForm = 1, numRef, idUsuario = null) {

    //Tipo form diz se é de cadastro ou de alteração
    //1-Cadastro / 2-Alteração
    let status = true;
    let statusMsg;

    //Esse aqui é no caso da alteração, já que os dois(cadastro e alteração) vão usar a mesma função
    if (tipoForm == 0) {

        //Campos de input a serem validados na alteração
        let nome = $("#nome-usuario-alterar-" + numRef);
        let cpf = $("#cpf-alterar-" + numRef);
        let sexo = $("#sexo-alterar-" + numRef);
        let nascimento = $("#nascimento-alterar-" + numRef);
        let telefone = $("#telefone-alterar-" + numRef);
        let email = $("#email-alterar-" + numRef);
        let nivelAcesso = $("#nivel-acesso-alterar-" + numRef);

        inputs.push(nome, cpf, sexo, nascimento, telefone, email, nivelAcesso);
    }

    inputs.forEach(element => {

        statusMsg = $(`#text-${element.attr('id')}`);

        if (element.attr('id') == 'sexo' || element.attr('id') == 'sexo-alterar-' + numRef) {//Verificando o Select de sexo

            if (element.val() == null) {
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html("Escolha uma opção!");
                status = false;
            } else {
                element.removeClass('border-danger').addClass('border-success border');
                statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
            }

        } else if (element.attr('id') == 'nivel-acesso' || element.attr('id') == 'nivel-acesso-alterar-' + numRef) {

            if (element.val() == null) {
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html("Escolha uma opção!");
                status = false;
            } else {
                element.removeClass('border-danger').addClass('border-success border');
                statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
            }

        } else if (element.attr('id') == 'cpf' || element.attr('id') == 'cpf-alterar-' + numRef) {//Verificando o CPF

            if ($.trim(element.val()) == '') {//CPF foi digitado?
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                status = false;
            } else {

                //Caso seja o form de alteração o CPF deve ser tratada de forma diferente, uma vez que ele já consta no banco
                if (tipoForm == 0) {

                    if (validaCPF(element.val())) {//CPF é valido?

                        if (verificaDuplicacaoCpf(element.val(), tipoForm, idUsuario) == 0) {
                            element.removeClass('border-danger').addClass('border-success border');
                            statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                        } else {
                            element.removeClass('border-success').addClass('border border-danger');
                            statusMsg.removeClass('text-success').addClass('text-danger').html('Este CPF já foi cadastrado!');
                            status = false;
                        }

                    } else {
                        element.removeClass('border-success').addClass('border border-danger');
                        statusMsg.removeClass('text-success').addClass('text-danger').html('CPF inválido!');
                        status = false;
                    }
                    //Esse é executado quando for o form de cadastro
                } else {

                    if (validaCPF(element.val())) {//CPF é valido?

                        if (verificaDuplicacaoCpf(element.val()) == 0) {
                            element.removeClass('border-danger').addClass('border-success border');
                            statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                        } else {
                            element.removeClass('border-success').addClass('border border-danger');
                            statusMsg.removeClass('text-success').addClass('text-danger').html('Este CPF já foi cadastrado!');
                            status = false;
                        }

                    } else {
                        element.removeClass('border-success').addClass('border border-danger');
                        statusMsg.removeClass('text-success').addClass('text-danger').html('CPF inválido!');
                        status = false;
                    }

                }

            }

        } else if (element.attr('id') == 'nascimento' || element.attr('id') == 'nascimento-alterar-' + numRef) {//Verificando a data de nascimento

            if (element.val() != '') {
                let dataNascimento = Date.parse(element.val());

                let data = new Date();

                let dia = String(data.getDate()).padStart(2, '0');
                let mes = String(data.getMonth() + 1).padStart(2, '0');
                let ano = data.getFullYear();

                //Data Atual
                let dataAtual = Date.parse(ano + '-' + mes + '-' + dia);

                //Data Minima
                let dataMinima = Date.parse("01-01-" + (ano - 100));

                if (dataNascimento > dataAtual || dataNascimento < dataMinima) {
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('Data de nascimento inválida!');
                    status = false;
                } else {
                    element.removeClass('border-danger').addClass('border-success border');
                    statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                }
            } else {
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                status = false;
            }

        } else if (element.attr('id') == 'email' || element.attr('id') == 'email-alterar-' + numRef) {//Validando o email

            if ($.trim(element.val()) == '' || validarEmail(element.val()) == false) {

                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Email inválido!');
                status = false;
            } else if (tipoForm == 0) {//Caso seja o form de alteração o Email deve ser tratada de forma diferente, uma vez que ele já consta no banco

                if (verificaDuplicacaoEmail(element.val(), tipoForm, idUsuario) == 0) {
                    element.removeClass('border-danger').addClass('border-success border');
                    statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                } else {
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('Este email já foi cadastrado!');
                    status = false;
                }

            } else {

                if (verificaDuplicacaoEmail(element.val()) == 0) {
                    element.removeClass('border-danger').addClass('border-success border');
                    statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                } else {
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('Este email já foi cadastrado!');
                    status = false;
                }

            }

        } else {//Se cair aqui é apenas os campos que só precisa validar para não ser vazio

            if ($.trim(element.val()) == '') {
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                status = false;
            } else {
                element.removeClass('border-danger').addClass('border-success border');
                statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
            }
        }

    });

    return status;
}

//validação de CPF
function validaCPF(cpf) {
    let Soma = 0
    let Resto

    let strCPF = String(cpf).replace(/[^\d]/g, '')

    if (strCPF.length !== 11)
        return false;

    if ([
        '00000000000',
        '11111111111',
        '22222222222',
        '33333333333',
        '44444444444',
        '55555555555',
        '66666666666',
        '77777777777',
        '88888888888',
        '99999999999',
    ].indexOf(strCPF) !== -1)
        return false;

    for (i = 1; i <= 9; i++)
        Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);

    Resto = (Soma * 10) % 11

    if ((Resto == 10) || (Resto == 11))
        Resto = 0

    if (Resto != parseInt(strCPF.substring(9, 10)))
        return false;

    Soma = 0

    for (i = 1; i <= 10; i++)
        Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i)

    Resto = (Soma * 10) % 11

    if ((Resto == 10) || (Resto == 11))
        Resto = 0

    if (Resto != parseInt(strCPF.substring(10, 11)))
        return false;

    return true;
}

//Validando o email
function validarEmail(email) {
    let re = /\S+@\S+\.\S+/;
    return re.test(email);
}

//Verificando duplicação de email 
function verificaDuplicacaoEmail(email, tipoForm = 1, idUsuario) {

    //Setando a roda d eacordo com o form
    let rota = (tipoForm == 1) ? '/usuario/verificar/email' : '/usuario/verificar/alterar/email';

    let status;
    let form = new FormData();
    form.append('email', email);

    //Caso for para alteração eu devo passar o id do Usuario
    if (idUsuario != null) {
        form.append('id-usuario', idUsuario);
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

//Verificando duplicação de CPF
function verificaDuplicacaoCpf(cpf, tipoForm = 1, idUsuario) {

    //Setando a rota d eacordo com o form
    let rota = (tipoForm == 1) ? '/usuario/verificar/cpf' : '/usuario/verificar/alterar/cpf';

    let status;
    let form = new FormData();
    form.append('cpf', cpf);

    //Caso for para alteração eu devo passar o id do Usuario
    if (idUsuario != null) {
        form.append('id-usuario', idUsuario);
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



