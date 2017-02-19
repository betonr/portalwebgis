//layers de api's (online)
var openstreetmap = new ol.layer.Tile({
    source: new ol.source.OSM(),
    visible: true,
    name: 'openstreetmap'
});
var esri = new ol.layer.Tile({
    source: new ol.source.XYZ({
                attributions: [
                        new ol.Attribution({
                            html: 'Tiles &copy; <a href="http://services.arcgisonline.com/ArcGIS/' +
                            'rest/services/World_Topo_Map/MapServer">ArcGIS</a>'
                        })
                ],
                url: 'http://server.arcgisonline.com/ArcGIS/rest/services/' +
                'World_Topo_Map/MapServer/tile/{z}/{y}/{x}'
            }),
    visible: false,
    name: 'esri'
});
var bingRoad = new ol.layer.Tile({
    preload: Infinity,
            source: new ol.source.BingMaps({
                    key: 'AqwD3uSJMGzPQGNGWetSkrdq3kTgIDODq_v-_72D7sQ0gWjkzTIVqzwQR3xqeaGo',
                    imagerySet: 'Road'
            }),
    visible: false,
    name: 'bingRoad'
});
var stamen = new ol.layer.Group({
    layers: [
        new ol.layer.Tile({
            source: new ol.source.Stamen({
                layer: 'watercolor'
            })
        }),
        new ol.layer.Tile({
            source: new ol.source.Stamen({
                layer: 'terrain-labels'
            })
        })
    ],
    visible: false,
    name: 'stamen'
});