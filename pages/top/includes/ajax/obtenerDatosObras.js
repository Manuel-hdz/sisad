/**
  * Nombre del Módulo: Topografía
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 27/Agosto/2012
  * Descripción: Este archivo contiene las funciones para buscar los datos de las obras acorde al subtipo
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;	
	//Guardar la Petición HTTP para validar los Dato que se quieren guardar en la BD
	var peticion_http_precio;
	var noRegistro;
	var subtipo;

	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. distancia: Distancia ingresada por el Usuario 
	 * 2. idObra: Id de la Obra para obtener la categoria de precios asociada
	 * 3. noRegistro: No. de Registro cuando se modifica el Detalle de Traspaleo
	 ******************************************************************************************/
	function extraerDatosSubtipoObras(idSubtipo){						
		if(idSubtipo!=""){
			if(idSubtipo=="UPD"){
				if(confirm("¿Actualizar la Lista de Subtipos?"))
					location.href='frm_editarSubtipos.php';
				else
					document.getElementById("cmb_subtipo").value = "";
			}
			else{
				subtipo=idSubtipo;
				//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
				//incluido este archivo JavaScript(obtenerPrecioTraspaleo.js)
				url = "includes/ajax/obtenerDatosObras.php?idSubtipo="+idSubtipo;
				/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
				 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
				 *servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();	
				//Hacer la Peticion al servidor de forma Asincrona
				cargaContenidoObrasVerificadas(url, "GET", procesarSubtipos);
			}
		}//Cierre if(manejarPrecio=="si")		
		else{
			//Limpiar los campos donde se realizan los calculos
			document.getElementById("txt_precioEstimacionMN").value = "";
			document.getElementById("txt_precioEstimacionUSD").value = "";
			document.getElementById("txt_seccion").value = "";
			document.getElementById("txt_area").value = "";
			document.getElementById("txt_unidad").value = "";
			document.getElementById("txt_seccion").readOnly = false;
		}
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarSubtipos(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_precio.readyState==READY_STATE_COMPLETE){
			if(peticion_http_precio.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_precio.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;				
				if (existe=="true"){					 					
					//Obtener los Precios Unitarios
					var pu_mn = respuesta.getElementsByTagName("pumn").item(0).firstChild.data;	 
					var pu_usd = respuesta.getElementsByTagName("puusd").item(0).firstChild.data;
					var seccion = respuesta.getElementsByTagName("seccion").item(0).firstChild.data;	 
					var area = respuesta.getElementsByTagName("area").item(0).firstChild.data;
					
					if(seccion!="¬EMPTY"){
						document.getElementById("txt_seccion").readOnly = false;
						document.getElementById("txt_seccion").value = seccion;
					}
					else{
						document.getElementById("txt_seccion").value = "N/A";
						document.getElementById("txt_seccion").readOnly = true;
					}
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById("txt_precioEstimacionMN").value = pu_mn;
					document.getElementById("txt_precioEstimacionUSD").value = pu_usd;
					if(area!="0")
						formatCurrency(area,'txt_area');
					else
						document.getElementById("txt_area").value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()			
	
	/******************************************************************************************
	 * Esta función identificara el tipo de Obras usando los siguientes parametros:
	 * 1. idObra: Id de la Obra seleccionada por el usuario a partir de un combo 
	 ******************************************************************************************/
	function verificarAnclas(tipoObra,idObra){
		if(idObra!=""){
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript(obtenerPrecioTraspaleo.js)
			url = "includes/ajax/obtenerDatosObras.php?tipoObra="+tipoObra+"&idObra="+idObra+"&Num=2";
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
			 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoObrasVerificadas(url, "GET", procesarAnclas);
			//document.write(url);
		}//Cierre if(manejarPrecio=="si")
		else
			document.frm_elegirObraTraspaleo.action="frm_registrarTraspaleo.php";
	}
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarAnclas(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_http_precio.readyState==READY_STATE_COMPLETE){
			if(peticion_http_precio.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_http_precio.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var id = respuesta.getElementsByTagName("id").item(0).firstChild.data;
					var tipo = respuesta.getElementsByTagName("tipo").item(0).firstChild.data;
					var clasificacion = respuesta.getElementsByTagName("clasificacion").item(0).firstChild.data;
					if(clasificacion=="ANCLA")
						//Modificar el Action del formulario
						document.frm_elegirObraTraspaleo.action="frm_registrarAnclas.php?id="+id+"&tipo="+tipo;
					else{
						//Trabajar la variable Hidden que indica el tipo de Obra
						document.getElementById("hdn_seccion").value="N/A";
						//Si no es Ancla, restablecer el action del formulario
						document.frm_elegirObraTraspaleo.action="frm_registrarTraspaleo.php";
					}
				}
				else{
					document.frm_elegirObraTraspaleo.action="frm_registrarTraspaleo.php";
					//Trabajar la variable Hidden que indica el tipo de Obra
					document.getElementById("hdn_seccion").value="";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoObrasVerificadas(url, metodo, funcion) {
		peticion_http_precio = inicializa_xhr_precio();
		if(peticion_http_precio){
			peticion_http_precio.onreadystatechange = funcion;
			peticion_http_precio.open(metodo, url, true);
			peticion_http_precio.send(null);
		}
	}
	
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializa_xhr_precio() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}