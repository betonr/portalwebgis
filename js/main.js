$(function() {
    //Menu flutuante topo
    $('.maislayers p').click(function(){
        if (!$(this).hasClass('actFloat')) {
            $('.layersFloat').slideToggle();
            $('.layersFixed').slideToggle();
            $(this).addClass('actFloat');
            $(this).removeClass('icon-point-down');
            $(this).addClass('icon-point-up');
        }else{
            $('.layersFloat').slideToggle();
            $('.layersFixed').slideToggle();
            $(this).removeClass('actFloat');
            $(this).removeClass('icon-point-up');
            $(this).addClass('icon-point-down');
        }
    });

    //Coloca todos os formulários em AJAX mode e inicia LOAD ao submeter!
    $('form').not('.ajax_off').submit(function () {
        var form = $(this);
        var callback = form.find('input[name="callback"]').val();
        var callback_action = form.find('input[name="callback_action"]').val();

        form.ajaxSubmit({
            url: 'ajax/' + callback + '.ajax.php',
            data: {callback_action: callback_action},
            dataType: 'json',
            beforeSubmit: function () {
                form.find('.form_load').fadeIn('fast');
                $('.trigger_ajax').fadeOut('fast');
            },
            success: function (data) {
                //REMOVE LOAD
                form.find('.form_load').fadeOut('slow', function () {
                    //EXIBE CALLBACKS
                    if (data.trigger) {
                        var CallBackPresent = form.find('.callback_return');
                        if (CallBackPresent.length) {
                            CallBackPresent.html(data.trigger);
                            $('.trigger_ajax').fadeIn('slow');
                        } else {
                            Trigger(data.trigger);
                        }
                    }

                    //REDIRECIONA
                    if (data.redirect) {
                        window.setTimeout(function () {
                            window.location.href = data.redirect;
                        }, 1500);
                    }

                    //CLEAR INPUT FILE
                    if (!data.error) {
                        form.find('input[type="file"]').val('');
                    }

                    //CLEAR INPUT's Draw
                    if (data.clearInput) {
                        form.find('input[id="clearForm"]').val('');
                        window.setTimeout(function () {
                            $('.trigger_ajax').fadeOut('slow');
                        }, 3000);
                    }

                });
            }
        });
        return false;
    });

    //Ocultra Trigger clicada
    $('html').on('click', '.trigger_ajax, .trigger_modal', function () {
        $(this).fadeOut('slow', function () {
            $(this).remove();
        });
    });

    //Botão de exclusão
    //DELETE CONFIRM
    $('.j_delete_action').click(function () {
        var RelToMap = $(this).attr('rel');
        $(this).fadeOut('fast', function () {
            $('.j_delete_action_confirm:eq(0)').fadeIn('fost');
        });
    });

    $('.j_delete_action_confirm').click(function () {
        var PreventMap = $(this);
        var DelIdMap = $(this).attr('id');
        var RelToMap = $(this).attr('rel');
        var CallbackMap = $(this).attr('callback');
        var repIdMap = $(this).attr('rep');
        var Callback_actionMap = $(this).attr('callback_action');
        $.post('ajax/' + CallbackMap + '.ajax.php', {callback: CallbackMap, callback_action: Callback_actionMap, del_id: DelIdMap, rep_id: repIdMap}, function (data) {
            if (data.trigger) {
                $('.j_delete_action_confirm:eq(0)').fadeOut('fast', function () {
                    $('.j_delete_action:eq(0)').fadeIn('slow');
                });
            }

            //REDIRECIONA
            if (data.redirect) {
                window.setTimeout(function () {
                    window.location.href = data.redirect;
                }, 500);
            }
        }, 'json');
    });

    //CLOSE POPUP FORMULÁRIOS DE INSERÇÃO
    $('.closeForm').click(function () {
        $('.draw_form').fadeOut();
    });

     //CLOSE POPUP STYLE MAP
    $('.closeStyle').click(function () {
        $('.editStyle').fadeOut();
    });

    //OPEN POPUP STYLE MAP
    $('.linkstyle').click(function () {
        $('.editStyle').fadeIn();
    });
});
