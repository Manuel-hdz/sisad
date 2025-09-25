/**
  *	Armando Ayala Alvarado
  * Nombre del Módulo: Gerencia Técnica                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.                                     			
  * Descripción: Este archivo contiene la funcion genera el id de las cuadrillas
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var txt_id;

	
	function obtenerIdCuadrilla(area,campo){
		txt_id = campo;
		var idArea = area.options[area.selectedIndex].value;
		if(idArea != ""){
			var url = "includes/ajax/calcularID.php?id_area="+idArea;
		} else {
			txt_id.value="";
		}
		
		url += "&nocache=" + Math.random();
		cargaID(url, "GET", procesarIdCuadrilla);
	}
	
	function procesarIdCuadrilla(){
		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				respuesta = peticionHTTP.responseXML;
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var id = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					txt_id.value=id;
				}
			}
		}
	}
	
	function cargaID(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
		}
	}
	
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}