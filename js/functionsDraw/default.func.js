//RETIRA AS INTERAÇÕES DOS MAPAS
function clearInteraction(type){
    if(type == 'line'){
        $("#lineOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(editLine);
        map.removeInteraction(drawLine);
        map.removeInteraction(duplicLine);
        map.removeInteraction(modifyLine);
        map.removeInteraction(eraseLine);
    }else if(type == 'polygon'){
        $("#poligonsOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(editPoligons);
        map.removeInteraction(drawPoligons);
        map.removeInteraction(duplicPoligons);
        map.removeInteraction(modifyPoligons);
        map.removeInteraction(erasePoligons);
    }else{
        $("#pointsOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(drawPoints);
        map.removeInteraction(erasePoint);
        map.removeInteraction(editPoint);
    }
}

//GERA UM DADO NO FORMATO WKT
function generationWkt(e, type){
    var featureWkt;

    var featureClone = e.clone();
    featureWkt = wkt.writeFeature(featureClone);

    if(type == "insert"){
        $(".inserirDado input[name='geom']").val(featureWkt);
    }else if(type == "edit"){
        $(".editDado input[name='geom']").val(featureWkt);
    }

}

//ADICIONA A FEATURE NO LAYER DO MAPA
function addFeature(feature){
    feature.setProperties({
        'id': 'waitingCheck'
    });

    if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().addFeatures(feature);
            }
        });
    }
}

//DUPLICA A FEATURE NO LAYER DO MAPA
function cloneFeature(idAnt, id){
    if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    if(f.get('id') == idAnt){
                        var cloneF = f.clone();
                        cloneF.setId(id);
                        cloneF.set('id', id, true);
                        sublayer.getSource().addFeatures(cloneF);
                    }
                });
            }
        });
    }
}

//PREENCHE A FEATURE COM OS ATRIBUTOS DIGITADOS PELO USUÁRIO
function preencheFeature(id, type){
    if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    if(f.get('id') == 'waitingCheck'){
                        f.set('id', id, true);
                        f.setId(id);

                        $("."+type+" input.inF").each(function(){
                            var colunmsName = $(this).attr('name');
                            var inputAtual = $('.'+type+' input[name="'+colunmsName+'"').val();
                            f.set(colunmsName, inputAtual, true);
                        });

                        var inputCamadas = '';
                        $("."+type+" input[type='checkbox']:checked").each(function(){
                            var colunmsName = $(this).attr('name');
                            inputCamadas+=colunmsName+", ";
                        });
                        f.set('camadas', inputCamadas, true);

                        console.log(f);
                    }
                });
            }
        });
    }
}

//MOSTRA OS ATRIBUTOS DA FEATURE NO FORMULARIO DE EDIÇÃO
function getAttribs(feature, type){
    $("."+type+" input").each(function(){
        var colunmsName = $(this).attr('name');
        if(colunmsName != 'callback' && colunmsName != 'callback_action' && colunmsName != 'responsavel' && colunmsName != 'geom' && colunmsName != 'map'){
            $('.'+type+' input[name="'+colunmsName+'"').val(feature.get(colunmsName));
        }
        if(colunmsName == 'camadas'){
            var camadasSelect = feature.get(colunmsName);
            for(var i=1930; i>=1870; i-=10){
                var searchCam = i+',';
                if(camadasSelect.indexOf(searchCam) != -1){
                    $('.'+type+' input[name="'+i+'"').prop("checked", true);

                }else{
                    $('.'+type+' input[name="'+i+'"').prop("checked", false);
                }
            }
        }
    });
    var jsonAutor = $('#jsonAutor').text();
    jsonAutor = JSON.parse(jsonAutor);

    jsonAutor.forEach(function(resultado) {
        if(resultado.id == feature.get('rep_id')){
            $('.'+type+' input[name="autor"]').val(resultado.name);
        }
    });
}

//ATUALIZA OS ATRIBUTOS DA FEATURE DE ACORDO COM O QUE FOI DIGITADO PELO USUÁRIO
function atualizaFeature(idFeature){
     if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    if(f.get('id') == idFeature){

                        $(".editDado input.inF").each(function(){
                            var colunmsName = $(this).attr('name');
                            var inputAtual = $('.editDado input[name="'+colunmsName+'"').val();
                            f.set(colunmsName, inputAtual, true);
                        });

                        var inputCamadas = '';
                        $(".editDado input[type='checkbox']:checked").each(function(){
                            var colunmsName = $(this).attr('name');
                                inputCamadas+=colunmsName+", ";
                            });
                        f.set('camadas', inputCamadas, true);

                        console.log(f);
                    }
                });
            }
        });
    }
}

//EXCLUI A FEATURE SELECIONADA DO 'LAYERS ATUAL' NO MAPA
function excluiFeature(feature){
    var DelId = feature.get('id');
    if(DelId=='waitingCheck'){
        if (bases instanceof ol.layer.Group){
            bases.getLayers().forEach(function(sublayer){
                if (sublayer.get('name') == 'mapAtual'){
                    sublayer.getSource().removeFeature(feature);
                }
            });
        }
    }else{
        var tabName = feature.get('map');
        var Callback = 'Draw';
        var Callback_action = 'draw_delete';
        $.post('ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: Callback_action, del_id: DelId, tb_name: tabName}, function (data) {

            if (data.trigger) {
                if (bases instanceof ol.layer.Group){
                    bases.getLayers().forEach(function(sublayer){
                        if (sublayer.get('name') == 'mapAtual'){
                            sublayer.getSource().removeFeature(feature);
                        }
                    });
                }
            }

        }, 'json');
    }
}
