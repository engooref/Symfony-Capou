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
        		var gps = dataSelect[i]["gps"].split(':');
				var layer = new VectorLayer({
					id: dataSelect[i]["id"],
					type: type,
					gps:  "lat: " + gps[0] + " long: " + gps[1],
					source: new VectorSource({
	          			features: [
	        				new Feature({
	              				geometry: new Point(fromLonLat([gps[1], gps[0]]))
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
					
				$("#id").text(id.toString(16).toUpperCase() + " : " + nameType);
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
			graph(data);            	
        }

        function graph(data){
            
        	const margin = {top: 20, right: 30, bottom: 30, left: 60},
            width = document.getElementById('right').offsetWidth ;
            console.log(width);
            
            //* 0.95 - margin.left - margin.right,
            const height = 400 - margin.top - margin.bottom;

        	//const parseTime = d3.timeParse("%Y-%m-%d %H:%M:%S");
        	//const dateFormat = d3.timeFormat("%Y-%m-%d %H:%M:%S");
        	const parseTime = d3.timeParse("%d/%m/%Y");
        	const dateFormat = d3.timeFormat("%d/%m/%Y");
        	
        	const x = d3.scaleTime()
            .range([0, width]);

        	const y = d3.scaleLinear()
            .range([height, 0]);

        	const line = d3.line()
            .x(d => x(d.date))
            .y(d => y(d.close));;

            const svg = d3.select("#chart").append("svg")
            .attr("id", "svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            var map = {};
            d3.tsv("/data.tsv").then(function(data) {
                console.log(data);
                // Conversion des données du fichier, parsing des dates et '+' pour expliciter une valeur numérique.
                data.forEach(function(d) {
                    d.date = parseTime(d.date);
                    d.close = +d.close;
                    d.volume = +d.volume;
                    map[d.date] = d; // sauvegarde sous forme de hashmap de nos données.
                });
                console.log(map);
                // Contrairement au tutoriel Bar Chart, plutôt que de prendre un range entre 0 et le max on demande 
                // directement à D3JS de nous donner le min et le max avec la fonction 'd3.extent', pour la date comme 
                // pour le cours de fermeture (close).
                x.domain(d3.extent(data, d => d.date));
                y.domain(d3.extent(data, d => d.close));

                // Ajout de l'axe X
                svg.append("g")
                    .attr("transform", "translate(0," + height + ")")
                    .call(d3.axisBottom(x));
                
                // Ajout de l'axe Y et du texte associé pour la légende
                svg.append("g")
                    .call(d3.axisLeft(y))
                    .append("text")
                        .attr("fill", "#000")
                        .attr("transform", "rotate(-90)")
                        .attr("y", 6)
                        .attr("dy", "0.71em")
                        .style("text-anchor", "end")
                        .text("Pts");
                
                // Ajout de la grille horizontale (pour l'axe Y donc). Pour chaque tiret (ticks), on ajoute une ligne qui va 
                // de la gauche à la droite du graphique et qui se situe à la bonne hauteur.
                svg.selectAll("y axis").data(y.ticks(10)).enter()
                    .append("line")
                    .attr("class", "horizontalGrid")
                    .attr("x1", 0)
                    .attr("x2", width)
                    .attr("y1", d => y(d))
                    .attr("y2", d => y(d));
                
                // Ajout d'un path calculé par la fonction line à partir des données de notre fichier.
                svg.append("path")
                    .datum(data)
                    .attr("class", "line")
                    .attr("d", line);
            });            
        }