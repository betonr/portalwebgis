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
 var dividirLine = new ol.interaction.Select();

 var eraseLine = new ol.interaction.Select();
 var wkt = new ol.format.WKT();
 var wktdiv = new ol.format.WKT();
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

