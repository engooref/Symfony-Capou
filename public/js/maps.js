var tabX = []	
        var layer = null;
        act();
        var intervalId = setInterval(act,10000);
		
	  	
	 	function act(){
			$.get({
				url  : '{{ path('extract') }}',
       			dataType : 'json',
       			success : 
				    layer = new ol.layer.Vector({
                   		source: new ol.source.Vector({
                  			features: [
                				new ol.Feature({
                      				geometry: new ol.geom.MultiPoint(tabX)
                    			}),
                  			]
                		})
                	});
                	map.addLayer(layer);     			
       			}	
	        });
		}	
		    	
		function SuccessAjax(data){ 
       				if(!layer){layer.getSource().clear();}
       				tabX.splice(0, tabX.length);
       				
   					for(let i = 0; i < data.length; i++){
   						var long = data[i]['longitude'];
   						var lat = data[i]['latitude']
   						tabX.push(ol.proj.fromLonLat([long, lat]));
   					}	
        var map = new ol.Map({
            layers: [
              new ol.layer.Tile({
                source: new ol.source.OSM({ maxZoom: 19 })
             })
            ],
            target: 
            	'map',
        		view: new ol.View({
        			center: 
        				ol.proj.fromLonLat([1.3149833, 44.0315281]),
        				maxZoom: 19,
        				zoom: 17
        		})
        });
        
        
        map.addLayer(layer);
        var container = document.getElementById('popup');
        var content = document.getElementById('popup-content');
        var closer = document.getElementById('popup-closer');
        
        /*var overlay = new ol.Overlay({
        	element: container,
            autoPan: true,
            autoPanAnimation: {
              duration: 250
            }
        });*/
        
        //map.addOverlay(overlay);
        
        /*closer.onclick = function() {
        	overlay.setPosition(undefined);
            closer.blur();
            return false;
        };*/
          
        map.on('singleclick', function (event) {
            if (map.hasFeatureAtPixel(event.pixel) === true) {
                $(".left").animate({width:"toggle"},{queue:false, duration:700 });
                $(".right").show();
                $(".right").animate({width:"+=50%"},{queue:false, duration:800 });   
          	} 
       });
       
        $('#returnButton').click(function(event)
        {
       		$(".left").show();
            $(".left").animate({width:"+=100%"},{queue:false, duration:800 });
            $(".right").animate({width:"toggle"},{queue:false, duration:700 });
            
        });	
	