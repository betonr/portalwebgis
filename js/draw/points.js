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
    var latitude = $("[name='latitude']");
    var longitude = $("[name='longitude']");

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

            //vkt.val('POINT(' + long + ' ' + lat + ')');
            map.getView().fitExtent(sourcePoint.getExtent(), map.getSize());
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
        });

        return false;
    });

    $('#erasePoint').click(function(){
        ClearInteractionPoints();
        $(this).addClass('activeOptions');
        sourcePoint.clear();
        latitude.val('');
        longitude.val('');

        return false;
    });

    function ClearInteractionPoints(){
        $("#pointsOptions").find("p").removeClass('activeOptions');
        map.removeInteraction(drawPoints);
    }

    function generationCoordsPoint(f){
        var coords = ol.proj.transform(f.getGeometry().getCoordinates(), 'EPSG:3857', 'EPSG:4326');
        longitude.val(coords[0]);
        latitude.val(coords[1]);

        return false;
    }