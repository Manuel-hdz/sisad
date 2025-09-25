/**
  * Nombre del Módulo: Compras                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 23/Noviembre/2010                                      			
  * Descripción: Este archivo contiene las funciones para actualziar el IVA de manera Asincrona en la Base de Datos
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_http_iva;
	
	//Variables para el manejo de calculos
	var nom_importe;
	var nom_iva
	var nom_total;


	/*Esta función obtendrá el dato que se quiere validar*/
	function actualizarIVA(txt_importe,txt_iva,txt_total){
		//Obtener los nombres de las cajas de texto que contendran los resultados de los calculos
		nom_importe = txt_importe;
		nom_iva = txt_iva;		
		nom_total = txt_total;		
		
		var cond = true;		
		do{
			var nuevo_iva = prompt("Escribir el Nuevo Porcentaje de IVA: ","Porcentaje IVA...");
			if(nuevo_iva!=null){
				if(nuevo_iva!="Porcentaje IVA..." && nuevo_iva!=""){
					if(validarEnteroValorCero(nuevo_iva))
						cond = false;
				}
			}
			else
				cond = false;
		}while(cond);
				
		if(nuevo_iva!=null){		
			if(confirm("¿Esta Seguro que Quiere Cambiar el Porcentaje de IVA?")){																
				//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
	 			var url = "../../includes/ajax/actualizarIVA.php?porcentIVA="+nuevo_iva;		
				//Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
				//variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache
				url += "&nocache=" + Math.random();
				//Hacer la Peticion al servidor de forma Asincrona
				cargaContenidoIva(url, "GET", procesarRespuesta);
			}
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoIva(url, metodo, funcion) {
		peticion_http_iva = inicializa_xhr_iva();
		if(peticion_http_iva) {
			peticion_http_iva.onreadystatechange = funcion;
			peticion_http_iva.open(metodo, url, true);
			peticion_http_iva.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_iva() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
		
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarRespuesta(){		
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_iva.readyState==READY_STATE_COMPLETE){
			if(peticion_http_iva.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticion_http_iva.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					var porcentaje = respuesta.getElementsByTagName("porcentaje").item(0).firstChild.data;
					alert("El Porcentaje de IVA fue Actualizado al "+porcentaje+"%");										
					
					//Saber si los precios incluyen IVA o NO
					var ivaInc = document.getElementById("hdn_ivaIncluido").value;
					if(ivaInc=="NO"){					
						//Hacer los calculos para actualizar las cifras mostradas en las paginas
						var cant_importe = parseFloat( document.getElementById(nom_importe).value.replace(/,/g,'') );
						var cant_iva = (cant_importe * porcentaje)/100;
						var cant_total = cant_importe + cant_iva;
						//Dar formato de moneda a las cifras y colocarlas en su respectivo lugar
						formatCurrency(cant_iva,nom_iva);
						formatCurrency(cant_total,nom_total);
					}
					else if(ivaInc=="SI"){
						
						//Hacer los calculos para actualizar las cifras mostradas en las paginas
						var cant_total = parseFloat( document.getElementById(nom_total).value.replace(/,/g,'') );
						var cant_importe = cant_total/(1 + (porcentaje/100));
						var cant_iva = cant_total - cant_importe;
						//Dar formato de moneda a las cifras y colocarlas en su respectivo lugar
						formatCurrency(cant_iva,nom_iva);
						formatCurrency(cant_importe,nom_importe);
					}
					
					//Actualizar la Etiqueta que indica la tasa de IVA aplicada
					document.getElementById("txt_lblIVA").value = porcentaje+"%";
				}
				else{
					alert("No se Pudo Actualizar el Porcentajede IVA");
				}
			}//If if(peticion_http_iva.status==200)
		}//If if(peticion_http_iva.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	
	/*Esta función verifica que el dato proporcionado sea un numero valido y que a su vez este pueda ser igual a 0*/
	function validarEnteroValorCero(valor){ 
		var cond = true;
		//Comprobar si es un valor numérico 
		if (isNaN(valor)) { 			
			//Numero invalido
			alert ("El Dato: '"+valor+"' es Incorrecto, Solo se Aceptan Numeros");
			cond = false;
		}	
		return cond;
	}