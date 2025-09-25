/**
  * Nombre del M�dulo: Desarrollo                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 28/Octubre/2011                                       			
  * Descripci�n: Este archivo contiene las funciones para revertir los cambios hechos en la BD cuando los registros en las bitacoras sean cancelados
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var manipularRegistros;
	
	//Esta variable se usa cuando se van a cargar los datos de un registro para ser borrado o modificados
	var tRegistro;//Valores 'fallas' o 'consumos'

	/*Esta funci�n borrara los registros de la bitacora de fallas, consumos y explosivos cuando sea cancelada la operacion */
	function borrarFallasConsumosTNT(idBitacora,tipoBitacora,nomTabla,tipoRegistro){		
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
		var url = "includes/ajax/fallasConsumosTNT.php?idBit="+idBitacora+"&tipoBit="+tipoBitacora+"&nomTabla="+nomTabla+"&tipoReg="+tipoRegistro+"&tipoOper=borrar";
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargarElementos(url, "GET", procesarBorradoRegistros);
	}//Fin de la Funcion obtenerSueldo(campo)
			

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarBorradoRegistros(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(manipularRegistros.readyState==READY_STATE_COMPLETE){
			if(manipularRegistros.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = manipularRegistros.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){																					
					/*No se puede notificar s� la operaci�n se realiz� con �xito al usuario, ya que cuando se le da click al boton de cancelar de la ventana de 
					registro de fallas, consumos y explosivos, �sta se cierra, la forma de comprobar si esta operaci�n se lleva a cabo con �xito, es verificar el archivo XML 
					que se genera en el navegador cuando se invoca el archivo de fallasConsumosTNT.php con la sig. 
					url => "includes/ajax/fallasConsumosTNT.php?idBit="+idBitacora+"&tipoBit="+tipoBitacora+"&nomTabla="+nomTabla+"&tipoReg="+tipoRegistro+"&tipoOper=borrar" */
				}				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	
	/*Esta funci�n elimina los registros hechos en las Bit�coras de Barrenaci�n, Voladura y Rezagado*/
	function borrarRegistrosBitacoras(idBitacora,tipoBitacora){
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
		var url = "includes/ajax/fallasConsumosTNT.php?idBit="+idBitacora+"&tipoBit="+tipoBitacora+"&tipoOper=borrarBitacoras";
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargarElementos(url, "GET", procesarBorradoBitacoras);	
	}//Cierre de la funcion borrarRegistrosBitacoras(idBitacora)
	
	
	/*Esta funci�n procesa el Archivo XML generado por la respuesta del servidor a la funci�n borrarRegistrosBitacoras(idBitacora,tipoBitacora)*/
	function procesarBorradoBitacoras(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(manipularRegistros.readyState==READY_STATE_COMPLETE){
			if(manipularRegistros.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = manipularRegistros.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){																					
					/*No se puede notificar s� la operaci�n se realiz� con �xito al usuario, ya que cuando se le da click al boton de cancelar de la ventana de 
					registro de fallas, consumos y explosivos, �sta se cierra, la forma de comprobar si esta operaci�n se lleva a cabo con �xito, es verificar el archivo XML 
					que se genera en el navegador cuando se invoca el archivo de fallasConsumosTNT.php con la sig. 
					url => "includes/ajax/fallasConsumosTNT.php?idBit="+idBitacora+"&tipoBit="+tipoBitacora+"&tipoOper=borrarBitacoras" */
				}				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Cierre de la funcion procesarBorradoBitacoras()
	
	
	
	/*Esta funcion carga los datos de Medida y Categoria que son mostrardas en la ventana de Registro de Explosivos Empleados*/
	function cargarDatosExplosivos(idExplosivo){
		//Verificar que el id del Explosivo sea proporcionado
		if(idExplosivo!=""){
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
			var url = "includes/ajax/fallasConsumosTNT.php?idTNT="+idExplosivo+"&tipoOper=cargarDatosTNT";
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarElementos(url, "GET", procesarCargaDatosTNT);
		}
		else{//En el caso de que no sea proporcionado el ID del Explosivo, borrar los datos que puedan estar en las cajas de texto
			document.getElementById("txt_medida").value = "";
			document.getElementById("txt_categoria").value = "";
		}
	}//Cierre de la funcion cargarDatosExploivos(idExplosivo)
	
	
	/*Esta funcion obtiene los datos adicionales del Explosivo para colocarlos en el formulario*/
	function procesarCargaDatosTNT(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(manipularRegistros.readyState==READY_STATE_COMPLETE){
			if(manipularRegistros.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = manipularRegistros.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Obtener los datos adicionales del Explosivo del codigo XML
					var medida = respuesta.getElementsByTagName("medida").item(0).firstChild.data;
					var categoria = respuesta.getElementsByTagName("categoria").item(0).firstChild.data;
					
					//Asignar los datos a las cajas de texto del formulario de registro de los Explosivos Empleados
					document.getElementById("txt_medida").value = medida;
					document.getElementById("txt_categoria").value = categoria;										
				}
				else{//En el caso de que no haya registro, borrar los datos que puedan estar en las cajas de texto
					document.getElementById("txt_medida").value = "";
					document.getElementById("txt_categoria").value = "";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Cierre de la funci�n procesarCargaDatosTNT()
	
	
	/*Esta funcion carga los datos del registro seleccionado de Fallas o Consumos para ser modificado*/
	function cargarDatosRegitro(noReg,idBitacora,tipoBitacora,tipoRegistro){				
		//Guardar el No de Falla y el tipo de Registro
		tRegistro = tipoRegistro;
		
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript
		var url = "includes/ajax/fallasConsumosTNT.php?noReg="+noReg+"&idBit="+idBitacora+"&tipoBit="+tipoBitacora+"&tipoReg="+tipoRegistro+"&tipoOper=cargar";
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();		
		//Hacer la Peticion al servidor de forma Asincrona
		cargarElementos(url, "GET", procesarCargarRegistro);
	}//Cierre de la funci�n cargarDatosRegitro(noFalla,idBitacora,tipoBitacora,tipoRegistro)
	
	
	/*Esta funci�n carga los registros de Fallas y Consumos al formulario respectivo para ser Modificado o Eliminado*/
	function procesarCargarRegistro(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(manipularRegistros.readyState==READY_STATE_COMPLETE){
			if(manipularRegistros.status==200){
				
				
				//Recuperar la respuesta del Servidor
				respuesta = manipularRegistros.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Dependiendo del tipo de Registro que se va hacer obtener y colocar los datos que correspondas a Fallas o Consumos
					if(tRegistro=="fallas"){
						//Recuperar los datos del registro y colocarlos en el Formulario donde sera modificada la informacion
						var tipoFalla = respuesta.getElementsByTagName("tipoFalla").item(0).firstChild.data;
						var descripcion = respuesta.getElementsByTagName("descripcion").item(0).firstChild.data;
						var tiempo = respuesta.getElementsByTagName("tiempo").item(0).firstChild.data;
						
						//Colocar los datos en los Campos correspondientes en el formulario de la Pagina de Modificar Fallas
						document.getElementById("cmb_tipo").value = tipoFalla;
						document.getElementById("txa_observaciones").value = descripcion;
						document.getElementById("txt_tiempoHrs").value = tiempo;
						
						//Activar los botones de Borrar y Modificar
						document.getElementById("sbt_modificar").disabled = false;
						document.getElementById("sbt_borrar").disabled = false;
						
						//Desactivar el Boton de Guardar
						document.getElementById("sbt_guardar").disabled = true;
					}
					else if(tRegistro=="consumos"){
						//Recuperar los datos del registro y colocarlos en el Formulario donde sera modificada la informaci�n
						var cantidad = respuesta.getElementsByTagName("cantidad").item(0).firstChild.data;
						
						//Colocar los datos en los Campos correspondientes en el formulario de la Pagina de Modificar Fallas
						formatCurrency(cantidad,'txt_cantidad');
						
						//Activar los botones de Borrar y Modificar
						document.getElementById("sbt_modificar").disabled = false;
						document.getElementById("sbt_borrar").disabled = false;												
						
						//Desactivar el Boton de Guardar
						document.getElementById("sbt_guardar").disabled = true;
						
						//Verificar si el CheckBox de Agregar nuevo material esta seleccionado
						if(document.getElementById("chk_nvoMaterial").checked){
							//Deseleccionar y desactivar el CheckBox
							document.getElementById("chk_nvoMaterial").checked = false;
							document.getElementById("chk_nvoMaterial").disabled = true;
							
							//Vaciar y Desactivar los campos para agregar un nuevo material y vaciarlos
							document.getElementById("txt_material").value = "";
							document.getElementById("txt_material").readOnly = true;
							document.getElementById("txt_unidadMedida").value = "";
							document.getElementById("txt_unidadMedida").readOnly = true;
							document.getElementById("txt_cant").value = "";														
							document.getElementById("txt_cant").readOnly = true;
							
							//Activar la Caja de Texto de la Cantidad, ya que al seleccionar el CheckBox, �sta fue desactivada
							document.getElementById("txt_cantidad").disabled = false;
		
						}
						else{		
							//Vaciar y Desactivar los Combos de Categor�a y Material y el CheckBox de agregar nuevo
							document.getElementById("cmb_categoria").value = "";
							document.getElementById("cmb_categoria").disabled = true;
							document.getElementById("cmb_idMaterial").value = "";
							document.getElementById("cmb_idMaterial").disabled = true;							
							document.getElementById("chk_nvoMaterial").disabled = true;						
						}
					}//Cierre else if(tRegistro=="consumos")					
					else if(tRegistro=="explosivos"){
						//Recuperar los datos del registro y colocarlos en el Formulario donde sera modificada la informaci�n
						var idExp = respuesta.getElementsByTagName("idExplosivo").item(0).firstChild.data;
						var medida = respuesta.getElementsByTagName("medida").item(0).firstChild.data;
						var categoria = respuesta.getElementsByTagName("categoria").item(0).firstChild.data;
						var cantidad = respuesta.getElementsByTagName("cantidad").item(0).firstChild.data;
						
						//Colocar los datos en los Campos correspondientes en el formulario de la Pagina de Modificar Fallas
						document.getElementById("cmb_explosivo").value = idExp;
						document.getElementById("cmb_explosivo").disabled = true;//Deshabilitar el Combo para que no cambie el nombre del Material
						document.getElementById("txt_medida").value = medida;
						document.getElementById("txt_categoria").value = categoria;
						formatCurrency(cantidad,'txt_cantidad');
						//document.getElementById("txt_cantidad").value = cantidad;
						
						//Activar los botones de Borrar y Modificar
						document.getElementById("sbt_modificar").disabled = false;
						document.getElementById("sbt_borrar").disabled = false;												
						
						//Desactivar el Boton de Guardar
						document.getElementById("sbt_guardar").disabled = true;																		
					}//Cierre else if(tRegistro=="explosivos")
				}//Cierre if (existe=="true")
				
				
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarCargarRegistro()
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarElementos(url, metodo, funcion) {
		manipularRegistros = inicializarXHR();
		if(manipularRegistros) {
			manipularRegistros.onreadystatechange = funcion;
			manipularRegistros.open(metodo, url, true);
			manipularRegistros.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializarXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}