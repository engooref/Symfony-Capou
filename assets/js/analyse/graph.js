import $ from "jquery";
var Chart = require('chart.js');

const delay = 10000;

var id = [];
var heure = [];
var temp = [];
var humi = [];
var humi1 = [];
var humi2 = [];
var humi3 = [];
var humi4 = [];

const ctx1 = document.getElementById('chartContainerTemp').getContext('2d');
const ctx2 = document.getElementById('chartContainerHumi').getContext('2d');

var chartContainerTemp = new Chart(ctx1, {
	type: 'line',
  	data: {
    	labels: heure,
    	datasets: [
				{
					label: 'Temperature Intérieure',
					data: temp,
					fill: false,
					borderColor: 'blue',
					borderWidth: 1
				}/*,
				{
	                label: 'Temperature Extérieure',
                	data: globalData.Temp2,
                	fill: false,
                	borderColor: 'red',
                	borderWidth: 1
      			}*/
		]
	},
	options: {
		responsive : true,
		scales: {
			x: {
				grid: {
					display: true,
					drawBorder: true,
					drawOnChartArea: true,
					drawTicks: true
				}
			},
			y: {
				grid: {
					display: true,
					drawBorder : true,
					drawOnChartArea: true,
					drawTicks: true,
					color: function(context){
						if((context.tick.value < 0)||(context.tick.value > 0)) {return '#6c757d';}
						return '#FFC501';
					}
				}
			}
		}
	}
});

var chartContainerHumi = new Chart(ctx2, {
	type: 'line',
  	data: {
    	labels: heure,
    	datasets: [
			{
				label: 'Humidité 1',
				data: humi1,
				fill: false,
				borderColor: 'blue',
				borderWidth: 1
			},
			{
				label: 'Humidité 2',
				data: humi2,
				fill: false,
				borderColor: 'red',
				borderWidth: 1
			},
			{
				label: 'Humidité 3',
				data: humi3,
				fill: false,
				borderColor: 'green',
				borderWidth: 1
			},
			{
				label: 'Humidité 4',
				data: humi4,
				fill: false,
				borderColor: 'purple',
				borderWidth: 1
			}
    	]
	},
  	options: {}
});

window.addEventListener('load', getData);

function Setup() {

	console.log("lancement du setup");
		
	for(var k=0; k<id.length; k++){
		console.log("n°k = ",k,"id[k] = ",id[k] );
	}
	for(var k=0; k<heure.length; k++){
		console.log("n°k = ",k,"heure[k] = ",heure[k] );
	}
	for(var k=0; k<temp.length; k++){
		console.log("n°k = ",k,"temp[k] = ",temp[k] );
	}
	for(var k=0; k<humi.length; k++){
		console.log("n°k = ",k,"humi[k] = ",humi[k] );
	}
	
	chartContainerTemp.data.labels = heure;
	chartContainerTemp.data.datasets[0].data = temp;
	chartContainerTemp.update();
	
	chartContainerHumi.data.labels = heure;
	chartContainerHumi.data.datasets[0].data = humi1;
	chartContainerHumi.data.datasets[1].data = humi2;
	chartContainerHumi.data.datasets[2].data = humi3;
	chartContainerHumi.data.datasets[3].data = humi4;
	chartContainerHumi.update();
	
	setTimeout(getData,delay);
}

function getData() {
	console.log("recuperation des donnees piquet");
	
	$.ajax('/getDataPiquet', {
		method: "POST",
		dataType:'json',
		success: function (response) {
			console.log("ID = ", response["Id"], "HEURE = ", response["Heure"], "TEMP = ", response["Temp"]);
			
			id = response["Id"];
			heure = response["Heure"];
			temp = response["Temp"];
			humi = response["Humi"];
			
			for(var k=0; k<humi.length;k++){
				var humik = [];
				humik = humi[k];
				
				humi1.push(humik[0]);
				humi2.push(humik[1]);
				humi3.push(humik[2]);
				humi4.push(humik[3]);
			}
			
			console.log("id = ",id,"heure = ", heure,"temp = ", temp,"humi = ",humi);
			console.log("humi1 = ",humi1,"humi1 = ",humi1,"humi2 = ",humi2,"humi3 = ",humi3,"humi4 = ",humi4)
			
			Setup();
		},
		error: function (response) {
			$('#res').html("error ", response);
		}
 	});
}

