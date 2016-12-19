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

    drawPoligons.on('drawend', function(e) {
        generationWktPoligons(e);
    });
 });

  $('#editPoligons').click(function(){
    ClearInteractionPoligons();
    $(this).addClass('activeOptions');
    map.addInteraction(selectPoligons);
    map.addInteraction(modifyPoligons);

    selectPoligons.getFeatures().on('add', function(e) {
        var features = e.element;
        features.on('change', function(e) {
            generationWktPoligons();
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
            layerPoligons.getSource().removeFeature(e.target.item(0));
            generationWktPoligons();
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
 }

function generationWktPoligons(){
    var featureWkt, modifiedWkt;
    var unionFeature = [];

    layerPoligons.getSource().forEachFeature(function(f) {
        var featureClone = f.clone();
        featureWkt = wkt.writeFeature(featureClone);

        if(featureWkt.match(/MULTIPOLYGON/)){
            modifiedWkt = (featureWkt.replace(/MULTIPOLYGON/g, '')), slice(1, -1);
        }else{
            modifiedWkt = (featureWkt.replace(/,/g, ', ')).replace(/POLYGON/g, '');
        }

        unionFeature.push(featureWkt);
    });

    layerPoligons.getSource().getFeatures().length ? $('#wkt').text('MULTIPOLYGON( ' + unionFeature + ' )') : $('#wkt').text('');
}
