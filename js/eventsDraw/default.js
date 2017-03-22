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

