
function lookup(cajaTexto,nomTabla,num,cajaTexto2,area) {
	var inputString = cajaTexto.value;
	if(inputString.length == 0) {
		$('#suggestions'+num).hide();
	} 
	else{
		var nomCajaTexto = cajaTexto.name;
		$.post("includes/ajax/busq_spider_personal_bitacora.php?nomCajaTexto="+nomCajaTexto+"&nomTabla="+nomTabla+"&num="+num+"&cajaTexto2="+cajaTexto2+"&area="+area, {queryString: ""+inputString+""}, function(data){
			if(data.length >0) {
				$('#suggestions'+num).show();
				$('#autoSuggestionsList'+num).html(data);
			}
		});
	}
}

function fill(nomCampo,thisValue,num) {
	$('#'+nomCampo).val(thisValue);
	$('#suggestions'+num).hide();
}