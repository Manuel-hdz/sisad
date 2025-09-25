	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_fecha;
	
	var cajaFechaGlobal;
	var fechaDGlobal;
	
	function comprobarFecha(fecha,cajaFecha,presupuesto,fechaD){
		cajaFechaGlobal = cajaFecha;
		fechaDGlobal = fechaD;
		if(fecha!=""){			
			var url = "includes/ajax/comprobarFechaPresupuesto.php?fecha="+fecha+"&presupuesto="+presupuesto;;	
			url += "&nocache=" + Math.random();	
			validaFecha(url, "GET", validacionFecha);
		}
	}
	
	function validacionFecha(){				
		if(peticion_fecha.readyState==READY_STATE_COMPLETE){
			if(peticion_fecha.status==200){
				var respuesta = peticion_fecha.responseXML;
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="false"){
					cajaFechaGlobal.value = fechaDGlobal;
					alert("La fecha seleccionada no se encuentra entre el rango de fechas del presupuesto");
				}
			}
		}
	}
	
	function validaFecha(url, metodo, funcion) {
		peticion_fecha = iniciar_xhr_req();
		if(peticion_fecha){
			peticion_fecha.onreadystatechange = funcion;
			peticion_fecha.open(metodo, url, true);
			peticion_fecha.send(null);
		}
	}
	
	function iniciar_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	