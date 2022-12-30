$(document).ready(function () {

    atualizaAgendamentos();
    atualizaAgendamentosAtrasados();

});

function concluirAgendamento(idAgendamento) {

    let confirmar = confirm('Confirma conclusão?');

    if (confirmar) {

        var form = new FormData();
        form.append('id-agendamento', idAgendamento);

        jQuery.ajax({
            url: '/agendamento/concluir',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {
                $('#alert-agendamento').addClass("alert-success")
                $('#alert-agendamento').fadeIn().html("Agendamento concluído!");

                atualizaAgendamentos();
                atualizaAgendamentosAtrasados();

                setTimeout(function () {
                    $('#alert-agendamento').fadeOut('Slow');
                }, 4000);

            }
        });

    }

}

function cancelarAgendamento(idAgendamento) {

    let confirmar = confirm('Confirma cancelamento?');

    if (confirmar) {

        var form = new FormData();
        form.append('id-agendamento', idAgendamento);

        jQuery.ajax({
            url: '/agendamento/cancelar',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: form,
            timeout: 8000,
            success: function (result) {
                $('#alert-agendamento').addClass("alert-danger")
                $('#alert-agendamento').fadeIn().html("Agendamento cancelado!");

                atualizaAgendamentos();
                atualizaAgendamentosAtrasados();

                setTimeout(function () {
                    $('#alert-agendamento').fadeOut('Slow');
                }, 4000);

            }
        });

    }

}

function atualizaAgendamentos() {

    jQuery.ajax({
        url: '/agendamento/exibir',
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        timeout: 8000,
        success: function (result) {
            data = JSON.parse(result)

            $('#agendamentos').html(data.agendamentos);
        }
    });

}

function atualizaAgendamentosAtrasados() {

    jQuery.ajax({
        url: '/agendamento/atrasados',
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        timeout: 8000,
        success: function (result) {

            data = JSON.parse(result)

            $('#agendamentos-atrasados').html(data.agendamentos);
        }
    });

}

