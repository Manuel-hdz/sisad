/**
  * Nombre del Módulo: Topografía
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 04/Junio/2011
  * Descripción: Este archivo contiene las funciones para buscar el precio de Traspaleo de Acuerdo a la Categoria y a la Distancia
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;	
	//Guardar la Petición HTTP para validar los Dato que se quieren guardar en la BD
	var peticion_http_precio;
	var noRegistro;

	/******************************************************************************************
	 * Esta función cargara un combo box en base al valor seleccionado en otro, Parametros:
	 * 1. distancia: Distancia ingresada por el Usuario 
	 * 2. idObra: Id de la Obra para obtener la categoria de precios asociada
	 * 3. noRegistro: No. de Registro cuando se modifica el Detalle de Traspaleo
	 ******************************************************************************************/
	function obtenerPrecio(distancia,idObra,noReg,incluirPrecio){						
		//Verificar que la Distancia y el Destino sean proporcionados para hacer la busqueda de precios
		//Obtener el Destino del movimiento registrado en el traspaleo
		var destino = document.getElementById("txt_destino"+noReg).value.toUpperCase();			
		
		if(distancia!="" && destino!=""){		
			//Obtener el valor de la variable que indica si el registro de Traspaleo incluye precio
			manejarPrecio = document.getElementById(incluirPrecio).value;
			//Si el Registro incluye precios, buscar en la lista correspondiente
			if(manejarPrecio=="si"){
				//Este numero de Registro se utiliza en la pantalla de modificar registros del detalle de Traspaleo
				noRegistro = noReg;					
				var url = "";
				
				//Si el Destino es APLANILLE, buscar en la Lista de precios de APLANILLE
				if(destino=="APLANILLE"){
					//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
					//incluido este archivo JavaScript(obtenerPrecioTraspaleo.js)
					url = "includes/ajax/obtenerPrecioTraspaleo.php?distancia="+distancia.replace(/,/g,'')+"&idObra="+idObra+"&destino="+destino;
				}
				else{//Si no buscar en la Lista de Precios Asociada a la Obra
					//Si la Obra NO esta Registrada en el Catalogo, obtener la lista seleccionada y mandarla para la obtención de precios
					if(idObra=="OBRA_NR"){
						//Obtener la Lista de precios
						var lista = document.getElementById("cmb_listaPrecios").value;
						//Hacer la petición cuando la lista sea diferente de vacia
						if(lista!=""){
							//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
							//incluido este archivo JavaScript(obtenerPrecioTraspaleo.js)
							url = "includes/ajax/obtenerPrecioTraspaleo.php?distancia="+distancia.replace(/,/g,'')+"&idObra="+idObra+"&lista="+lista;
						}
					}
					else{//Buscar los precios en la lista asociada a la Obra seleccionda
						//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
						//incluido este archivo JavaScript(obtenerPrecioTraspaleo.js)
						url = "includes/ajax/obtenerPrecioTraspaleo.php?distancia="+distancia.replace(/,/g,'')+"&idObra="+idObra;				
					}
				}
							
				/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. 
				 *Como cada petición variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al 
				 *servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();	
				//Hacer la Peticion al servidor de forma Asincrona
				cargaContenidoPrecio(url, "GET", procesarPrecio);
			}//Cierre if(manejarPrecio=="si")		
			else{
				if(manejarPrecio=="si"){
					//Limpiar los campos donde se realizan los calculos
					document.getElementById("txt_pumn"+noReg).value = "";
					document.getElementById("txt_puusd"+noReg).value = "";
					document.getElementById("txt_totalMN"+noReg).value = "";
					document.getElementById("txt_totalUSD"+noReg).value = "";
					document.getElementById("txt_importeTotal"+noReg).value = "";
				}
			}
		}//Cierre if(distancia!="" && destino!="")		
	}//Fin de la Funcion cargarCombo(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomCmbCargar,etiqCombo,valSeleccionado)
	
		
	/*Procesar la respuesta del servidor y obtener los resultados de la petición*/
	function procesarPrecio(){				
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
					
					//Colocar el dato en la Caja de Texto o Elemento HTML indicado
					document.getElementById("txt_pumn"+noRegistro).value = pu_mn;
					document.getElementById("txt_puusd"+noRegistro).value = pu_usd;
					
					//Realizar los calculos de los Totales
					calcularTotales(noRegistro);
					
					
					//Obtener la Suma Total cada vez que se Cambie el Total de Cada Registro cuando se modifica el Detalle de Traspaleo
					if(noRegistro!="")
						obtenerSumaTotal();
						
				}
				else{
					//Obtener la Distancia Introducida por el Usuario
					var distancia = respuesta.getElementsByTagName("distancia").item(0).firstChild.data;	 
					alert("No hay Precio Registrado Para la Distancia "+distancia);
					
					//Limpiar los campos donde se realizan los calculos
					document.getElementById("txt_distancia"+noRegistro).value = "";
					document.getElementById("txt_pumn"+noRegistro).value = "";
					document.getElementById("txt_puusd"+noRegistro).value = "";
					document.getElementById("txt_totalMN"+noRegistro).value = "";
					document.getElementById("txt_totalUSD"+noRegistro).value = "";
					document.getElementById("txt_importeTotal"+noRegistro).value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()			
	
		
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoPrecio(url, metodo, funcion) {
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