import $ from "jquery";
import {Map, View} from 'ol';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import {fromLonLat} from 'ol/proj';
import Point from 'ol/geom/Point';
import Circle from 'ol/geom/Circle';
import Style from 'ol/style/Style';
import Feature from 'ol/Feature';

import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
    	
actMaps();
var intervalId = setInterval(actMaps,9000); 
var circleTab = [];  
var map = new Map({
	layers: [ 
		new TileLayer({
    		source: new OSM({ 
    			maxZoom: 19
    		})

		})
	],
    target: 
    	'map',
		view: new View({
			center: 
				fromLonLat([1.3132516617440526, 44.03227977777603]),
				maxZoom: 19,
				zoom: 15
		})
});
 	
function actMaps() {
	$.get({
		url  : '/mapsControl',
		dataType : 'json',
		success : SuccessMaps
    });
};	

function SuccessMaps(data) { 
	if(map.getLayers().getLength() >= 2) {
		for(let i = 0; i+1 < map.getLayers().getLength() ; i++)
			map.removeLayer(map.getLayers().item(i+1));
		}
	// Electrovanne -> 1
	//AddToMap(data, 1);
	// Piquet -> 2
	AddToMap(data, 2);	
}

function addCircle(percent, circle) {
	circle.setStyle(
		new Style({
		    renderer: function renderer(coordinates, state) {
		      var coordinates_0 = coordinates[0];
		      var x = coordinates_0[0];
		      var y = coordinates_0[1];
		      var coordinates_1 = coordinates[1];
		      var x1 = coordinates_1[0];
		      var y1 = coordinates_1[1];
		      var ctx = state.context;
		      var dx = x1 - x;
		      var dy = y1 - y;
		      var radius = Math.sqrt(dx * dx + dy * dy);
		
		      var innerRadius = 0;
		      var outerRadius = radius * 1.4;
		
		      var gradient = ctx.createRadialGradient(
		        x,
		        y,
		        innerRadius,
		        x,
		        y,
		        outerRadius
		      );
		      gradient.addColorStop(0, 'rgba(255,0,0,0)');
		      gradient.addColorStop(0.6, 'rgba(255,0,0,0.2)');
		      gradient.addColorStop(1, 'rgba(255,0,0,0.8)');
		      ctx.beginPath();
		      ctx.arc(x, y, radius, 0, 2 * Math.PI, true);
		      ctx.fillStyle = gradient;
		      ctx.fill();
		
		      ctx.arc(x, y, radius, 0, 2 * Math.PI, true);
		      ctx.strokeStyle = 'rgba(255,0,0,1)';
		      ctx.stroke();
		  	},
		})
	)
}
	
function AddToMap(data, type) {
	var dataSelect = data[type.toString()];
	for(let i = 0; i < dataSelect.length; i++){
		var gps = dataSelect[i]["gps"];
		circleTab[i] = new Feature({
			geometry: new Circle(fromLonLat([gps["longitude"], gps["latitude"]]),40)
		});
		addCircle(Math.floor(Math.random()*100),circleTab[i]);
		var layer = new VectorLayer({
				id: dataSelect[i]["id"],
				type: type,
				gps:  "lat: " + gps["latitude"] + " long: " + gps["longitude"],
				source: new VectorSource({
          			features: [
        				new Feature({
              				geometry: new Point(fromLonLat([gps["longitude"], gps["latitude"]]))
            			}),
						circleTab[i]
					]
    			})
    	});
    	map.addLayer(layer);
	}
}

map.on('singleclick', function (event) {
    if (map.hasFeatureAtPixel(event.pixel) === true) {

    	var id = map.forEachLayerAtPixel(event.pixel, function (layer) {
		    										return layer;}).get('id');
	    var type = map.forEachLayerAtPixel(event.pixel, function (layer) {
	    											return layer;}).get('type');
	    var gps = map.forEachLayerAtPixel(event.pixel, function (layer) {
													return layer;}).get('gps');
		// 1 -> Electrovanne | 2 -> Piquet | 3 -> Armoire 
	    if(type == 1) var nameType = "Electrovanne";
		else if(type == 2) var nameType = "Piquet";
		//else if(type == 3) var nameType = "Armoire";
			
		$("#id").text(id);
		$("#gps").text(gps);
		var data = "type=" + type + "&id=" + id;
		
        $.get({
			url  : '/getData',
   			dataType : 'json',
   			data: data,
   			success : AcquireData
        });    
	} 
});

function AcquireData(data) {
	var object = data["Object"];
	var dataObj = data["Data"];
	var dataEnd = dataObj[dataObj.length - 1];

	$("#bat").text(dataEnd["batterie"] + "%");
	$("#dataEnd").text(dataEnd["horodatage"]);
	var hum = "";
	for(let i = 0; i < dataEnd["humidite"].length; i++){
		hum = hum + dataEnd["humidite"][i] + "%, ";
	}
	$("#hum").text(hum);
	$("#temp").text(dataEnd["temperature"] + "°C");

	$(".left").animate({width:"toggle"},{queue:false, duration:700 });
    $(".right").show();
    $(".right").animate({width:"+=50%"},{queue:false, duration:800 });        	
}

$('#returnButton').click(function(event) {
	$(".left").show();
    $(".left").animate({width:"+=100%"},{queue:false, duration:800 });
    $(".right").animate({width:"toggle"},{queue:false, duration:700 });
});	
