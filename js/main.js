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

    //Mascaras de formulário
    $(".formDate").mask("99/99/9999");
    $(".formTime").mask("99/99/9999 99:99");
    $(".formCep").mask("99999-999");
    $(".formCpf").mask("999.999.999-99");

    $('.formPhone').focusout(function () {
        var phone, element;
        element = $(this);
        element.unmask();
        phone = element.val().replace(/\D/g, '');
        if (phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    }).trigger('focusout');

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
                        inputCheck = $('.inserirDado input[type="checkbox"]');
                        inputCheck.attr('checked', false);

                        window.setTimeout(function () {
                            $('.trigger_ajax').fadeOut('slow');
                        }, 3000);
                    }

                    //FORM Draw (display:none)
                    if (data.none) {
                        window.setTimeout(function () {
                            $('.trigger_ajax').fadeOut('slow');
                        }, 2000);
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
            //$('.j_delete_action_confirm:eq(0)').fadeIn('fast');
            $('.' + RelToMap + '[id="' + $(this).attr('id') + '"] .j_delete_action_confirm:eq(0)').fadeIn('fast');
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
                $('.' + RelToMap + '[id="' + PreventMap.attr('id') + '"] .j_delete_action_confirm:eq(0)').fadeOut('fast', function () {
                    $('.' + RelToMap + '[id="' + PreventMap.attr('id') + '"] .j_delete_action:eq(0)').fadeIn('slow');
                });
            }else {
                $('.' + RelToMap + '[id="' + DelIdMap + '"]').fadeOut('slow');
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
    $('.linkstyle_complex').click(function () {
        $('.complex').fadeIn();
        var nameMapStyle = $(this).attr('title');
        var idMapStyle = $(this).attr('id');
        $('.editStyle input[name="map"]').val(nameMapStyle);
        $('.editStyle input[name="id"]').val(idMapStyle);
    });

    $('.linkstyle').click(function () {
        $('.simple').fadeIn();
        var nameMapStyle = $(this).attr('title');
        var idMapStyle = $(this).attr('id');
        $('.editStyle input[name="map"]').val(nameMapStyle);
        $('.editStyle input[name="id"]').val(idMapStyle);
    });

    //btn RECARREGAMENTO de PÁGINA
    $('.recEditado').click(function () {
        var viewCenter = map.getView().getCenter();
        var viewZoom = map.getView().getZoom();

        $.cookie("saveViewCenter", viewCenter);
        $.cookie("saveViewZoom", viewZoom);

        location.reload();
    });

    $('.recDefault').click(function () {

        $.removeCookie("saveViewCenter");
        $.removeCookie("saveViewZoom");

        location.reload();
    });

    //btn ativar TROCA DE SENHA
    $('.actnewPass').click(function () {
        $('.newpass').slideToggle();

        if (!$(this).hasClass('actPass')) {
            $(this).addClass('actPass');
            $(this).text('Não Alterar Senha');
        }else{
            $(this).removeClass('actPass');
            $(this).text('Alterar Senha');
            $('.newpass input').val('');
        }

    });

    //automatização dos formulários de seleção de layers(CHECKBOX)
    $('.top .layersFloat input[type=checkbox]').click(function () {
        var layersName = $(this).val();

        if(layersName == 'mapAtual' || layersName == 'sara' || layersName == 'distritos' || layersName == 'municipios'){
            if($(this).is(":checked") == true){
                $('.top .layersFixed input[value="'+layersName+'"]').prop("checked", true);
            }else{
                $('.top .layersFixed input[value="'+layersName+'"]').prop("checked", false);
            }
        }

    });
    $('.top .layersFixed input[type=checkbox]').click(function () {
        var layersName = $(this).val();

        if($(this).is(":checked") == true){
            $('.top .layersFloat input[value="'+layersName+'"]').prop("checked", true);
        }else{
            $('.top .layersFloat input[value="'+layersName+'"]').prop("checked", false);
        }

    });

    //automatização dos formulários de seleção de layers(RADIO)
    $('.top .layersFloat input[type=radio]').click(function () {
        var layersName = $(this).val();

        if(layersName == 'openstreetmap' || layersName == 'esri' || layersName == 'none'){
            if($(this).is(":checked") == true){
                $('.top .layersFixed input[value="'+layersName+'"]').prop("checked", true);
            }else{
                $('.top .layersFixed input[value="'+layersName+'"]').prop("checked", false);
            }
        }else{
            $('.top .layersFixed input[type=radio]').prop("checked", false);
        }

    });
    $('.top .layersFixed input[type=radio]').click(function () {
        var layersName = $(this).val();

        if($(this).is(":checked") == true){
            $('.top .layersFloat input[value="'+layersName+'"]').prop("checked", true);
        }else{
            $('.top .layersFloat input[value="'+layersName+'"]').prop("checked", false);
        }

    });

});