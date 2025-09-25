/**
  * Nombre del Módulo: Compras
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 01/Octubre/2015                                      			
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_equipo_mtto;
	var pedido;
	
	function verificarPedido(id_pedido){
		pedido=id_pedido;
		if(pedido!=""){
			var url = "includes/ajax/verificarPedido.php?pedido="+pedido;
			url += "&nocache=" + Math.random();	
			cargaVerificacionPedidos(url, "GET", procesarVerificarPedido);
		}
		else{
			objeto = document.getElementById('txt_pedido');
			objeto.value = "";
		}
	}
	
	function procesarVerificarPedido(){
		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				var respuesta = peticion_equipo_mtto.responseXML;
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var fecha = respuesta.getElementsByTagName("fecha").item(0).firstChild.data
					var hora = respuesta.getElementsByTagName("hora").item(0).firstChild.data;
					var id_pedido = respuesta.getElementsByTagName("id_pedido").item(0).firstChild.data;
					
					alert("El pedido " + id_pedido + " ya se encuentra registrado.\nSe recibio el " + fecha + " a las " + hora);
					
					document.getElementById('txt_pedido').value="";
					document.getElementById('txt_pedido').focus();
				}
			}
		}
	}
	
	function cargaVerificacionPedidos(url, metodo, funcion) {
		peticion_equipo_mtto = iniciar_xhr_req();
		if(peticion_equipo_mtto){
			peticion_equipo_mtto.onreadystatechange = funcion;
			peticion_equipo_mtto.open(metodo, url, true);
			peticion_equipo_mtto.send(null);
		}
	}
	
	function iniciar_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	