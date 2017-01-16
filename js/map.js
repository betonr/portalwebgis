	if($.cookie("saveViewCenter") && $.cookie("saveViewCenter") != ''){
		var long = parseFloat($.cookie("saveViewCenter"));
		var lat = $.cookie("saveViewCenter").lastIndexOf(",");
		lat = $.cookie("saveViewCenter").substr(lat+1);
		lat = parseFloat(lat);

		var centerMap = [long, lat];
		var zoomMap = $.cookie("saveViewZoom");
	}else{
		var centerMap = [-5432905.961580031, -2530559.233689207];
		var zoomMap = '7';
	}
	view = new ol.View({
		center: centerMap,
		zoom: zoomMap,
		maxZoom: 20,
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
		layers: [openstreetmap, esri, stamen, bingRoad, bases],
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
		$('.titleMapStyle').attr('title', postgisSelect);
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

	//modificação de Estilos dos mapas
	$('.actEditStyleC').click(function() {
		var mapEdit = $('.complex input[name="map"]').val();
		var idEdit = $('.complex input[name="id"]').val();
		var backEdit = $('.complex input[name="background"]').val();
		var alphaEdit = $('.complex input[name="alpha"]').val();
		var strokeEdit = $('.complex input[name="stroke"]').val();
		var styleEdit = new ol.style.Style
		({
			image: new ol.style.Circle({
				radius: 5,
				fill: new ol.style.Fill({ color: backEdit }),
				stroke: new ol.style.Stroke({ color: strokeEdit })
			}),
			fill: new ol.style.Fill({ color: backEdit }),
			stroke: new ol.style.Stroke({ color: strokeEdit })
		});


		if(idEdit == 'Lmapa'){
			map.getLayers().getArray().forEach(function(e) {
				if(e.get('name') == mapEdit){
					e.setOpacity(parseFloat(alphaEdit));
				}
			});

		}else if(idEdit == 'Lbases'){
			if (bases instanceof ol.layer.Group){
				bases.getLayers().forEach(function(sublayer){
					if (sublayer.get('name') == mapEdit) {
						sublayer.setOpacity(parseFloat(alphaEdit));
						sublayer.setStyle(styleEdit);
					}
				});
			}
		}
	});
	$('.actEditStyle').click(function() {
		var mapEdit = $('.simple input[name="map"]').val();
		var idEdit = $('.simple input[name="id"]').val();
		var alphaEdit = $('.simple input[name="alpha"]').val();
		if(idEdit == 'Lmapa'){
			map.getLayers().getArray().forEach(function(e) {
				if(e.get('name') == mapEdit){
					e.setOpacity(parseFloat(alphaEdit));
				}
			});
		}else if(idEdit == 'Lbases'){
			if (bases instanceof ol.layer.Group){
				bases.getLayers().forEach(function(sublayer){
					if (sublayer.get('name') == mapEdit) {
						sublayer.setOpacity(parseFloat(alphaEdit));
						sublayer.setStyle(styleEdit);
					}
				});
			}
		}
	});
