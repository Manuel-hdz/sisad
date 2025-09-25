function ismaxlength(obj){
	var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
	if (obj.getAttribute && obj.value.length>mlength){
		obj.value=obj.value.substring(0,mlength)
		alert("Alcanzaste el máximo de "+mlength+" caracteres");
	}
}