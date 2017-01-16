$('.poligons').click(function() {
        if (!$(this).hasClass('active')) {
            $('.points').removeClass('active');
            $('#pointsOptions').fadeOut();
            $('.line').removeClass('active');
            $('#lineOptions').fadeOut();
            ClearInteractionPoints();
            ClearInteractionLine();

            $('.poligons').addClass('active');
            $('#poligonsOptions').fadeIn();
        }else{
            $('.poligons').removeClass('active');
            $('#poligonsOptions').fadeOut();
        }
});

 var sourcePoligons = new ol.source.Vector();
 var selectPoligons = new ol.interaction.Select();
 var erasePoligons = new ol.interaction.Select();
 var wkt = new ol.format.WKT();

 var layerPoligons = new ol.layer.Vector({
    source: sourcePoligons,
    visible: true,
    name: 'Poligon'
 });

 var drawPoligons = new ol.interaction.Draw({
    source: sourcePoligons,
    type: 'Polygon'
 });

 var modifyPoligons = new ol.interaction.Modify({
    features: selectPoligons.getFeatures(),
        deleteCondition: function(event) {
            return ol.events.condition.shiftKeyOnly(event) &&
            ol.events.condition.singleClick(event);
        }
 });

 $('#drawPoligons').click(function(){
    ClearInteractionPoligons();
    $(this).addClass('activeOptions');
    map.addInteraction(drawPoligons);
    $('.inserirDado').fadeIn();
    $('.editDado').fadeOut();
    $('.delDado').fadeOut();

    drawPoligons.on('drawend', function(e) {
        generationWktPoligons(e);
    });
 });

  var idCheckPoligons;
  $('#editPoligons').click(function(){
    ClearInteractionPoligons();
    $(this).addClass('activeOptions');
    map.addInteraction(selectPoligons);
    map.addInteraction(modifyPoligons);

    selectPoligons.getFeatures().on('add', function(e) {
        var features = e.element;
        features.on('change', function(e) {
            if(features.get('id')){
                idCheckPoligons = features.get('id');
                generationWktPoligonsEdit();
             }else{
                generationWktPoligons();
            }
        });
        $('.inserirDado').fadeOut();
        $('.editDado').fadeIn();
        $('.delDado').fadeOut();

        $(".editDado input").each(function(){
            var colunmsName = $(this).attr('name');
            if(colunmsName != 'callback' && colunmsName != 'callback_action' && colunmsName != 'responsavel' && colunmsName != 'geom' && colunmsName != 'map'){
                $('.editDado input[name="'+colunmsName+'"').val(features.get(colunmsName));
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

$('#erasePoligons').click(function(){
    ClearInteractionPoligons();
    $(this).addClass('activeOptions');
    map.addInteraction(erasePoligons);

    erasePoligons.getFeatures().on('change:length', function(e) {
        if(e.target.getArray().length !== 0){
            erasePoligons.getFeatures().on('add', function(f) {
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

 $('#panPoligons').click(function(){
    ClearInteractionPoligons();
    $(this).addClass('activeOptions');
    return false;
 });

 map.addLayer(layerPoligons);

 function ClearInteractionPoligons(){
        $("#poligonsOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(drawPoligons);
        map.removeInteraction(selectPoligons);
        map.removeInteraction(erasePoligons);
 }

function generationWktPoligons(){
    var featureWkt;

    layerPoligons.getSource().forEachFeature(function(f) {
        var featureClone = f.clone();
        featureWkt = wkt.writeFeature(featureClone);
    });

    layerPoligons.getSource().getFeatures().length ? $(".draw_form input[name='geom']").val(featureWkt) : $(".draw_form input[name='geom']").val('');
}

function generationWktPoligonsEdit(){
    var featureWkt;

    if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    if(f.get('id') == idCheckPoligons){
                        var featureClone = f.clone();
                        featureWkt = wkt.writeFeature(featureClone);
                    }
                });

                $(".editDado input[name='geom']").val(featureWkt);
            }
        });
    }
}