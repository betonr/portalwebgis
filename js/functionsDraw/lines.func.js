//ADICIONA A FEATURE NA LAYER DO MAPA
function addLine(feature){
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

//PREENCHE A FEATURE COM OS ATRIBUTOS DIGITADOS PELO USUÁRIO
function preencheFeature(id){
    if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    if(f.get('id') == 'waitingCheck'){
                        f.set('id', id, true);

                        $(".inserirDado input.inF").each(function(){
                            var colunmsName = $(this).attr('name');
                            var inputAtual = $('.inserirDado input[name="'+colunmsName+'"').val();
                            f.set(colunmsName, inputAtual, true);
                        });

                        var inputCamadas = '';
                        $(".inserirDado input[type='checkbox']:checked").each(function(){
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
function getAttribs(feature){
    $(".editDado input").each(function(){
        var colunmsName = $(this).attr('name');
        if(colunmsName != 'callback' && colunmsName != 'callback_action' && colunmsName != 'responsavel' && colunmsName != 'geom' && colunmsName != 'map'){
            $('.editDado input[name="'+colunmsName+'"').val(feature.get(colunmsName));
        }
        if(colunmsName == 'camadas'){
            var camadasSelect = feature.get(colunmsName);
            for(var i=1930; i>=1870; i-=10){
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
        if(resultado.id == feature.get('rep_id')){
            $('.editDado input[name="autor"]').val(resultado.name);
        }
    });
}

//ATUALIZA OS ATRIBUTOS DA FEATURE DE ACORDO COM O QUE FOI DIGITADO PELO USUÁRIO
function atualizaFeature (){
     if (bases instanceof ol.layer.Group){
        bases.getLayers().forEach(function(sublayer){
            if (sublayer.get('name') == 'mapAtual') {
                sublayer.getSource().forEachFeature(function(f) {
                    var idFeature = $(".editDado input[name='id']");
                    if(f.get('id') == idFeature){

                        $(".editDado input.inF").each(function(){
                            var colunmsName = $(this).attr('name');
                            var inputAtual = $('.inserirDado input[name="'+colunmsName+'"').val();
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