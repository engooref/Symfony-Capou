import $ from "jquery";

function validateOperator(emailOperator) {
	$.post({
		url  : "{{ path('adminValidateOperator') }}",
		data : 'email=' + emailOperator,
		dataType : false,
    });
}

function deleteOperator(emailOperator) {
	$.post({
		url  : "{{ path('adminDeleteOperator') }}",
		data : 'email=' + emailOperator,
		dataType : false,
    });	
}
