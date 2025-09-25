	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_periodo;
	var ccCmb;
	var nomCmbCargarGral;
	var etqComboGral;


	function cargarPresupuesto(cc,nomCmbCargar,etqCombo){
		ccCmb = cc;
		nomCmbCargarGral = nomCmbCargar;
		etqComboGral = etqCombo;
		id_costos = ccCmb.value;
		
		if(id_costos!=""){			
			var url = "includes/ajax/cargarComboPresupuesto.php?id_costos="+id_costos;	
			url += "&nocache=" + Math.random();	
			cargaContenidoPeriodo(url, "GET", cargarDatosCmbPeriodo);
		}
		else{
			objeto = document.getElementById(nomCmbCargarGral);
			objeto.length = 0;
			objeto.length++;
			objeto.options[objeto.length-1].text=etqComboGral;
			objeto.options[objeto.length-1].value="";
		}
	}
	
	function cargarDatosCmbPeriodo(){				
		if(peticion_periodo.readyState==READY_STATE_COMPLETE){
			if(peticion_periodo.status==200){
				var respuesta = peticion_periodo.responseXML;
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;					
				if (existe=="true"){					 					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var valor;
					objeto = document.getElementById(nomCmbCargarGral);					
					objeto.length = 0;
					objeto.length++;
					objeto.options[objeto.length-1].text = etqComboGral;
					objeto.options[objeto.length-1].value = "";
					
					for(var i=0;i<tam;i++){
						valor = respuesta.getElementsByTagName("idPresupuesto"+(i+1)).item(0).firstChild.data;
						var periodo = respuesta.getElementsByTagName("periodo"+(i+1)).item(0).firstChild.data;
						var fechaIni = respuesta.getElementsByTagName("fecha_inicio"+(i+1)).item(0).firstChild.data;
						
						var texto = periodo+" ----- "+fechaIni;
						
						objeto.length++;
						objeto.options[objeto.length-1].text = texto;
						objeto.options[objeto.length-1].value = valor;
						objeto.options[objeto.length-1].title = valor;
					}
				}
				else{
					objeto = document.getElementById(nomCmbCargarGral);					
					objeto.length = 0;
					objeto.length++;
					objeto.options[objeto.length-1].text = "No Hay Presupuestos Registrados";
					objeto.options[objeto.length-1].value = "";
				}
			}
		}
	}
	
	function cargaContenidoPeriodo(url, metodo, funcion) {
		peticion_periodo = iniciar_xhr_req();
		if(peticion_periodo){
			peticion_periodo.onreadystatechange = funcion;
			peticion_periodo.open(metodo, url, true);
			peticion_periodo.send(null);
		}
	}
	
	function iniciar_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	