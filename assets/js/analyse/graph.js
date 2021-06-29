import $ from "jquery";
var Chart = require('chart.js');

const delay = 10000;

var periodeGlo = '1sem';

var HeureSplit = [];

var id = [];
var heure = [];
var temp = [];
var humi = [];
var humiAir;
var humiSol1;
var humiSol2;
var humiSol3;

const ctx1 = document.getElementById('chartContainerTemp').getContext('2d');
const ctx2 = document.getElementById('chartContainerHumiAir').getContext('2d');
const ctx3 = document.getElementById('chartContainerHumiSol').getContext('2d');

var chartContainerTemp = new Chart(ctx1, {
	type: 'line',
  	data: {
    	labels: HeureSplit,
    	datasets: [
				{
					label: 'Temperature',
					data: temp,
					fill: false,
					borderColor: 'blue',
					borderWidth: 1
				}
		]
	},
	options: {
		responsive:true,
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
					drawOnChartArea: true,
					drawTicks: true,
					color: function(context){
						if((context.tick.value < 0)||(context.tick.value > 0)) {return '#DCDCDC';}
						return '#FFC501';
					},
					lineWidth:function(context){
						if((context.tick.value < 0)||(context.tick.value > 0)) {return 1;}
						return 2;
					}
				},
			}
		}
	}
});

var chartContainerHumiAir = new Chart(ctx2, {
	type: 'line',
  	data: {
    	labels: HeureSplit,
    	datasets: [
			{
				label: 'Humidité air',
				data: humiAir,
				fill: false,
				borderColor: 'blue',
				borderWidth: 1
			}
    	]
	},
  	options: {
		scales: {
			y: {
				suggestedMin:0,
				suggestedMax:100
			}
		}
	}
});

var chartContainerHumiSol = new Chart(ctx3, {
	type: 'line',
  	data: {
    	labels: HeureSplit,
    	datasets: [
			{
				label: 'Humidité sol 1',
				data: humiSol1,
				fill: false,
				borderColor: 'blue',
				borderWidth: 1
			},
			{
				label: 'Humidité sol 2',
				data: humiSol2,
				fill: false,
				borderColor: 'red',
				borderWidth: 1
			},
			{
				label: 'Humidité sol 3',
				data: humiSol3,
				fill: false,
				borderColor: 'green',
				borderWidth: 1
			}
    	]
	},
  	options: {
		scales: {
			y: {
				suggestedMin:0,
				suggestedMax:100
			}
		}
	}
});

getData();
setInterval(getData,delay);

function Update() {
	
	console.log("Mise à jour des données");
		
	for(var k=0; k<id.length; k++){
		console.log("id[",k,"] = ",id[k] );
	}
	for(var k=0; k<heure.length; k++){
		console.log("heure[",k,"] = ",heure[k] );
	}
	for(var k=0; k<temp.length; k++){
		console.log("temp[",k,"] = ",temp[k] );
	}
	for(var k=0; k<humi.length; k++){
		console.log("humi[",k,"] = ",humi[k] );
	}

	chartContainerTemp.data.labels = HeureSplit;
	chartContainerTemp.data.datasets[0].data = temp;
	chartContainerTemp.update();
	
	chartContainerHumiAir.data.labels = HeureSplit;
	chartContainerHumiAir.data.datasets[0].data = humiAir;
	chartContainerHumiAir.update();
	
	chartContainerHumiSol.data.labels = HeureSplit;
	chartContainerHumiSol.data.datasets[0].data = humiSol1;
	chartContainerHumiSol.data.datasets[1].data = humiSol2;
	chartContainerHumiSol.data.datasets[2].data = humiSol3;
	chartContainerHumiSol.update();
}


window.setPeriode = function setPeriode(periode){
	console.log("------------ Période active ------------");
	periodeGlo = periode;
	getData();
}

function getData() {

	console.log("recuperation des donnees piquet");
	
	$.ajax('/getDataPiquet', {
		method: "POST",
		dataType:'json',
		data: 'periode=' + periodeGlo,
		success: function (response) {
			id = response["Id"];
			heure = response["Heure"];
			temp = response["Temp"];
			humi = response["Humi"];
			
			humiAir=[];
			humiSol1=[];
			humiSol2=[];
			humiSol3=[];

			HeureSplit = [];
			
			if (periodeGlo == '24h'){
				for(var m=0; m<heure.length; m++){
					HeureSplit[m] = heure[m].split(' ').slice(1);
				}
			} else {
				for(var m=0; m<heure.length; m++){
				HeureSplit[m] = heure[m].split(' ')[0];
				}
			}

			
			for(var k=0; k<humi.length;k++){
				var humik = [];
				humik = humi[k];	
				
				humiAir.push(humik[0]);
				humiSol1.push(humik[1]);
				humiSol2.push(humik[2]);
				humiSol3.push(humik[3]);
			}
			
			console.log("id = ",id,"heure = ", HeureSplit,"temp = ", temp,"humi = ",humi);
			
			Update();
		},
		error: function (response) {
			$('#res').html("error ", response);
		}
 	});
}

