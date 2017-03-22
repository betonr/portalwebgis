$('.poligons').click(function() {
        if (!$(this).hasClass('active')) {
            $('.points').removeClass('active');
            $('#pointsOptions').fadeOut();
            $('.line').removeClass('active');
            $('#lineOptions').fadeOut();
            clearInteraction('points');
            clearInteraction('line');

            $('.poligons').addClass('active');
            $('#poligonsOptions').fadeIn();
        }else{
            $('.poligons').removeClass('active');
            $('#poligonsOptions').fadeOut();
        }
});

//VARIAVEIS (OBJETOS) -> polygon - PARA A INTERAÇÃO COM O MAPA
var editPoligons = new ol.interaction.Select();
var duplicPoligons = new ol.interaction.Select();
var erasePoligons = new ol.interaction.Select();
var wkt = new ol.format.WKT();

var drawPoligons = new ol.interaction.Draw({
    source: vectorSourceAtual,
    type: 'Polygon'
});

var modifyPoligons = new ol.interaction.Modify({
    features: editPoligons.getFeatures(),
    deleteCondition: function(event) {
        return ol.events.condition.shiftKeyOnly(event) && ol.events.condition.singleClick(event);
    }
});

//AO CLICAR NO BOTÃO DE DESENHAR
$('#drawPoligons').click(function(){
    clearInteraction('polygon');
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
        map.addInteraction(drawPoligons);
        drawPoligons.on('drawend', function(e) {
            addFeature(e.feature);
            generationWkt(e.feature, "insert");

            map.removeInteraction(drawPoligons);
        });
    }
    return false;
});

//AO CLICAR NO BOTÃO EDIÇÃO
$('#editPoligons').click(function(){
    clearInteraction('polygon');
    $(this).addClass('activeOptions');
    map.addInteraction(editPoligons);
    map.addInteraction(modifyPoligons);

    editPoligons.getFeatures().on('add', function(e) {
        var featSelect = e.element;
        featSelect.on('change', function(e) {
            if(featSelect.get("id")!='waitingCheck' && featSelect.get("id")!=null){
                generationWkt(featSelect, "edit");
            }else{
                generationWkt(featSelect, "insert");
            }
        });
        if(featSelect.get("id")!='waitingCheck' && featSelect.get("id")!=null){
            $('.inserirDado').fadeOut();
            $('.editDado').fadeIn();
            $('.duplicDado').fadeOut();
            $('.delDado').fadeOut();

            getAttribs(featSelect, "editDado");
        }
    });
});

//AO CLICAR NO BOTÃO PARA DUPLICAR FEATURE
$('#duplicPoligons').click(function(){
    clearInteraction('polygon');
    $(this).addClass('activeOptions');
    map.addInteraction(duplicPoligons);

    duplicPoligons.getFeatures().on('add', function(e) {
        var featSelect = e.element;
        if(featSelect.get("id")!='waitingCheck' && featSelect.get("id")!=null){
            $('.inserirDado').fadeOut();
            $('.editDado').fadeOut();
            $('.duplicDado').fadeIn();
            $('.delDado').fadeOut();

            getAttribs(e.element, "duplicDado");
        }
    });

    return false;
});

//AO CLICAR NO BOTÃO DE EXCLUSÃO
$('#erasePoligons').click(function(){
    clearInteraction('polygon');
    $(this).addClass('activeOptions');
    map.addInteraction(erasePoligons);

    erasePoligons.getFeatures().on('change:length', function(e) {
        if(e.target.getArray().length !== 0){
            erasePoligons.getFeatures().on('add', function(f) {
                excluiFeature(f.element);
                });
            }
     });
    return false;
});

//AO CLICAR NO BOTÃO [ ]
$('#panPoligons').click(function(){
    clearInteraction('polygon');
    $(this).addClass('activeOptions');
    return false;
});
