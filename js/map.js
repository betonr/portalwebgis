	view = new ol.View({
		center: [-5233558.191812292, -2632067.6072519226],
		zoom: 7,
		maxZoom: 22,
		minZoom: 2
	});

	// =============================
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

	//layers do geoserver
	var bases = new ol.layer.Group({
		layers: [
			new ol.layer.Tile({
				source: new ol.source.TileWMS({
					url: 'http://localhost:8080/geoserver/ows',
					params: {'LAYERS': 'pauliceia:municipios', 'TILED': true},
					serverType: 'geoserver'
				}),
				visible: true,
				name: 'municipios'
			}),
			new ol.layer.Tile({
				source: new ol.source.TileWMS({
					url: 'http://localhost:8080/geoserver/ows',
					params: {'LAYERS': 'pauliceia:distritos', 'TILED': true},
					serverType: 'geoserver'
				}),
				visible: true,
				name: 'distritos'
			})
		],
		visible: true,
		name: 'bases'
	});

	// ===========================
	//configurações de mostragem do mapa
	var map = new ol.Map({
		target: 'mapa',
		controls: ol.control.defaults().extend([
			new ol.control.ScaleLine(),
			new ol.control.ZoomSlider()
		]),
		renderer: 'canvas',
		layers: [openstreetmap, esri, stamen, bases],
		view: view
	});

	// ===========================
	//ações de mostragem dos mapas fixos
	$('#layers input[type=radio]').change(function() {
		var layer = $(this).val();

		map.getLayers().getArray().forEach(function(e) {
			var name = e.get('name');
			if(name == layer){
				if(!e.get('visible')){
					e.setVisible(true);
				}
			}else if(name != 'bases'){
				e.setVisible(false);
			}
		});
	});
	//ações de mostragem dos mapas do geoserver
	$('#layers input[type=checkbox]').click(function() {
		var layerselect = $(this).val();

		if (bases instanceof ol.layer.Group){
			bases.getLayers().forEach(function(sublayer){
				if (sublayer.get('name') == layerselect) {
					if(!sublayer.get('visible')){
						sublayer.setVisible(true);
					}else{
						sublayer.setVisible(false);
					}
				}
			});
		}

	});


