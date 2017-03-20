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

//VARIAVEIS (OBJETOS) -> line - PARA A INTERAÇÃO COM O MAPA
var editLine = new ol.interaction.Select();
var duplicLine = new ol.interaction.Select();
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
    clearInteraction();
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
            addLine(e.feature);
            generationWkt(e.feature, "insert");

            map.removeInteraction(drawLine);
        });
    }
    return false;
 });

//AO CLICAR NO BOTÃO EDIÇÃO
$('#editLine').click(function(){
    clearInteraction();
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
    clearInteraction();
    $(this).addClass('activeOptions');
    map.addInteraction(duplicLine);

    duplicLine.getFeatures().on('add', function(e) {
        $('.inserirDado').fadeOut();
        $('.editDado').fadeOut();
        $('.duplicDado').fadeIn();
        $('.delDado').fadeOut();
        getAttribs(e.element, "duplicDado");
    });

    return false;
});

/*
$('#dividirLine').click(function(){
    ClearInteractionLine();
    $(this).addClass('activeOptions');
    map.addInteraction(dividirLine);

    dividirLine.getFeatures().on('add', function(e) {
        var features = e.element;
        $('.inserirDado').fadeOut();
        $('.editDado').fadeOut();
        $('.duplicDado').fadeOut();
        $('.delDado').fadeOut();

         LineDivPoint  = new ol.interaction.Draw({
            source: sourceLine,
            type: 'LineString'
        });
        map.addInteraction(LineDivPoint);
        LineDivPoint.on('drawend', function(d){
            var feature = d.feature;
            var coordsdiv = feature.getGeometry().getCoordinates();
                coordsdiv = coordsdiv.toString().split(",");
            var geomLine = wktdiv.writeFeature(features);
                var valTextI = geomLine.indexOf('(');
                var valTextF = geomLine.indexOf(')');
            var AlterGeomLine = geomLine.substring(valTextI+1, valTextF);
            var arrayGeom = AlterGeomLine.split(",");

            var i = 0;
            var actDiv = 0;
            while(i<arrayGeom.length-1){
                var vInicial = arrayGeom[i].split(" ");
                var vFinal = arrayGeom[i+1].split(" ");

                vInicial[0] = parseFloat(vInicial[0]);
                vInicial[1] = parseFloat(vInicial[1]);
                vFinal[0] = parseFloat(vFinal[0]);
                vFinal[1] = parseFloat(vFinal[1]);

                coordsdiv[0] = parseFloat(coordsdiv[0]);
                coordsdiv[1] = parseFloat(coordsdiv[1]);
                coordsdiv[2] = parseFloat(coordsdiv[2]);
                coordsdiv[3] = parseFloat(coordsdiv[3]);

                //calculo da primeira equação
                var mFirst = (vFinal[1]-vInicial[1])/(vFinal[0]-vInicial[0]);
                var cFirst = -(mFirst*vInicial[0])+vInicial[1];

                //calculo da segunda equação
                var mSecond = (coordsdiv[3]-coordsdiv[1])/(coordsdiv[2]-coordsdiv[0]);
                var cSecond = -(mSecond*coordsdiv[0])+coordsdiv[1];

                //soma das equações
                if(mFirst != mSecond){

                    var somaM = mFirst-mSecond;
                    var somaC = -cFirst+cSecond;

                    var x = somaC/somaM;
                    var y = (mFirst*x)+cFirst;

                    //verifica se o ponto criado está localizado em cima de um reta desenhada
                    if(((vInicial[0]<=x) && (vFinal[0]>=x)) || ((vInicial[0]>=x) && (vFinal[0]<=x))){
                        if(((vInicial[1]<=y) && (vFinal[1]>=y)) || ((vInicial[1]>=y) && (vFinal[1]<=y))) {

                            if(((coordsdiv[0]<=x) && (coordsdiv[2]>=x)) || ((coordsdiv[0]>=x) && (coordsdiv[2]<=x))){
                                if(((coordsdiv[1]<=y) && (coordsdiv[3]>=y)) || ((coordsdiv[1]>=y) && (coordsdiv[3]<=y))) {

                                    var confDiv = confirm("Confirmar Divisão? ");

                                    if(confDiv){
                                        //primeira linha da divisão
                                        var firstLine = geomLine;
                                        var vDiv = firstLine.indexOf(vFinal[0]+' '+vFinal[1]);
                                        firstLine = firstLine.substring(0, vDiv);
                                        firstLine = firstLine+x+' '+y+')';

                                        //segunda linha da divisão
                                        var secondLine = geomLine;
                                        secondLine = secondLine.substr(vDiv);
                                        secondLine = 'LINESTRING('+x+' '+y+','+secondLine;

                                        //executando ação no banco de dados
                                        var DivId = features.get('id');
                                        var DivTabName = features.get('tabName');
                                        var DivLinesGeom = firstLine+'+'+secondLine;
                                        var DivAutor = features.get('rep_id');
                                        var DivCallback = 'Draw';
                                        var DivCallback_action = 'draw_dividir';
                                        $.post('ajax/' + DivCallback + '.ajax.php', {callback: DivCallback, callback_action: DivCallback_action, div_id: DivId, tb_name: DivTabName, lines: DivLinesGeom, autor: DivAutor}, function (data) {}, 'json');
                                        actDiv = 1;

                                    }
                                    $i=1000000;
                                    break;

                                }
                            }

                        }
                    }

                }

                i++;
            }

            if(actDiv==1){
                map.removeInteraction(LineDivPoint);
                map.removeInteraction(dividirLine);
                window.alert("\nLinha dividida com sucesso.\nRecarregue a página!\n");
            }else{
                location.reload();
            }

        });

    });

    return false;
});
*/
$('#eraseLine').click(function(){
    clearInteraction();
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

$('#panLine').click(function(){
    clearInteraction();
    $(this).addClass('activeOptions');
    return false;
});

/*

/* function ClearInteractionLine(){
        $("#lineOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(selectLine);
        map.removeInteraction(duplicLine);
        map.removeInteraction(eraseLine);
        map.removeInteraction(dividirLine);

        map.getInteractions().forEach(function (interaction) {
           if (interaction instanceof ol.interaction.Draw) {
               map.removeInteraction(interaction);
           }
        });
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

*/