$('.points').click(function() {
    if (!$(this).hasClass('active')) {
        $('.line').removeClass('active');
        $('#lineOptions').fadeOut();
        $('.poligons').removeClass('active');
        $('#poligonsOptions').fadeOut();
        clearInteraction('line');
        clearInteraction('polygon');

        $('.points').addClass('active');
        $('#pointsOptions').fadeIn();
    }else{
        $('.points').removeClass('active');
        $('#pointsOptions').fadeOut();
    }
});


//VARIAVEIS (OBJETOS) -> line - PARA A INTERAÇÃO COM O MAPA
var erasePoint = new ol.interaction.Select();
var editPoint = new ol.interaction.Select();
var wkt = new ol.format.WKT();

var drawPoints  = new ol.interaction.Draw({
    source: vectorSourceAtual,
    type: 'Point'
});

//AO CLICAR NO BOTÃO [ ]
$('#panPoint').click(function(){
    clearInteraction('points');
    $(this).addClass('activeOptions');

    return false;
});

//AO CLICAR NO BOTÃO DE DESENHAR
$('#drawPoint').click(function(){
    clearInteraction('points');
    $(this).addClass('activeOptions');
    $('.inserirDado').fadeIn();
    $('.editDado').fadeOut();
    $('.duplicDado').fadeOut();
    $('.delDado').fadeOut();

    var actExiste = 0;
    if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    if(f.get('id') == 'waitingCheck'){
                        actExiste=1;
                    }
                });
            }
        });
    }
    if(actExiste==0){
        map.addInteraction(drawPoints);
        drawPoints.on('drawend', function(e) {
            addFeature(e.feature);
            generationWkt(e.feature, "insert");

            map.removeInteraction(drawPoints);
        });
    }
    return false;

});

//AO CLICAR NO BOTÃO EDIÇÃO
$('#editPoint').click(function(){
    clearInteraction('points');
    $(this).addClass('activeOptions');
    map.addInteraction(editPoint);

    editPoint.getFeatures().on('add', function(e) {
        var featSelect = e.element;
        if(featSelect.get("id")!='waitingCheck' && featSelect.get("id")!=null){
            $('.inserirDado').fadeOut();
            $('.editDado').fadeIn();
            $('.duplicDado').fadeOut();
            $('.delDado').fadeOut();

            getAttribs(featSelect, "editDado");
        }
    });
});

//AO CLICAR NO BOTÃO DE EXCLUSÃO
$('#erasePoint').click(function(){
    clearInteraction('points');
    $(this).addClass('activeOptions');
    map.addInteraction(erasePoint);

    erasePoint.getFeatures().on('change:length', function(e) {
        if(e.target.getArray().length !== 0){
            erasePoint.getFeatures().on('add', function(f) {
                excluiFeature(f.element);
                });
            }
     });
    return false;
});