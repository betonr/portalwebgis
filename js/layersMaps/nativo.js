    var geojsonObject = $('#jsonMap').text();
    var vectorSourceAtual = new ol.source.Vector({
            features: (new ol.format.GeoJSON()).readFeatures(geojsonObject)
    });

    //layers do geoserver
    var bases = new ol.layer.Group({
        layers: [
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:municipios', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: true,
                name: 'municipios'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:distritos', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: true,
                name: 'distritos'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:1930', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: '1'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:1920', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: '2'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:1910', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: '3'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:1900', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: '4'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:1890', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: '5'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:1880', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: '6'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb:1868', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: '7'
            }),
            new ol.layer.Tile({
                source: new ol.source.TileWMS({
                    url: 'http://localhost:8080/geoserver/ows',
                    params: {'LAYERS': 'portalweb: none', 'TILED': true},
                    serverType: 'geoserver'
                }),
                visible: false,
                name: 'postgis'
            }),
            new ol.layer.Vector({
                source: vectorSourceAtual,
                visible: true,
                name: 'mapAtual'
            })
        ],
        visible: true,
        name: 'bases'
    });
