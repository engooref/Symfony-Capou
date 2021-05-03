const CanvasJS = require('canvasjs');
import $ from "jquery";

var dataPoints = [];

function getDataPointsFromCSV_Temp1(csv) {
	var dataPoints = csvLines = points = [];
	  csvLines = csv.split(/[\r?\n|\r|\n]+/);         
		   
			for (var i = 0; i < csvLines.length; i++)
			   if (csvLines[i].length > 0) {
				   points = csvLines[i].split(",");
					 dataPoints.push({ 
						   x: parseFloat(points[0]), 
						   y: parseFloat(points[1]) 		
				   });
			}
   return dataPoints;
}

function getDataPointsFromCSV_Temp2(csv) {
	var dataPoints = csvLines = points = [];
	  csvLines = csv.split(/[\r?\n|\r|\n]+/);         
		   
			for (var i = 0; i < csvLines.length; i++)
			   if (csvLines[i].length > 0) {
				   points = csvLines[i].split(",");
					 dataPoints.push({ 
						   x: parseFloat(points[0]), 
						   y: parseFloat(points[2]) 		
				   });
			}
   return dataPoints;
}

function getDataPointsFromCSV_Humi(csv) {
	var dataPoints = csvLines = points = [];
	  csvLines = csv.split(/[\r?\n|\r|\n]+/);         
		   
			for (var i = 0; i < csvLines.length; i++)
			   if (csvLines[i].length > 0) {
				   points = csvLines[i].split(",");
					 dataPoints.push({ 
						   x: parseFloat(points[0]), 
						   y: parseFloat(points[3]) 		
				   });
			}
   return dataPoints;
}

window.onload = $.get("data.csv", function(data) {

	var chartTemp = new CanvasJS.Chart("chartContainerTemp", {
		animationEnabled: true,
		title: {
			text: "Donnees Température"
		},
		axisX: {
            valueFormatString: "DD MM YYYY" ,
			title: "Jour - Heure"
		},
		axisY: {
			title: "Temperature",
			suffix:"°C",
			includeZero: true
		},
		data: [{
			type: "line",
			name: "Temperature1",
			color:"blue",
			connectNullData: true,
			//nullDataLineDashType: "solid",
			xValueType: "dateTime",
			xValueFormatString: "DD MM YYYY HH:mm ",
			yValueFormatString: "#0.## °C",
			dataPoints: getDataPointsFromCSV_Temp1(data)
		},
		{
			type: "line",
			name: "Temperature2",
			color:"red",
			connectNullData: true,
			//nullDataLineDashType: "solid",
			xValueType: "dateTime",
			xValueFormatString: "DD MM YYYY HH:mm ",
			yValueFormatString: "#0.## °C",
			dataPoints: getDataPointsFromCSV_Temp2(data)
		}]
	});

	var chartHumi = new CanvasJS.Chart("chartContainerHumi", {
		animationEnabled: true,
		title: {
			text: "Donnees Humidité"
		},
		axisX: {
            valueFormatString: "DD MM YYYY" ,
			title: "Jour - Heure"
		},
		axisY: {
			title: "Humidité",
			suffix:"%",
			includeZero: true
		},
		data: [{
			type: "line",
			name: "Humidité",
			color:"blue",
			connectNullData: true,
			//nullDataLineDashType: "solid",
			xValueType: "dateTime",
			xValueFormatString: "DD MM YYYY HH:mm ",
			yValueFormatString: "#0.## °C",
			dataPoints: getDataPointsFromCSV_Humi(data)
		}]
	});
	chartTemp.render();
	chartHumi.render();
})
