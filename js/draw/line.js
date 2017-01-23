 $('.line').click(function() {
        if (!$(this).hasClass('active')) {
            $('.points').removeClass('active');
            $('#pointsOptions').fadeOut();
            $('.poligons').removeClass('active');
            $('#poligonsOptions').fadeOut();
            ClearInteractionPoints();
            ClearInteractionPoligons();

            $('.line').addClass('active');
            $('#lineOptions').fadeIn();
        }else{
            $('.line').removeClass('active');
            $('#lineOptions').fadeOut();
        }
});

 var sourceLine = new ol.source.Vector();
 var selectLine = new ol.interaction.Select();
 var duplicLine = new ol.interaction.Select();
 var eraseLine = new ol.interaction.Select();
 var wkt = new ol.format.WKT();
 var resultWkt = true;

 var layerLine = new ol.layer.Vector({
    source: sourceLine,
    visible: true,
    name: 'lines'
 });

 var drawLine = new ol.interaction.Draw({
    source: sourceLine,
    type: 'LineString'
 });

 var modifyLine = new ol.interaction.Modify({
    features: selectLine.getFeatures(),
        deleteCondition: function(event) {
            return ol.events.condition.shiftKeyOnly(event) &&
            ol.events.condition.singleClick(event);
        }
 });

 $('#drawLine').click(function(){
    ClearInteractionLine();
    $(this).addClass('activeOptions');
    map.addInteraction(drawLine);
    $('.inserirDado').fadeIn();
    $('.editDado').fadeOut();
    $('.duplicDado').fadeOut();
    $('.delDado').fadeOut();

    drawLine.on('drawend', function(e) {
        var featureAddLine = e.feature;
        generationWktLine(e);
    });
 });

    var idCheck;
  $('#editLine').click(function(){
    ClearInteractionLine();
    $(this).addClass('activeOptions');
    map.addInteraction(selectLine);
    map.addInteraction(modifyLine);

    selectLine.getFeatures().on('add', function(e) {
        var features = e.element;
        features.on('change', function(e) {
                if(features.get('id')){
                    idCheck = features.get('id');
                    generationWktEditLine();
                }else{
                    generationWktLine();
                }
        });
        $('.inserirDado').fadeOut();
        $('.editDado').fadeIn();
        $('.duplicDado').fadeOut();
        $('.delDado').fadeOut();

        $(".editDado input").each(function(){
            var colunmsName = $(this).attr('name');
            if(colunmsName != 'callback' && colunmsName != 'callback_action' && colunmsName != 'responsavel' && colunmsName != 'geom' && colunmsName != 'map'){
                $('.editDado input[name="'+colunmsName+'"').val(features.get(colunmsName));
            }
            if(colunmsName == 'camadas'){
                var camadasSelect = features.get(colunmsName);
                for(var i=1; i<=7; i++){
                    var searchCam = i+',';
                    if(camadasSelect.indexOf(searchCam) != -1){
                        $('.editDado input[name="'+i+'"').prop("checked", true);
                    }else{
                        $('.editDado input[name="'+i+'"').prop("checked", false);
                    }
                }
            }
        });
        var jsonAutor = $('#jsonAutor').text();
        jsonAutor = JSON.parse(jsonAutor);

        jsonAutor.forEach(function(resultado) {
            if(resultado.id == features.get('rep_id')){
                $('.editDado input[name="autor"]').val(resultado.name);
            }
        });
    });

    return false;
    });

  $('#duplicLine').click(function(){
    ClearInteractionLine();
    $(this).addClass('activeOptions');
    map.addInteraction(duplicLine);

    duplicLine.getFeatures().on('add', function(e) {
        var features = e.element;
        $('.inserirDado').fadeOut();
        $('.editDado').fadeOut();
        $('.duplicDado').fadeIn();
        $('.delDado').fadeOut();

        $(".duplicDado input").each(function(){
            var colunmsName = $(this).attr('name');
            if(colunmsName != 'callback' && colunmsName != 'callback_action' && colunmsName != 'responsavel' && colunmsName != 'map'){
                $('.duplicDado input[name="'+colunmsName+'"').val(features.get(colunmsName));
            }
            if(colunmsName == 'camadas'){
                var camadasSelect = features.get(colunmsName);
                for(var i=1; i<=7; i++){
                    var searchCam = i+',';
                    if(camadasSelect.indexOf(searchCam) != -1){
                        $('.duplicDado input[name="'+i+'"').prop("checked", true);
                    }else{
                        $('.duplicDado input[name="'+i+'"').prop("checked", false);
                    }
                }
            }
        });
        var jsonAutor = $('#jsonAutor').text();
        jsonAutor = JSON.parse(jsonAutor);

        jsonAutor.forEach(function(resultado) {
            if(resultado.id == features.get('rep_id')){
                $('.duplicDado input[name="autor"]').val(resultado.name);
            }
        });
    });

    return false;
    });

$('#eraseLine').click(function(){
    ClearInteractionLine();
    $(this).addClass('activeOptions');
    map.addInteraction(eraseLine);

    eraseLine.getFeatures().on('change:length', function(e) {
        if(e.target.getArray().length !== 0){
            eraseLine.getFeatures().on('add', function(f) {
                var features = f.element;

                var Prevent = $(this);
                var DelId = features.get('id');
                var tabName = features.get('tabName');
                var Callback = 'Draw';
                var Callback_action = 'draw_delete';
                $.post('ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: Callback_action, del_id: DelId, tb_name: tabName}, function (data) {

                    if (data.trigger) {
                        if (bases instanceof ol.layer.Group){
                            bases.getLayers().forEach(function(sublayer){
                                if (sublayer.get('name') == 'mapAtual') {
                                    sublayer.getSource().removeFeature(e.target.item(0));
                                }
                            });
                        }
                    }
                    }, 'json');
                });
            }

     });
    return false;
 });

 $('#panLine').click(function(){
    ClearInteractionLine();
    $(this).addClass('activeOptions');

    return false;
 });

map.addLayer(layerLine);

 function ClearInteractionLine(){
        $("#lineOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(drawLine);
        map.removeInteraction(selectLine);
        map.removeInteraction(duplicLine);
        map.removeInteraction(eraseLine);
 }

function generationWktLine(){
    var featureWkt;

    layerLine.getSource().forEachFeature(function(f) {
        var featureClone = f.clone();
        featureWkt = wkt.writeFeature(featureClone);
    });

    layerLine.getSource().getFeatures().length ? $(".draw_form input[name='geom']").val(featureWkt) : $(".draw_form input[name='geom']").val('');
}

function generationWktEditLine(){
    var featureWkt;

    if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    if(f.get('id') == idCheck){
                        var featureClone = f.clone();
                        featureWkt = wkt.writeFeature(featureClone);
                    }
                });

                $(".editDado input[name='geom']").val(featureWkt);
            }
        });
    }
}


