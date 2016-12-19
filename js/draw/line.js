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
            layerLine.getSource().removeFeature(e.target.item(0));
            generationWktLine();
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

    layerLine.getSource().getFeatures().length ? $('#wkt').text('MULTILINESTRING( ' + unionFeature + ' )') : $('#wkt').text('');
}
