//Macaras dos campos de cadastro de Cliente
$('.telefone-mask').mask("(99) 99999-9999");
$('.cpf-mask').mask("999.999.999-99");

function alterarUsuarioLogado() {

    let idUsuarioForm = $('#id-usuario-logado').val();

    let form = new FormData($('#form-usuario-logado')[0]);

    if (verificaInputsUsuarioLogado(idUsuarioForm)) {

        jQuery.ajax({
            url: '/usuario/logado/alterar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {

                data = JSON.parse(result)

                if (data.status == 0) {

                    $('#alert-body').addClass("alert-success")
                    $('#alert-body').fadeIn().html(data.success);
                    $('#modalAlterarUsuarioLogado').modal('hide');
                    $(".modal-backdrop").css("display","none");
                    $("html,body").css({"overflow":"auto"});

                    $('#usuarioLogadoNome').html(data.infoAtualizada.nome)
                    limparModalAlterarUsuarioLogado(data.infoAtualizada);

                    $(".modal-backdrop").css("display","none");

                    setTimeout(function () {
                        $('#alert-body').fadeOut('Slow');
                    }, 5000);

                } else {
                    $('#alert-body').addClass("alert-danger")
                    $('#alert-body').fadeIn().html(data.erro);

                    $(".modal-backdrop").css("display","none");

                    setTimeout(function () {
                        $('#alert-body').fadeOut('Slow');
                    }, 5000);
                }

            }
        });
    
    }
}

//Função principal que irá validar e permitir ou não o envio do form
function verificaInputsUsuarioLogado(idUsuario) {

    let inputs = new Array();

    let status = true;
    let statusMsg;

    //Serão usadas na hora da comparação
    let senhaComparar = $("#senha-alterar-logado").val();
    let senhaConfirmarComparar = $("#senha-alterar-logado-confirmar").val();

    //Campos de input a serem validados na alteração
    let nome = $("#nome-usuario-alterar-logado");
    let cpf = $("#cpf-alterar-logado");
    let sexo = $("#sexo-alterar-logado");
    let nascimento = $("#nascimento-alterar-logado");
    let telefone = $("#telefone-alterar-logado");
    let email = $("#email-alterar-logado");
    let senha = $("#senha-alterar-logado");
    let senhaConfirmar = $("#senha-alterar-logado-confirmar");

    inputs.push(nome, cpf, sexo, nascimento, telefone, email, senha, senhaConfirmar);

    inputs.forEach(element => {

        statusMsg = $(`#text-${element.attr('id')}`);

        if (element.attr('id') == 'sexo-alterar-logado') {//Verificando o Select de sexo

            if (element.val() == null) {
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html("Escolha uma opção!");
                status = false;
            } else {
                element.removeClass('border-danger').addClass('border-success border');
                statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
            }

        } else if (element.attr('id') == 'cpf-alterar-logado') {//Verificando o CPF

            if ($.trim(element.val()) == '') {//CPF foi digitado?
                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Campo não pode ser vazio!');
                status = false;
            } else {

                if (validaLogadoCPF(element.val())) {//CPF é valido?

                    if (verificaDuplicacaoLogadoCpf(element.val(), idUsuario) == 0) {
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

        } else if (element.attr('id') == 'nascimento-alterar-logado') {//Verificando a data de nascimento

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

        } else if (element.attr('id') == 'email-alterar-logado') {//Validando o email

            if ($.trim(element.val()) == '' || validarLogadoEmail(element.val()) == false) {

                element.removeClass('border-success').addClass('border border-danger');
                statusMsg.removeClass('text-success').addClass('text-danger').html('Email inválido!');
                status = false;

            } else {

                if (verificaDuplicacaoLogadoEmail(element.val(), idUsuario) == 0) {
                    element.removeClass('border-danger').addClass('border-success border');
                    statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                } else {
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('Este email já foi cadastrado!');
                    status = false;
                }

            }

        } else if (element.attr('id') == 'senha-alterar-logado' || element.attr('id') == 'senha-alterar-logado-confirmar') {

            //A senha no caso da alteração não é algo obrigatório
            if ($.trim(element.val()) != '') {  

                if ($.trim(element.val()).length < 6) { 
                    element.removeClass('border-success').addClass('border border-danger');
                    statusMsg.removeClass('text-success').addClass('text-danger').html('A senha deve ter no mínimo 6 caracteres!');
                    status = false;
                } else {

                    if (senhaComparar != senhaConfirmarComparar) {
                        element.removeClass('border-success').addClass('border border-danger');
                        statusMsg.removeClass('text-success').addClass('text-danger').html('Senhas diferentes');
                        status = false;
                    } else {
                        element.removeClass('border-danger').addClass('border-success border');
                        statusMsg.removeClass('text-danger').addClass('text-success').html("Campo validado!");
                    }

                }
            } else {
                element.removeClass('border border-danger border-success');
                statusMsg.removeClass('text-danger text-success').html("");
            }

            //Se cair aqui é apenas os campos que só precisa validar para não ser vazio
        } else if (element.attr('id') != 'senha-alterar-logado' && element.attr('id') != 'senha-alterar-logado-confirmar') {

            if ($.trim(element.val()) == '' ) {
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
function validaLogadoCPF(cpf) {
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
function validarLogadoEmail(email) {
    let re = /\S+@\S+\.\S+/;
    return re.test(email);
}

//Verificando duplicação de email 
function verificaDuplicacaoLogadoEmail(email, idUsuario) {
    let status;

    let form = new FormData();
    form.append('email', email);
    form.append('id-usuario', idUsuario);

    jQuery.ajax({
        url: '/usuario/verificar/alterar/email',
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
function verificaDuplicacaoLogadoCpf(cpf, idUsuario) {

    let status;

    let form = new FormData();
    form.append('cpf', cpf);
    form.append('id-usuario', idUsuario);

    jQuery.ajax({
        url: '/usuario/verificar/alterar/cpf',
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

function limparModalAlterarUsuarioLogado(dadosForm) {

    $('#nome-usuario-alterar-logado').removeClass('border border-success border-danger').val(dadosForm.nome);
    $('#text-nome-usuario-alterar-logado').removeClass('text-success text-danger').html('');

    $('#cpf-alterar-logado').removeClass('border border-success border-danger').html(dadosForm.cpf);
    $('#text-cpf-alterar-logado').removeClass('text-success text-danger').html('');

    $('#sexo-alterar-logado').removeClass('border border-success border-danger').val();
    $('#text-sexo-alterar-logado').removeClass('text-success text-danger').html('');

    $('#nascimento-alterar-logado').removeClass('border border-success border-danger').val(dadosForm.data_nascimento);
    $('#text-nascimento-alterar-logado').removeClass('text-success text-danger').html('');

    $('#email-alterar-logado').removeClass('border border-success border-danger').val(dadosForm.email);
    $('#text-email-alterar-logado').removeClass('text-success text-danger').html('');

    $('#telefone-alterar-logado').removeClass('border border-success border-danger').val(dadosForm.telefone);
    $('#text-telefone-alterar-logado').removeClass('text-success text-danger').html('');

    $('#senha-alterar-logado').removeClass('border border-success border-danger').val('');
    $('#text-senha-alterar-logado').removeClass('text-success text-danger').html('');

    $('#senha-alterar-logado-confirmar').removeClass('border border-success border-danger').val('');
    $('#text-senha-alterar-logado-confirmar').removeClass('text-success text-danger').html('');

}
