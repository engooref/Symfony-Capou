import $ from "jquery";
import {Map, View} from 'ol';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import {fromLonLat} from 'ol/proj';
import Point from 'ol/geom/Point';
import Circle from 'ol/geom/Circle';
import Style from 'ol/style/Style';
import Fill from 'ol/style/Fill';
import Stroke from 'ol/style/Stroke';
import Icon from 'ol/style/Icon';
import RegularShape from 'ol/style/RegularShape';
import Feature from 'ol/Feature';

import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
    	
actMaps();
var GradientMidPourcent = 50;
var humiditeChx = 0;
var dataMap = []

var slider = document.getElementById("sliderHum");
var output = document.getElementById("moyenneHum");
output.innerHTML = slider.value;

slider.oninput = function() {
  	output.innerHTML = this.value;
	GradientMidPourcent = this.value;
	SuccessMaps(dataMap);
}

var intervalId = setInterval(actMaps,5000); 
var circleTab = [];  
var stroke = new Stroke({
	color: 'black',
	width: 2
}); 
/*var electrovanneFill = new Fill({
	color: 'blue'
});*/
var piquetFill = new Fill({
	color: 'green'
});
var armoireFill = new Fill({
	color: 'red'
});
var vanneStyle = new Style({
	image: new Icon({
		src: 'img/vanne_logo.png',
		scale: 0.05,
	}),
});
var marqueur;
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
	dataMap = data;
	if(map.getLayers().getLength() >= 2) {
		for(let i = 1; map.getLayers().getLength()!=1 ; i){
			var layerItem = map.getLayers().item(i);
			layerItem.getSource().getFeatures().forEach(function(feature){ 
				layerItem.getSource().removeFeature(feature)
			}); 
			map.removeLayer(map.getLayers().item(i));
		}
	}
	// Electrovanne -> 1
	//AddToMap(data, 1);
	// Piquet -> 2
	AddToMap(data, 2, humiditeChx);
}

function positivSoustraction(a,b){
	if(a<b){
		return 0
	}else{
		return a-b
	}
}

function getColor(p){
	var x1 = Math.round(p/100);
	var red = (1 - x1) * 255 + x1 * (255 - positivSoustraction(p,GradientMidPourcent)*(255/GradientMidPourcent));
	var green = x1 * 255 + ( 1 - x1 ) * (positivSoustraction(GradientMidPourcent,p) * (255/GradientMidPourcent));
	//return 'rgba(255,0,0,'
	return 'rgba(' + red + ',' + green + ',0,'
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

				var colors = getColor(percent); // couleur du piquet
				
		      gradient.addColorStop(0, colors + '0)');
		      gradient.addColorStop(0.6, colors + '0.2)');
		      gradient.addColorStop(1, colors + '0.8)');
		      ctx.beginPath();
		      ctx.arc(x, y, radius, 0, 2 * Math.PI, true);
		      ctx.fillStyle = gradient;
		      ctx.fill();
		
		      ctx.arc(x, y, radius, 0, 2 * Math.PI, true);
		      ctx.strokeStyle = colors+'1)';
		      ctx.stroke();
		  	},
		})
	)
}
	
function AddToMap(data, type, choixHum) {
	var dataSelect = data[type.toString()];
	for(let i = 0; i < dataSelect.length; i++){
		var gps = dataSelect[i]["gps"];
		if(type == 1) { 		// Electrovanne 
			marqueur = new Feature({
				geometry: new Point(fromLonLat([gps["longitude"], gps["latitude"]]))
			});
			marqueur.setStyle(vanneStyle);
			circleTab[i] = new Feature(null);
		} else if (type == 2) {	// Piquet
			var humidite = dataSelect[i]["humidite"]; 
			var piquet = new Circle(fromLonLat([gps["longitude"], gps["latitude"]]), 8);
			var piquetStyle = new Style({
				geometry: piquet,
				fill: piquetFill,
				stroke:	stroke,
			});
			marqueur = new Feature(piquet);
			marqueur.setStyle(piquetStyle);	
			circleTab[i] = new Feature({
				geometry: new Circle(fromLonLat([gps["longitude"], gps["latitude"]]),40)
			});
			/*console.log(humidite);*/
			addCircle(humidite[choixHum], circleTab[i]);
		} else if(type == 3) {	// Armoire 
			var armoire = new Point(fromLonLat([gps["longitude"], gps["latitude"]]));
			var armoireStyle = new Style ({
				image: new RegularShape({
					fill: armoireFill, 
					stroke: stroke,
					points: 4,
					radius: 8,
					angle: Math.PI / 4,
				}),
			})
			marqueur = new Feature(armoire);
			marqueur.setStyle(armoireStyle);
		} else {
			
		}
		var layer = new VectorLayer({
				id: dataSelect[i]["id"],	
				type: type,
				gps:  "lat: " + gps["latitude"] + " long: " + gps["longitude"],
				source: new VectorSource({
          			features: [
						marqueur,
						circleTab[i]
					]
    			})
    		});
    	map.addLayer(layer);
	}
}


window.humidite = function humidite(niveauHum){
	humiditeChx = niveauHum;
	SuccessMaps(dataMap);
	//AddToMap(data, 2, niveauHum);
}

map.on('singleclick', function (event) {
	map.forEachFeatureAtPixel(event.pixel,
		function (feature){
			if(feature){
				var layer = GetLayerEvent(feature)
				if( !layer){
					return;
				}
				var id = layer.get("id");
				var type = layer.get("type")
				var gps = layer.get("gps")
				
				if(type == 1) var nameType = "Electrovanne";
				else if(type == 2) var nameType = "Piquet";
				
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
		}
	);
});

function GetLayerEvent(feature){
	var layerMap = map.getLayers();
	for(let i=1 ; i < layerMap.getLength(); i++){
		var featureLayer = layerMap.item(i).getSource().getFeatures()[0];
		if(feature === featureLayer){return layerMap.item(i)}
	}
	return null;
}

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
