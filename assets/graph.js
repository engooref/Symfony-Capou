var Chart = require('chart.js');

window.addEventListener('load', setup);

async function setup() {
	const ctx1 = document.getElementById('chartContainerTemp').getContext('2d');
	const ctx2 = document.getElementById('chartContainerHumi').getContext('2d');
    const globalData = await getData();
    const chartContainerTemp = new Chart(ctx1, {
    	type: 'line',
      	data: {
        	labels: globalData.Heure,
        	datasets: [
					{
						label: 'Temperature Intérieure',
						data: globalData.Temp1,
						fill: false,
						borderColor: 'blue',
						borderWidth: 1
					},
					{
		                label: 'Temperature Extérieure',
	                	data: globalData.Temp2,
	                	fill: false,
	                	borderColor: 'red',
	                	borderWidth: 1
          			}
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
							if((context.tick.value < 0)||(context.tick.value > 0)) {return '#000000';}
							return '#FFC501';
						}
					}
				}
			}
		}
	});

	const chartContainerHumi = new Chart(ctx2, {
		type: 'line',
	  	data: {
	    	labels: globalData.Heure,
	    	datasets: [
				{
					label: '% Humidité',
					data: globalData.Humi,
					fill: false,
					borderColor: 'blue',
					borderWidth: 1
				}
	    	]
		},
	  	options: {}
	});
}

async function getData() {
	const response = await fetch('data.csv');
    const data = await response.text();
	const Heure = [];
    const Temp1 = [];
    const Temp2 = [];
	const Humi = [];
    const rows = data.split('\n').slice(1);
    rows.forEach(row => {
    	const cols = row.split(',');
    	Heure.push(cols[0]);
    	Temp1.push(parseFloat(cols[1]));
		Temp2.push(parseFloat(cols[2]));
		Humi.push(parseFloat(cols[3]));
    });
	return { Heure, Temp1, Temp2, Humi};
}
