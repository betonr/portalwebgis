 $('.line').click(function() {
        if (!$(this).hasClass('active')) {
            $('.points').removeClass('active');
            $('#pointsOptions').fadeOut();
            $('.poligons').removeClass('active');
            $('#poligonsOptions').fadeOut();
            clearInteraction('points');
            clearInteraction('polygon');

            $('.line').addClass('active');
            $('#lineOptions').fadeIn();
        }else{
            $('.line').removeClass('active');
            $('#lineOptions').fadeOut();
        }
});

//VARIAVEIS (OBJETOS) -> line - PARA A INTERAÇÃO COM O MAPA
var editLine = new ol.interaction.Select();
var duplicLine = new ol.interaction.Select();
var dividirLine = new ol.interaction.Select();
var eraseLine = new ol.interaction.Select();
var wkt = new ol.format.WKT();

var drawLine = new ol.interaction.Draw({
    source: vectorSourceAtual,
    type: 'LineString'
});

var modifyLine = new ol.interaction.Modify({
    features: editLine.getFeatures(),
    deleteCondition: function(event) {
        return ol.events.condition.shiftKeyOnly(event) && ol.events.condition.singleClick(event);
    }
});

//AO CLICAR NO BOTÃO DE DESENHAR
$('#drawLine').click(function(){
    clearInteraction('line');
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
        map.addInteraction(drawLine);
        drawLine.on('drawend', function(e) {
            addFeature(e.feature);
            generationWkt(e.feature, "insert");

            map.removeInteraction(drawLine);
        });
    }
    return false;
});

//AO CLICAR NO BOTÃO EDIÇÃO
$('#editLine').click(function(){
    clearInteraction('line');
    $(this).addClass('activeOptions');
    map.addInteraction(editLine);
    map.addInteraction(modifyLine);

    editLine.getFeatures().on('add', function(e) {
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
$('#duplicLine').click(function(){
    clearInteraction('line');
    $(this).addClass('activeOptions');
    map.addInteraction(duplicLine);

    duplicLine.getFeatures().on('add', function(e) {
        var featSelect = e.element;
        if(featSelect.get("id")!='waitingCheck' && featSelect.get("id")!=null){
            $('.inserirDado').fadeOut();
            $('.editDado').fadeIn();
            $('.duplicDado').fadeOut();
            $('.delDado').fadeOut();

            getAttribs(featSelect, "duplicDado");
        }
    });

    return false;
});

//AO CLICAR NO BOTÃO PARA DIVIDIR FEATURE
$('#dividirLine').click(function(){
    clearInteraction('line');
    $(this).addClass('activeOptions');
    map.addInteraction(dividirLine);

    dividirLine.getFeatures().on('add', function(e) {
        var featOriginal = e.element;
        if(featOriginal.get("id")!='waitingCheck' && featOriginal.get("id")!=null){
            $('.inserirDado').fadeOut();
            $('.editDado').fadeOut();
            $('.duplicDado').fadeOut();
            $('.delDado').fadeOut();

            lineDivPoint  = new ol.interaction.Draw({
                source: vectorSourceAtual,
                type: 'LineString'
            });
            map.addInteraction(lineDivPoint);
            lineDivPoint.on('drawend', function(d){

                if(divLine(featOriginal, d.feature)==1){
                    map.removeInteraction(lineDivPoint);
                    map.removeInteraction(dividirLine);
                    window.alert("\nLinha dividida com sucesso.\nRecarregue a página!\n");
                }else{
                    location.reload();
                }

            });
        }
    });

    return false;
});

//AO CLICAR NO BOTÃO DE EXCLUSÃO
$('#eraseLine').click(function(){
    clearInteraction('line');
    $(this).addClass('activeOptions');
    map.addInteraction(eraseLine);

    eraseLine.getFeatures().on('change:length', function(e) {
        if(e.target.getArray().length !== 0){
            eraseLine.getFeatures().on('add', function(f) {
                excluiFeature(f.element);
                });
            }
     });
    return false;
});

//AO CLICAR NO BOTÃO [ ]
$('#panLine').click(function(){
    clearInteraction('line');
    $(this).addClass('activeOptions');
    return false;
});
