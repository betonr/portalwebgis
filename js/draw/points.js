    $('.points').click(function() {
        if (!$(this).hasClass('active')) {
            $('.line').removeClass('active');
            $('#lineOptions').fadeOut();
            $('.poligons').removeClass('active');
            $('#poligonsOptions').fadeOut();
            ClearInteractionLine();
            ClearInteractionPoligons();

            $('.points').addClass('active');
            $('#pointsOptions').fadeIn();
        }else{
            $('.points').removeClass('active');
            $('#pointsOptions').fadeOut();
        }
    });

    var drawPoints;
    var sourcePoint = new ol.source.Vector();
    var erasePoint = new ol.interaction.Select();
    var selectPoint = new ol.interaction.Select();
    var latitude = $("[name='latitude']");
    var longitude = $("[name='longitude']");
    var wktpoint = $(".draw_form input[name='geom']");

    var layerPoints = new ol.layer.Vector({
        source: sourcePoint,
        visible: true,
        name: 'points'
    });

    //if (bases instanceof ol.layer.Group){
        map.addLayer(layerPoints);
    //}

    $('#localizar').click(function(){
        var lat = latitude.val();
        var long = longitude.val();

        if(lat != '' && long != ''){
            sourcePoint.clear();
            sourcePoint.addFeature(
                new ol.Feature({
                    geometry: new ol.geom.Point([parseFloat(long), parseFloat(lat)]).transform('EPSG:4326', 'EPSG:3857')
                })
             );

            wktpoint.val('POINT(' + long + ' ' + lat + ')');
            map.getView().fitExtent(sourcePoint.getExtent(), map.getSize());
            $('.inserirDado').fadeIn();
            $('.editDado').fadeOut();
            $('.delDado').fadeOut();
        }

        return false;
    })

    $('#panPoint').click(function(){
        ClearInteractionPoints();
        $(this).addClass('activeOptions');

        return false;
    });

    $('#drawPoint').click(function(){
        ClearInteractionPoints();
        $(this).addClass('activeOptions');

        drawPoints  = new ol.interaction.Draw({
            source: sourcePoint,
            type: 'Point'
        });
        map.addInteraction(drawPoints);
        drawPoints.on('drawend', function(d){
            var feature = d.feature;
            sourcePoint.clear();
            sourcePoint.addFeature(feature);
            var latLong = feature.getGeometry().getCoordinates();

            generationCoordsPoint(feature);
            $('.inserirDado').fadeIn();
            $('.editDado').fadeOut();
            $('.delDado').fadeOut();
        });

        return false;
    });

    $('#editPoint').click(function(){
        ClearInteractionPoints();
        $(this).addClass('activeOptions');
        map.addInteraction(selectPoint);

        selectPoint.getFeatures().on('add', function(e) {
            var features = e.element;
            $('.inserirDado').fadeOut();
            $('.editDado').fadeIn();
            $('.delDado').fadeOut();
            sourcePoint.clear();
            $('.editDado input[name="geom"').val('');

            $(".editDado input").each(function(){
                var colunmsName = $(this).attr('name');
                if(colunmsName != 'callback' && colunmsName != 'callback_action' && colunmsName != 'responsavel' && colunmsName != 'geom' && colunmsName != 'map'){
                    $('.editDado input[name="'+colunmsName+'"').val(features.get(colunmsName));
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

     });

    $('#erasePoint').click(function(){
        ClearInteractionPoints();
        $(this).addClass('activeOptions');
        map.addInteraction(erasePoint);

        erasePoint.getFeatures().on('change:length', function(e) {
        if(e.target.getArray().length !== 0){
            erasePoint.getFeatures().on('add', function(f) {
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

    function ClearInteractionPoints(){
        $("#pointsOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(drawPoints);
        map.removeInteraction(erasePoint);
        map.removeInteraction(selectPoint);
    }

    function generationCoordsPoint(f){
        var coords = ol.proj.transform(f.getGeometry().getCoordinates(), 'EPSG:3857', 'EPSG:4326');
        longitude.val(coords[0]);
        latitude.val(coords[1]);

        wktpoint.val('POINT(' + coords[0] + ' ' + coords[1] + ')');
        return false;
    }