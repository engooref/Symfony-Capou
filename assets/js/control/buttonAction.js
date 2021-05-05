import $ from "jquery";


$( "#syncButton" ).click(function() {

});

$( "#pingButton" ).click(function() {
	$.get({
			url  : '/mapsControl',
   			dataType : 'json',
   			success : SuccessMaps
        });
});