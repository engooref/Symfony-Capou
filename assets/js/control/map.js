import $ from "jquery";
import {Map, View} from 'ol';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import {fromLonLat} from 'ol/proj';
import Point from 'ol/geom/Point';
import Feature from 'ol/Feature';

import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
    	
    actMaps();
    var intervalId = setInterval(actMaps,9000);    	
 	function actMaps(){
		$.get({
			url  : '/mapsControl',
   			dataType : 'json',
   			success : SuccessMaps
        });
	};	

	function SuccessMaps(data){ 
		if(map.getLayers().getLength() >= 2){
			for(let i = 0; i+1 < map.getLayers().getLength() ; i++)
				map.removeLayer(map.getLayers().item(i+1));
			}
		
		
		AddToMap(data, 0);
		
	}

    	function AddToMap(data, type){
    		var dataSelect = data[type.toString()];
    		for(let i = 0; i < dataSelect.length; i++){
        		var gps = dataSelect[i]["gps"];
				var layer = new VectorLayer({
					id: dataSelect[i]["id"],
					type: type,
					gps:  "lat: " + gps["latitude"] + " long: " + gps["longitude"],
					source: new VectorSource({
	          			features: [
	        				new Feature({
	              				geometry: new Point(fromLonLat([gps["longitude"], gps["latitude"]]))
	            			}),
	          			]
	        		})
	        	});
	        	map.addLayer(layer);
			}
        }
		
        var map = new Map({
            layers: [
              new TileLayer({
                source: new OSM({ maxZoom: 19 })
             })
            ],
            target: 
            	'map',
        		view: new View({
        			center: 
        				fromLonLat([1.31, 44.05]),
        				maxZoom: 19,
        				zoom: 17
        		})
        });

        map.on('singleclick', function (event) {
            if (map.hasFeatureAtPixel(event.pixel) === true) {

            	var id = map.forEachLayerAtPixel(event.pixel, function (layer) {
				    										return layer;}).get('id');
			    var type = map.forEachLayerAtPixel(event.pixel, function (layer) {
			    											return layer;}).get('type');
			    var gps = map.forEachLayerAtPixel(event.pixel, function (layer) {
															return layer;}).get('gps');
				
			    if(type == 0) var nameType = "Piquet";
				else if(type == 1) var nameType = "Station";
				else if(type == 2) var nameType = "Electrovanne";
					
				$("#id").text(id);
				$("#gps").text(gps);
				var data = "id=" + id + "&type=" + type;
				
                $.get({
        			url  : '/getData',
           			dataType : 'json',
           			data: data,
           			success : AcquireData
                });

                
          	} 
       });
       
        $('#returnButton').click(function(event)
        {
       		$(".left").show();
            $(".left").animate({width:"+=100%"},{queue:false, duration:800 });
            $(".right").animate({width:"toggle"},{queue:false, duration:700 });
        });	

        function AcquireData(data){
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
