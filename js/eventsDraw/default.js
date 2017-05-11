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

//CLOSE POPUP FORMULÁRIOS DE INSERÇÃO
$('.closeForm').click(function () {
    $('.draw_form').fadeOut();
});

 //CLOSE POPUP STYLE MAP
$('.closeStyle').click(function () {
    $('.editStyle').fadeOut();
});

//OPEN / CLOSE TOOBAR SEARCH
$('.search_btn .btn').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }else{
            $(this).addClass('active');
            load_dados();
        }
        $('.searchEnd').toggle();
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

//OPEN POPUP SELECT CAMADAS
$('.selectC').click(function () {
    $('.selectCamadas').fadeIn();
});

//SELECT CAMADAS btn 'selecionar todas'
$('.selectCamadas .todasCamadas').click(function () {
    $('.selectCamadas input[type=checkbox]').prop("checked", true);
});

//CLOSE POPUP STYLE MAP
$('.closeSelectC').click(function () {
    $('.selectCamadas').fadeOut();
});

//CAMPO DE BUSCA POR ENDEREÇO (GEOODIFICAÇÃO)
    //funções que deixa visivel o gif de 'carregando'
    function loading_show(){
        $('.search_load').fadeIn();
    }
    function loading_hide(){
        $('.search_load').fadeOut();
    }

    //Ao digitar ou selecionar outra camada,
    //inicia-se a função de GEOLOCALIZAÇÃO
    $(".searchEnd input[name='searchInput']").keyup(function(){
        load_dados();
    });

    $(".searchEnd .years").keyup(function(){
        if($('.searchEnd input[name="anoI"]').val()>=1868 
            && $('.searchEnd input[name="anoI"]').val()<=1940
            && $('.searchEnd input[name="anoF"]').val()>=1868
            && $('.searchEnd input[name="anoF"]').val()<=1940
            && $('.searchEnd input[name="anoI"]').val() <= $('.searchEnd input[name="anoF"]').val()){
            load_dados();
        }
    });

    //função que carrega os dados para o ajax
    function load_dados(){
        var form = $('#searchForm_end');

        var consulta = form.find('input[name="searchInput"]').val();
        var numberStreet;
        if(consulta.indexOf("-") != -1){ numberStreet = parseInt(consulta.substr(consulta.indexOf("-")+1));}
        if(consulta.indexOf(",") != -1){ numberStreet = parseInt(consulta.substr(consulta.indexOf(",")+1));}
        if(consulta.indexOf(".") != -1) { numberStreet = parseInt(consulta.substr(consulta.indexOf(".")+1));}
        if(consulta.indexOf("/") != -1) { numberStreet = parseInt(consulta.substr(consulta.indexOf("/")+1));}

        var callback = form.find('input[name="callback"]').val();
        var callback_action = form.find('input[name="callback_action"]').val();

        form.ajaxSubmit({
                type: 'POST',
                data: {callback_action: callback_action},
                dataType: 'json',
                url: 'ajax/' + callback + '.ajax.php',
                beforeSend: function(){
                        //loading_show();
                },
                success: function(msg){
                    //loading_hide();
                    var res = msg.sucess;
                    $("#searchResposta").html(res).fadeIn();
                    //ao clicar no botão de visualizar a rua
                    $(".searchResposta p button").click(function(){
                        var idRua = $(this).attr('id');

                        //captura os pontos INICIAIS E FINAIS da rua
                        /* var pontos = $(".searchResposta p").text();
                        pontos = pontos.substr(pontos.indexOf("(")+1);
                        pontos = pontos.substr(0,pontos.indexOf(")"));
                        var pts = pontos.split(",");
                        if(numberStreet%2!=0){
                            var pIni = parseInt(pts[0]);
                            var pFim = parseInt(pts[1]);
                        }else{
                            var pIni = parseInt(pts[2]);
                            var pFim = parseInt(pts[3]);
                        } */           
                        
                        //centraliza o mapa na rua selecionada
                        var extent = ol.extent.createEmpty();
                        bases.getLayers().forEach(function(layer) {
                            if(layer.get('name') == "mapAtual"){
                                var ruaSelect = layer.getSource().getFeatureById(idRua);
                                ol.extent.extend(extent, ruaSelect.getGeometry().getExtent());
                            }
                        });
                        map.getView().fitExtent(extent, map.getSize());
                    });
                }
        });
    }
//fim GEOLOCALIZAÇÃO