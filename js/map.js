	view = new ol.View({
		center: [-5432905.961580031, -2530559.233689207],
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
			}),
			new ol.layer.Tile({
				source: new ol.source.TileWMS({
					url: 'http://localhost:8080/geoserver/ows',
					params: {'LAYERS': 'pauliceia: none', 'TILED': true},
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

	// ===========================
	//configurações de mostragem do mapa
	var map = new ol.Map({
		target: 'mapafixo',
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
	$('.top input[type=radio]').change(function() {
		var layer = $(this).val();

		map.getLayers().getArray().forEach(function(e) {
			var name = e.get('name');
			if(name == layer){
				if(!e.get('visible')){
					e.setVisible(true);
				}
			}else if(name != 'bases' && name != 'lines' && name != 'Poligon' && name != 'points'){
				e.setVisible(false);
			}
		});
	});
	//ações de mostragem dos mapas do geoserver
	$('.top input[type=checkbox]').click(function() {
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

	//ações de mostragem dos mapas do geoserver
	$('.top .selectMap').click(function() {
		var postgisSelect = $("select[name='dbpostgis']").val();
		var postgislayer = 'pauliceia:'+postgisSelect+'';

		if (bases instanceof ol.layer.Group){
			bases.getLayers().forEach(function(sublayer){
				if (sublayer.get('name') == 'postgis') {
					if(postgisSelect == 'none'){
						sublayer.setVisible(false);
					}else{
						sublayer.getSource().updateParams({'LAYERS': postgislayer, 'TILED': true});
						sublayer.setVisible(true);
					}
				}
			});
		}

	});


