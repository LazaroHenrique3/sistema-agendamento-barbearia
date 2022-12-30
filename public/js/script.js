//Autenticacao Login
function validaLogin() {
    const usuario = document.querySelector("#email-login");
    const senha = document.querySelector("#senha");

    return verificaInputsLogin(usuario, senha);
}

function recuperarSenha() {

    let email = document.getElementById('email-recuperar-senha');

    let form = new FormData();
    form.append('email', email.value);

    if (verificaInputsLogin(email)) {

        //Mudando o status do botão até o fim da requisição
        $('#btn-recuperar-senha').prop("disabled", true);
        let email = $('#email-recuperar-senha').val();

        jQuery.ajax({
            url: '/usuario/recuperar/senha',
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
                    $('#btn-recuperar-senha').prop("disabled", false);

                    $('#alert-login').addClass("alert-success");
                    $('#alert-login').fadeIn().html(data.msg);

                    $("#email-recuperar-senha").removeClass('border boder-success border-danger').val('');
                    $("#text-email-recuperar-senha").removeClass('text-success text-danger').html('');
                    $('#email-login').val(email);

                    $('#modal-recuperar-senha').modal('hide');

                    setTimeout(function () {
                        $('#alert-login').fadeOut('Slow');
                    }, 6000);

                } else {

                    //Mudando a propriedade do botão
                    $('#btn-recuperar-senha').prop("disabled", false);

                    $('#alert').addClass("alert-danger")
                    $('#alert').fadeIn().html(data.msg);

                    $("#email-recuperar-senha").removeClass('border boder-success border-danger')
                    $("#text-email-recuperar-senha").removeClass('text-success text-danger').html('')

                    setTimeout(function () {
                        $('#alert').fadeOut('Slow');
                    }, 6000);
                }

            }
        });
    }
}

//Valida Cadastro de Cliente
function verificaInputsLogin(...inputs) {

    let status = true;
    inputs.forEach(element => {

        let statusMsg = document.querySelector(`#text-${element.id}`);

        if (element.value.trim() == '') {
            validaInput(element, 'danger', statusMsg);
            status = false;
        } else {

            if(element.id == 'email-login' || element.id == 'email-recuperar-senha'){

                if (validarLogadoEmail(element.value.trim())) {
                    validaInput(element, 'success', statusMsg);
                } else {
                    validaInput(element, 'danger', statusMsg);
                    status = false;
                }

            } else {
                validaInput(element, 'success', statusMsg);
            }
        }
    });

    return status;
}

//Validando o email
function validarLogadoEmail(email) {
    let re = /\S+@\S+\.\S+/;
    return re.test(email);
}

//Serve para apresentar as mensagens e sinais nos inputs
function validaInput(element, status, statusMsg){
    if(status == 'danger') {

        element.classList.add("border");
        element.classList.add('border-danger');
        element.classList.remove('border-success');
    
        statusMsg.classList.remove('text-success');
        statusMsg.classList.add('text-danger');
        statusMsg.innerHTML = "Dado inválido!";

    } else if(status == 'success'){

        element.classList.add("border");
        element.classList.add('border-success');
        element.classList.remove('border-danger');

        statusMsg.classList.remove('text-danger');
        statusMsg.classList.add('text-success');
        statusMsg.innerHTML = "Dado validado!";

    }
}


