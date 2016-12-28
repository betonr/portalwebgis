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
 var eraseLine = new ol.interaction.Select();
 var wkt = new ol.format.WKT();

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
    $('.delDado').fadeOut();

    drawLine.on('drawend', function(e) {
        generationWktLine(e);
    });
 });

  $('#editLine').click(function(){
    ClearInteractionLine();
    $(this).addClass('activeOptions');
    map.addInteraction(selectLine);
    map.addInteraction(modifyLine);

    selectLine.getFeatures().on('add', function(e) {
        var features = e.element;
            $('.inserirDado').fadeOut();
            $('.editDado').fadeIn();
            $('.delDado').fadeOut();

            var jsontext = $('#jsonMap').text();
            window.alert(jsontext);
            //$('.editDado input[name="id"').val(features.get('id'));
            //$('.editDado input[name="nome"').val(features.get('nome'));
        features.on('change', function(e) {
            generationWktLine();
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
 }

function generationWktLine(){
    var featureWkt, modifiedWkt;
    var unionFeature = [];

    layerLine.getSource().forEachFeature(function(f) {
        var featureClone = f.clone();
        featureWkt = wkt.writeFeature(featureClone);

        if(featureWkt.match(/MULTILINESTRING/)){
            modifiedWkt = (featureWkt.replace(/MULTILINESTRING/g, '')), slice(1, -1);
        }else{
            modifiedWkt = (featureWkt.replace(/,/g, ', ')).replace(/LINESTRING/g, '');
        }

        unionFeature.push(featureWkt);
    });

    layerLine.getSource().getFeatures().length ? $(".draw_form input[name='geom']").val('MULTILINESTRING( ' + unionFeature + ' )') : $(".draw_form input[name='geom']").val('');
}

