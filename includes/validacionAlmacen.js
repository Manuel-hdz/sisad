/**
  * Nombre del Módulo: Almacén                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 30/Septiembre/2010                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Almacén
  */
/*****************************************************************************************************************************************************************************************/
/************************************************************************VALIDAR CARACTERES***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Almacén*/
function permite(elEvento, permitidos) {
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ";
	var numeros_caracteres = numeros + caracteres;
	var teclas_especiales = [8, 34, 37, 44, 45, 46, 47];//8 = BackSpace, 34 = Comillas Dobles, 37 = Signo Porcentaje, 44 = Coma, 45 = Guion medio, 46 = Punto, 47 = Diagonal
	//Seleccionar los caracteres a partir del parámetro de la función
	switch(permitidos) {
		case 'num':
			permitidos = numeros;
		break;
		case 'car':
			permitidos = caracteres;
		break;
		case 'num_car':
			permitidos = numeros_caracteres;
		break;
	}
	
	//Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);
	
	//Comprobar si la tecla pulsada es alguna de las teclas especiales
	//(teclas de borrado y flechas horizontales)
	var tecla_especial = false;
	for(var i in teclas_especiales) {
		if(codigoCaracter == teclas_especiales[i]) {
			tecla_especial = true;
			break;
		}
	}

	//Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
	//o si es una tecla especial
	return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

/********************************************Función que permite Validar que en las Claves del Material NO se puedan ingresar comillas(")*********************************/

/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Almacén*/
function permiteClavesMaterial(elEvento, permitidos) {
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ";
	var numeros_caracteres = numeros + caracteres;
	var teclas_especiales = [8, 37, 44, 45, 46, 47];//8 = BackSpace, 37 = Signo Porcentaje, 44 = Coma, 45 = Guion medio, 46 = Punto, 47 = Diagonal
	//Seleccionar los caracteres a partir del parámetro de la función
	switch(permitidos) {
		case 'num':
			permitidos = numeros;
		break;
		case 'car':
			permitidos = caracteres;
		break;
		case 'num_car':
			permitidos = numeros_caracteres;
		break;
	}
	
	//Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);
	
	//Comprobar si la tecla pulsada es alguna de las teclas especiales
	//(teclas de borrado y flechas horizontales)
	var tecla_especial = false;
	for(var i in teclas_especiales) {
		if(codigoCaracter == teclas_especiales[i]) {
			tecla_especial = true;
			break;
		}
	}

	//Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
	//o si es una tecla especial
	return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

/*Esta funcion permitira evaluar si un documento o archivo cargado tiene el formato válido*/
function validarCSV(campo){
	//Obtener la longitud de la ruta del archivo	
	var tam = campo.value.length;
	//Obtener la cadena de la ruta del archivo
	var cadena = campo.value;
	//Si el usuario decide quitar el documento, entonces la longitud de la ruta del archivo es 0 colocamos el valor "si" en la variable hdn_docValido para que proceda
	//la operacion correspondiente
	if(tam!=0){
		//Obtener la extension del archivo
		var extension = cadena.charAt(tam-3)+cadena.charAt(tam-2)+cadena.charAt(tam-1);
		//var extension = campo.value.substring(tam-3,tam);
		//Pasar la extension de la imagen a minusculas para evaluarla como tal
		extension = extension.toLowerCase();
		//Comparar la extension contra las extensiones de los archivos permitidos
		if(extension!="csv" ){
			alert("Formato de Archivo no Soportado, Formatos Validos: 'csv'");
			document.getElementById("hdn_docValido").value = "no";
		}
		else
			document.getElementById("hdn_docValido").value = "si";
	}
}

/*****************************************************************************************************************************************************************************************/
/************************************************************************EXPORTAR /IMPORTAR CSV*******************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Funcion que valida seza seleccionada una linea de articulo a exportar
function valFormExportarCSV(frm_exportarCSV){
	if(frm_exportarCSV.cmb_lineaArticulo.value==""){
		alert("Seleccionar la Línea del Material a Exportar");
		return false;
	}
	else
		return true;
}

//Funcion que valida se cargue un archivo CSV
function valFormImportarCSV(frm_importarCSV){
	if(frm_importarCSV.hdn_docValido.value=="no"){
		alert("Ingresar Archivo con el Formato Válido. Formato Permitido '.csv'");
		return false;
	}
	else
		return true;
}

/*****************************************************************************************************************************************************************************************/
/**************************************************************************AGREGAR MATERIAL***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función pregunta al usuario si el material que va a ser agregado debe tener como existencia solo 1 */
function definirTipoMat(){
	var respuesta = confirm("¿El Material que va a ingresar debe tener de existencia solo 1?");
	if(respuesta){
		//Colocar uno a los campos de Cantidad, Nivel Mínimo, Nivel Máximo y Punto de Reorden
		document.getElementById("txt_cantidad").value = 1; document.getElementById("txt_cantidad").readOnly = true;
		document.getElementById("txt_nivelMinimo").value = 1; document.getElementById("txt_nivelMinimo").readOnly = true;
		document.getElementById("txt_nivelMaximo").value = 1; document.getElementById("txt_nivelMaximo").readOnly = true;
		document.getElementById("txt_puntoReorden").value = 1; document.getElementById("txt_puntoReorden").readOnly = true;
		//Colocar el valor de 'si' a la variable de hdn_matEspecial para dar otra validacion a dicho material.
		document.getElementById("hdn_matEspecial").value = "si";
	}
}


/*Esta funcion indica que valores deben ser considerados para los movimientos que seran registrados en los materiales*/
function definirNivelesMovimiento(comboRelevancia){
	//Si la opcion seleccionada es STOCK, preguntar si el material tendra existencia de 1
	if(comboRelevancia.value=="STOCK"){
		definirTipoMat();
	}//Si se selecciona cualquiera de las otras dos opciones, colocar valor 0 en los campos de nivel minimo, maximo y punto de reorden
	else if(comboRelevancia.value!=""){
		//Activar y vaciar el campo de Cantidad en caso de que este ocupado
		document.getElementById("txt_cantidad").value = ""; document.getElementById("txt_cantidad").readOnly = false;
		//Colocar valor 0 a los campos y dejarlos como readonly
		document.getElementById("txt_nivelMinimo").value = 0; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = 0; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = 0; document.getElementById("txt_puntoReorden").readOnly = false;		
	}//Si se selecciona la opcion vacia, vaciar y activart los campos
	else if(comboRelevancia.value==""){		
		document.getElementById("txt_cantidad").value = ""; document.getElementById("txt_cantidad").readOnly = false;		
		document.getElementById("txt_nivelMinimo").value = ""; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = ""; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = ""; document.getElementById("txt_puntoReorden").readOnly = false;		
	}
}//Cierre de la funcion definirNivelesMovimiento(comboRelevancia)

function valFormAgregarMateriales(frm_agregarMaterial){
	var res=1;
	var cantidad = frm_agregarMaterial.txt_cantidad.value;
	var minimo = frm_agregarMaterial.txt_nivelMinimo.value;
	var maximo = frm_agregarMaterial.txt_nivelMaximo.value;
	var reorden = frm_agregarMaterial.txt_puntoReorden.value;
	
	if(res == 1 && (minimo>reorden)){
		alert("El Nivel Mínimo no Puede Ser Mayor al Punto de Reorden");
		res = 0;
	}
	
	if(res == 1 && (minimo>maximo || minimo==maximo)){
		alert("El Nivel Mínimo no Puede Ser Mayor o Igual al Nivel Maximo");
		res = 0;
	}
	
	if(res == 1 && (reorden==maximo || reorden>maximo)){
		alert("El Punto de Reorden NO Puede Ser Igual o Mayor que el Nivel Máximo");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*Validar los datos del formulario Agregar Material que no esten vacios y que los datos numericos sean validos */
function valFormAgregarMaterial(frm_agregarMaterial){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;				
	if (document.getElementById("hdn_nuevoAdd").value!="Add"){
		//Verificar primero que la Clave no este vacía
		if(frm_agregarMaterial.txt_clave.value==""){
			res = 0;		
			alert ("Introducir una Clave para el Material");
		}
		//LINEA TOTALMENTE NUEVA
		//Comprobar que la clave este repetida y preguntar la aprobacion del usuario para continuar
		if(document.getElementById("hdn_claveValida").value!="si" && res==1){
			if (confirm("Se detecto una clave Repetida, de ser el mismo Material, el Sistema incrementara la cantidad de Existencia en el Stock.\n¿Desea Continuar?")){
				document.getElementById("hdn_continuar").value="si";
			}
			else{
				document.getElementById("hdn_continuar").value="no";
			}
		}
		else
			document.getElementById("hdn_continuar").value="no";
	}
	//LINEA TOTALMENTE NUEVA
	if(document.getElementById("hdn_continuar").value=="no" || document.getElementById("hdn_nuevoAdd").value=="Add"){
		//Primero se debe validar que los campos del formulario de Agregar Material no se encuentren vacíos
		var cond = verContFormAgregarMaterial(frm_agregarMaterial);	
		
		
		//Si todos los campos fueron proporcionados, proceder a validar su contenido
		if(cond){
			//Si se trata de un material de STOCK y NO es un Material Especial validar que los datos de Nivel Mínimo, Nivel Máximo y Punto de Reorden sean correctos
			if(frm_agregarMaterial.cmb_relevancia.value=="STOCK" && frm_agregarMaterial.hdn_matEspecial.value=="no"){									
				//Verificar que los datos numericos del formulario sean numeros: Cantidad, Nivel Mínimo, Nivel Máximo, Punto de Reorden, Costo Unitario y el Factor de Conversión
				if(validarEntero(frm_agregarMaterial.txt_cantidad.value,"La Cantidad del Material")){
					if(validarEnteroValorCero(frm_agregarMaterial.txt_nivelMinimo.value)){
						if(validarEntero(frm_agregarMaterial.txt_nivelMaximo.value,"El Nivel Máximo del Material")){
							if(validarEnteroValorCero(frm_agregarMaterial.txt_puntoReorden.value)){
							}else{ res = 0; }
						}else{ res = 0; }
					}else{ res = 0; }
				}else{ res = 0;	}
			}//Cierre if(frm_agregarMaterial.cmb_relevancia.value=="STOCK")
		
					
			if(validarEntero(frm_agregarMaterial.txt_costoUnidad.value.replace(/,/g,''),"El Costo Unitario del Material")){
				if(!validarEntero(frm_agregarMaterial.txt_factor.value,"El Factor de Conversión del Material")){
					res = 0;
				}
			}else{ res = 0; }
		}else{res = 0; }//Cierre else if(cond)
	
	
	
		//Si se trata de un material de STOCK y NO es un Material Especial validar que la congruncia entre el Nivel Mínimo, Nivel Máximo y Punto de Reorden sea correcta
		if(frm_agregarMaterial.cmb_relevancia.value=="STOCK" && frm_agregarMaterial.hdn_matEspecial.value=="no" && res==1){
			//Obtener los datos numericos de la Cantidad, Nivel Minimo, Nivel Maximo y Punto de Reorden
			var minimo = parseInt(frm_agregarMaterial.txt_nivelMinimo.value);
			var maximo = parseInt(frm_agregarMaterial.txt_nivelMaximo.value);
			var reorden = parseInt(frm_agregarMaterial.txt_puntoReorden.value);	
		
			//Validar que el Punto de Reorden no sea igual o menor que el Nivel Mínimo
			if(res==1){
				if(minimo>reorden){
					alert("El Nivel Mínimo no Puede Ser Mayor al Punto de Reorden");
					res = 0;
				}			
			}	
		
			//Validar que el Nivel Máximo no sea igual o menor que el punto de reorden y que no sea igual o menor que el Nivel Mínimo
			if(res==1){
				if(reorden==maximo || reorden>maximo){
					alert("El Punto de Reorden NO Puede Ser Igual o Mayor que el Nivel Máximo");
					res = 0;
				}
			}						
		}//Cierre if(frm_agregarMaterial.hdn_matEspecial.value=="no")
		
	
					
		//Validar que cuando el factor de conversion sea 1, la unidad de medida y la unidad de despacho tienen que ser las mismas
		if(res==1){
			if(frm_agregarMaterial.txt_factor.value==1){
				var unidad_medida;
				if(frm_agregarMaterial.cmb_unidadMedida.disabled==false)
					unidad_medida = frm_agregarMaterial.cmb_unidadMedida.value;
				else
					unidad_medida = frm_agregarMaterial.hdn_unidadMedida.value.toUpperCase();
					
				var unidad_despacho = frm_agregarMaterial.txt_unidadDespacho.value.toUpperCase();
				
				if(unidad_medida!=unidad_despacho){
					alert("Cuando el Factor de Conversión es Igual a 1,\nla Unidad de Medida y la Unidad de Despacho Deben Ser Iguales ");
					res = 0;
				}
			}			
		}		
		//Verificar que la Ubicacion no este vacío
		if(frm_agregarMaterial.txt_ubicacion.value=="" && res==1){
			res = 0;	
			alert ("Introducir la Ubicación del Material");
		}
		
		//Verificar que la Ubicacion no este vacío
		if(frm_agregarMaterial.txt_moneda.value=="" && res==1){
			res = 0;	
			alert ("Selecionar el tipo de moneda");
		}
		
		//Verificar que la Clave ingresada no este repetida
		if(document.getElementById("hdn_claveValida").value!="si" && res==1){
			alert("Verificar la Clave Proporcionada para el Material");
			res = 0;
		}
		
		
		//Verificar que la imagen proporcionada tenga el formato y tamaño soportado por el Sistema
		if(document.getElementById("hdn_imgValida").value!="si" && res==1){
			alert("Verificar el Formato y Tamaño de la de la Imagen Proporcionada\nTamaño de la Imagen Seleccionada: "+parseInt(document.getElementById("hdn_tamImg").value/1024)+" Kb");
			res = 0;
		}
		
		if(res==1 && frm_agregarMaterial.txt_codigoBarras.value==""){
		alert("Introducir o Escanear el Código de Barras");
		res = 0;
		frm_agregarMaterial.txt_codigoBarras.focus();
	}
	
	if(res==1 && frm_agregarMaterial.txt_codigoBarras.value!="" && frm_agregarMaterial.hdn_codeValido.value=="1"){
		alert("El Código de Barras Ingresado pertenece a otro Material");
		res = 0;
		frm_agregarMaterial.txt_codigoBarras.focus();
	}
	}//Fin de la linea del IF recien agregada
	
	//Regresar el resultado final de la validacion
	if(res==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormAgregarMaterial(frm_agregarMaterial)

/* Esta función se encarga de verificar que todos los datos obligatorios del formulario de Agregar Material no esten vacíos*/
function verContFormAgregarMaterial(frm_agregarMaterial){		
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	//Verificar primero que la Clave no este vacía
	if(frm_agregarMaterial.txt_clave.value==""){
		band = 0;		
		alert ("Introducir una Clave para el Material");
	}
		
	//Luego verificar que el Nombre no este vacío
	if(frm_agregarMaterial.txt_nombre.value=="" && band==1){
		band = 0;
		alert ("Introducir un Nombre para el Material");
	}
	
	if (frm_agregarMaterial.cmb_relevancia.value=="" && band==1){
		band = 0;	
		alert ("Seleccionar la Relevancia del Material");
	}
	
	//Verificar que la Cantidad no este vacía
	if(frm_agregarMaterial.txt_cantidad.value=="" && band==1){
		band = 0;
		alert ("Introducir una Cantidad de Inicio para el Material");
	}

	//Verificar que el Nivel Mínimo no este vacío
	if(frm_agregarMaterial.txt_nivelMinimo.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Nivel Mínimo del Material");					
	}
	
	//Verificar que el Nivel Máximo no este vacío
	if(frm_agregarMaterial.txt_nivelMaximo.value=="" && band==1){
		band = 0;
		alert ("Introducir el Nivel Máximo del Material");
	}

	//Verificar que el Punto de Reorden no este vacío
	if(frm_agregarMaterial.txt_puntoReorden.value=="" && band==1){
		band = 0;
		alert ("Introducir el Punto de Reorden del Material");	
	}

	//Verificar que la Línea del Artículo no este vacía
	if(frm_agregarMaterial.cmb_lineaArticulo.value==""  && frm_agregarMaterial.hdn_lineaArticulo.value=="" && band==1){		
		band = 0;
		alert ("Introducir la Línea del Material");
	}

	//Verificar que la Unidad de Medida no este vacía
	if(frm_agregarMaterial.cmb_unidadMedida.value=="" && frm_agregarMaterial.hdn_unidadMedida.value=="" && band==1){
		band = 0;			
		alert ("Introducir una Unidad de Medida para el Material");
	}

	//Verificar que el Grupo no este vacío
	if(frm_agregarMaterial.cmb_grupo.value=="" && frm_agregarMaterial.hdn_grupo.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Grupo al que Pertenece el Material");
	}
	
	//Verificar que haya sido seleccionado un Proveedor
	if(frm_agregarMaterial.cmb_proveedor.value=="" && band==1){
		band = 0;	
		alert ("Seleccionar el Proveedor del Material");
	}	

	//Verificar que la Ubicacion no este vacío
	if(frm_agregarMaterial.txt_ubicacion.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Ubicación del Material");
	}

	//Verificar que el Factor de Conversión no este vacía
	if(frm_agregarMaterial.txt_factor.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Factor de Conversión del Material");
	}

	//Verificar que la Unidad de Despacho no este vacío
	if(frm_agregarMaterial.txt_unidadDespacho.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Unidad de Despacho del Material");
	}
	
	//Verificar que el Costo de la Unidad no este vacío
	if(frm_agregarMaterial.txt_costoUnidad.value=="" && band==1){
		band = 0;
		alert ("Introducir el Costo Unitario del Material");	
	}
	

	if(band==1)
		return true;
	else
		return false;				
}


/*Esta función verifica que el dato proporcionado sea un numero valido y que a su vez este sea mayor que 0*/
function validarEntero(valor,campo){ 
	var cond = true;
	//Comprobar si es un valor numérico 
	if (isNaN(valor)) { 			
		//Numero invalido
		alert ("El Dato: '"+valor+"' es Incorrecto, Solo se Aceptan Numeros");
		cond = false;
	}
	//Comproba que el numero sea mayor que 0
	if(cond){
		if(valor<=0){
			//Numero invalido
			alert(campo+" Debe Ser Mayor a 0")
			cond = false;
		}
	}	
	return cond;
}


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


//Esta funcion valida que una imagen sea valida, tomando en cuenta el tamaño de 1 Kb hasta 10Mb
function validarImagen(campo,bandera) { 
	//Verificar que el campo tenga foto agregada, de lo contrario no hacer la validacion
	if (campo.value!=""){
		//Creamos un elemento DIV
		div = document.createElement("DIV"); 
		//Le damos la propiedad hidden al DIV
		div.style.visibility = "hidden"; 
		//Le asignamos la propiedad scroll al Div para que no se ajuste al tamaño del formulario
		div.overflow="scroll";
		//Le asignamos el ancho y largo al div creado
		div.width="10";
		div.height="10";
				
		//Creamos un elemento IMG
		img = document.createElement("IMG"); 
		//Asignamos a nuestro elemento recien creado la imagen que queremos sea evaluada, esta viene en el valor del campo que se pasa como parametro a la funcion
		img.src = campo.value; 
		//Asignamos un ID al elemento IMG
		img.id = "fotoFinal"; 
		//Ocultamos IMG a fin de que no se muestre en la pagina o formulario, Si el archivo subido no es una imagen, el tamaño asignadop sera de 0, lo cual lo hace una imagen invalida
		img.style.visibility = "hidden"; 
		
		//Le asignamos al div que creamos al inicio, la imagen que acabamos de cargar a fin de poder evaluarla
		div.appendChild(img);
		//Definimos la variable que indicara el tamaño de la Imágen
		var tam=0;
		//Cargamos en un setTimeout la imagen medida en Kb para que a los 500 milisegundos revise el tamaño de la misma, es en un setTimeout para que permite cargar y revisar
		setTimeout("tam=Math.round(div.lastChild.fileSize/1024);",700);
		//Despues de haber obtenido el tamaño de la imagen, la quitamos del div que creamos, para esto damos un espacio de 100 milisegundos
		setTimeout("div.removeChild(div.lastChild);",800);
		//Una vez que ya obtuvimos el tamaño de la imagen comparamos que sea mayor a 0 y menor a 10240000, si se cumple, 
		//al elemento bandera que le pasamos a la funcion le asigna el valor de SI, este elemento bandera es un elemento tipo hidden en el formulario a fin de poder hacer validacion
		//en caso que el tamaño no se cumpla, muestra una alerta de Imágen no válida y al elemento bandera le asigna el valor NO
		setTimeout("if(tam>0&&tam<10240000){ document.getElementById('"+bandera+"').value='si'; return true}; else {alert('Introducir una Imágen Válida'); document.getElementById('"+bandera+"').value='no'; return false;}",900);	
	}
	else
		document.getElementById(bandera).value="si";
}


/******************Funciones para pedir datos, activar y desactivar elementos en el formulario de Agregar Material*****************************/
function obtenerLineaArticulo(){		
	var linea = prompt("¿Nombre de la Nueva Línea para el Material?","Nombre de la Línea...");	
	if(linea!=null && linea!="Nombre de la Línea..." && linea!=""){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_lineaArticulo").value = linea;
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no los pueda modificar 			
		document.getElementById("cmb_lineaArticulo").disabled = true;
		document.getElementById("ckb_lineaArticulo").disabled = true;						
		//Asignar el valor de la línea obtenida al elemento Hidden para enviar el nuevo dato a la BD			
		document.getElementById("hdn_lineaArticulo").value = linea;
	}
	else
		document.getElementById("ckb_lineaArticulo").checked = false;
}	
function obtenerUnidadMedia(){
	var unidad = prompt("¿Cuál es la Nueva Unidad de Medida?","Unidad de Medida...");
	if(unidad!=null && unidad!="Unidad de Medida..." && unidad!=""){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_unidadMedida").value = unidad;
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no lso pueda modificar 			
		document.getElementById("cmb_unidadMedida").disabled = true;
		document.getElementById("ckb_unidadMedida").disabled = true;						
		//Asignar el valor de la unidad obtenida al elemento Hidden para enviar el nuevo dato a la BD			
		document.getElementById("hdn_unidadMedida").value = unidad;
	}
	else
		document.getElementById("ckb_unidadMedida").checked = false;
}
function obtenerGrupo(){
	var grupo = prompt("¿Nombre del Nuevo Grupo para el Material?","Nombre del Grupo...");
	if(grupo!=null && grupo!="Nombre del Grupo..." && grupo!=""){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		grupo = grupo.toUpperCase();
		if(grupo!="PLANTA"){
			document.getElementById("txt_grupo").value = grupo;
			//Deshabilitar el ComboBox y el CheckBox para que el usuario no lso pueda modificar 			
			document.getElementById("cmb_grupo").disabled = true;
			document.getElementById("ckb_grupo").disabled = true;						
			//Asignar el valor de la grupo obtenida al elemento Hidden para enviar el nuevo dato a la BD			
			document.getElementById("hdn_grupo").value = grupo;
		}
		else{
			alert(grupo + " es un grupo Administrado por Gerencia Técnica. No se puede crear Grupo");
		}
	}
	else
		document.getElementById("ckb_grupo").checked = false;
}
function hablitarElementos(){
	//Cuando el usuario de clic en el boton Limpiar se activarán los ComboBox y los CheckBox de Línea del Artículo, Unidad de Medida y Grupo
	document.getElementById("cmb_lineaArticulo").disabled = false;
	document.getElementById("ckb_lineaArticulo").disabled = false;
	document.getElementById("cmb_unidadMedida").disabled = false;
	document.getElementById("ckb_unidadMedida").disabled = false;
	document.getElementById("cmb_grupo").disabled = false;
	document.getElementById("ckb_grupo").disabled = false;
	document.getElementById("error").style.visibility = "hidden";
	
	//Habilitar los elementos ReadOnly en caso de que tengan esa propiedad asignada
	document.getElementById("txt_cantidad").readOnly = false;
	document.getElementById("txt_nivelMinimo").readOnly = false;
	document.getElementById("txt_nivelMaximo").readOnly = false;
	document.getElementById("txt_puntoReorden").readOnly = false;
	
	//Colocar el valor de 'si' a la variable de hdn_matEspecial para dar otra validacion a dicho material.
	document.getElementById("hdn_matEspecial").value = "no";
}		
/******************Funciones para pedir datos, activar y desactivar elementos en el formulario de Agregar Material*****************************/


/*****************************************************************************************************************************************************************************************/
/**************************************************************************ELIMINAR MATERIAL**********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Validar que el formulario de Seleccionar Material a Eliminar no este vacío*/
function valFormEliminar(frm_eliminar){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	//Validar que los campos no se encuentren vacíos
	var cond = verContFormEliminar(frm_eliminar);
	if(cond){
		//Verificar que la Existencia sea diferente de 0
		if(frm_eliminar.hdn_existencia.value!=0){
			alert ("Para Eliminar el Material su Existencia Debe Ser 0");
			res = 0;
		}
	}
	else{
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función verifica que haya sido seleccionada una Categoría y un Material en el formulario de Seleccionar Material a Eliminar*/
function verContFormEliminar(frm_eliminar){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero que la Clave no este vacia
	if(frm_eliminar.hdn_clave.value==""){
		band = 0;		
		alert ("Seleccionar una Categoría y Después un Material para Mostrar su Existencia");
	}	
	
	//Emitir el resultado en base al análisis realizado
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función verifica que el formulario de Buscar Material a Eliminar no este vacío*/
function valFormBuscar(frm_buscar){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	if(frm_buscar.hdn_param.value==""){
		band = 0;
		alert ("Seleccionar un Parámetro de Búsqueda");
	}
	else{
		if(frm_buscar.cmb_datoBuscar.value==""){
			band = 0;
			alert ("Seleccionar Dato Mediante el Cual se Realizará la Búsqueda");
		}
	}
	
	//Emitir el resultado en base al análisis realizado
	if(band==1)
		return true;
	else
		return false;
		
}


/*Esta función verifica que sea seleccionado un material en la tabla de resultados de busqueda de materiales a eliminar y verifica si la existencia del material seleccionado es igual a 0*/
function valFormEliminar2(frm_eliminar2){
	//Si el valor de la variable "res" se mantiene en 0, entonces el formulario no paso el proceso de validacion
	var res = 0;
	var pos;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminar2.rdb_clave.checked){
		//Separar la existencia de la clave del material para verificar si es diferente de 0
		var clave = frm_eliminar2.rdb_clave.value;
		var partes = clave.split("-");
		if(parseInt(partes[1])!=0){
			alert ("Para Eliminar el Material su Existencia Debe Ser 0");
			res = 0;
		}
		else{
			res = 1;
			//Reescribir la clave original en el RadioButton
			frm_eliminar2.rdb_clave.value = partes[0];
		}
	}
	else{
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		for(i=0;i<frm_eliminar2.rdb_clave.length;i++){
			if(frm_eliminar2.rdb_clave[i].checked){
				res = 1;
				pos = i;
			}
		}
	
		
		if(res==0)
			alert("Seleccionar un Material para Eliminar");
		else{
			//Separar la existencia de la clave del material para verificar si es diferente de 0
			var clave = frm_eliminar2.rdb_clave[pos].value;
			var partes = clave.split("-");
			if(parseInt(partes[1])!=0){
				alert ("Para Eliminar el Material su Existencia Debe Ser 0");
				res = 0;
			}
			else{
				res = 1;
				//Reescribir la clave original en el RadioButton
				frm_eliminar2.rdb_clave[pos].value = partes[0];
			}
		}
		
		
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*Esta funcion pedira confirmación al usuario para eliminar un material en el caso de que exista*/
function valFormEliminarXClave(frm_eliminarXClave){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var res=1;
	if(frm_eliminarXClave.txt_clave.value==""){
		res = 0;
		alert ("Ingresar una Clave de Material");
	}	
	
	//Verificar si existe el material y solicitar al usuario autorizacion para borrarlo
	if(frm_eliminarXClave.hdn_nomMaterial.value!=""){
		if(!confirm("Esta seguro que Desea Eliminar el Material "+frm_eliminarXClave.hdn_nomMaterial.value)){
			res=0;	
		}
		
	}
	
	//Vaciar la caja de texto oculta para que no se muestre el mensaje de Notificacion para el usuario 2 veces
	frm_eliminarXClave.hdn_nomMaterial.value = "";
	
	
	if(res==1)
		return true;
	else
		return false; 
}


/*****************************************************************************************************************************************************************************************/
/**************************************************************************CONSULTAR MATERIAL*********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida que sea seleccionada un Categoría y un Material en el formulario de Consultar Material por Artículo*/
function valFormConsultarMaterial(frm_consultarMaterial){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;		
	
	//Validar que una categoría haya sido seleccionda
	if(frm_consultarMaterial.hdn_categoria.value==""){
		band = 0;
		alert ("Seleccionar una Categoría");
	}
	
	else{
		//Validar que un material haya sido seleccionado
		if(frm_consultarMaterial.cmb_material.value==""){
			band = 0;
			alert ("Seleccionar un Material");
		}		
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función valida que sea seleccionada una Categoría en el formulario de Consultar Material por Categoría*/
function valFormConsultarCategoria(frm_consultarCategoria){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;
	
	if(frm_consultarCategoria.cmb_lineaArticulo.value==""){
		band = 0;
		alert ("Seleccionar una Categoría");
	}
	
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función valida que sea seleccionado el parámetro de búsqueda y el dato por el cual se realizará la búsqueda*/
function valFormConsultarMixta(frm_consultarMixta){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;
	
	if(frm_consultarMixta.hdn_param.value==""){
		band = 0;
		alert ("Seleccionar un Parámetro");
	}
	else{
		if(frm_consultarMixta.cmb_param2.value==""){
			band = 0;
			alert ("Seleccionar una Opción");
		}
	}
	
	
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función valida que sea seleccionada la clave del Material a buscar*/
function valFormConsultarClave(frm_consultarClave){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;
	
	if(frm_consultarClave.txt_clave.value==""){
		band = 0;
		alert ("Escribir una Clave");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/***************************************************************************MODIFICAR MATERIAL********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función verifica que sea seleccionada una categoría y después un material para ser modificado en el formulario de Modificar Material por Artículo*/
function valFormElegirParams(frm_elegirParams){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;	
	//Validar que una categoría haya sido seleccionda
	if(frm_elegirParams.hdn_categoria.value==""){
		band = 0;
		alert ("Seleccionar una Categoría");
	}
	
	else{
		//Validar que un material haya sido seleccionado
		if(frm_elegirParams.cmb_material.value==""){
			band = 0;
			alert ("Seleccionar un Material");
		}		
	}
	
	if(band==1)
		return true;
	else
		return false;
}

	
/*Esta funcion indica que valores deben ser considerados para los movimientos que seran registrados en los materiales*/
function definirNivelesMovModificar(comboRelevancia){
	//Si se selecciona STOCK, Activar y vacias las cajas de texto de Punto de Reorden, Nivel Maximo, Nivel Minimo
	if(comboRelevancia.value=="STOCK"){
		document.getElementById("txt_nivelMinimo").value = 1; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = 1; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = 1; document.getElementById("txt_puntoReorden").readOnly = false;
	}//Si se selecciona la opcion de Consignacion o Lento Movimiento, colocar 0 a las cajas de texto y colocarlas como ReadOnly
	else if(comboRelevancia.value=="CONSIGNACION" || comboRelevancia.value=="LENTO MOVIMIENTO"){		
		//Colocar valor 0 a los campos y dejarlos como readonly
		document.getElementById("txt_nivelMinimo").value = 0; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = 0; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = 0; document.getElementById("txt_puntoReorden").readOnly = false;		
	}//Si se selecciona la opcion vacia, vaciar y activart los campos
	else if(comboRelevancia.value==""){		
		document.getElementById("txt_nivelMinimo").value = ""; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = ""; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = ""; document.getElementById("txt_puntoReorden").readOnly = false;		
	}
}//Cierre de la funcion definirNivelesMovModificar(comboRelevancia)


/*Este función valida que en el formulario de Modificar Material no se encuentren vacíos los campos obligatorios
  y verifica que los datos numericos contengan numeros validos y mayores a 0 */
function valFormModificarMaterial(frm_modificarMaterial){
	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	//Primero validar que los campos no se encuentren vacios
	var cond = verContFormModificarMaterial(frm_modificarMaterial);
		
	
		
	//Sí todos los materiales fueron proporcionados proceder a validar su contenido
	if(cond){
		if(frm_modificarMaterial.cmb_relevancia.value=="STOCK"){
			//Verificar que los datos numericos del formulario sean numeros: Cantidad, Nivel Mínimo, Nivel Máximo, Punto de Reorden, Costo Unitario y el Factor de Conversión
			//if(validarEntero(frm_modificarMaterial.txt_cantidad.value,"La Cantidad del Material")){
				if(validarEnteroValorCero(frm_modificarMaterial.txt_nivelMinimo.value)){
					if(validarEntero(frm_modificarMaterial.txt_nivelMaximo.value,"El Nivel Máximo del Material")){
						if(validarEnteroValorCero(frm_modificarMaterial.txt_puntoReorden.value)){	
						}else{ res = 0; }
					}else{ res = 0; }
				}else{ res = 0; }
			//}else{ res = 0;	}		
		}//Cierre if(frm_modificarMaterial.cmb_relevancia.value=="STOCK")
	
	
		if(validarEntero(frm_modificarMaterial.txt_costoUnidad.value.replace(/,/g,''),"El Costo Unitario del Material")){
			if(!validarEntero(frm_modificarMaterial.txt_factor.value,"El Factor de Conversión del Material"))
				res = 0;
		}else{ res = 0; }
	
	}else{ res=0; }//Cierre else if(cond)
	
	//Validamos la cantidad solo en caso de que el usuario registrado sea el administrador
	if(frm_modificarMaterial.hdn_administrador.value=="AdminAlmacen"){
		if(validarEntero(frm_modificarMaterial.txt_cantidad.value.replace(/,/g,''),"La Cantidad de Material")){
			if(!validarEntero(frm_modificarMaterial.txt_factor.value,"La Cantidad de Material"))
				res = 0;
			else{ 
				res = 0; 
			}		
		}
		else{ 
			res=0; 
		}
	}
	
	//Si el tipo de Relevancia es igual a STOCK, validar los niveles Maximo, Minimo y Punto de Reorden
	if(frm_modificarMaterial.cmb_relevancia.value=="STOCK"){
		//Obtener los datos numericos de Nivel Minimo, Nivel Maximo y Punto de Reorden
		var minimo = parseInt(frm_modificarMaterial.txt_nivelMinimo.value);
		var maximo = parseInt(frm_modificarMaterial.txt_nivelMaximo.value);
		var reorden = parseInt(frm_modificarMaterial.txt_puntoReorden.value);	
		
		//Validar que el Punto de Reorden no sea igual o menor que el Nivel Mínimo
		if(res==1){
			if(minimo>reorden){
				alert("El Nivel Mínimo no Puede Ser Mayor al Punto de Reorden");
				res = 0;
			}			
		}	
		
		//Validar que el Nivel Máximo no sea igual o menor que el punto de reorden y que no sea igual o menor que el Nivel Mínimo
		if(res==1){
			if(reorden==maximo || reorden>maximo){
				alert("El Punto de Reorden NO Puede Ser Igual o Mayor que el Nivel Máximo");
				res = 0;
			}			
		}
	}
	
	
	
	//Validar que cuando el factor de conversion sea 1, la unidad de medida y la unidad de despacho tienen que ser las mismas
	if(res==1){
		if(frm_modificarMaterial.txt_factor.value==1){
			var unidad_medida;
			if(frm_modificarMaterial.cmb_unidadMedida.disabled==false)
				unidad_medida = frm_modificarMaterial.cmb_unidadMedida.value;
			else
				unidad_medida = frm_modificarMaterial.txt_unidadMedida.value.toUpperCase();
				
			if(unidad_medida!=frm_modificarMaterial.txt_unidadDespacho.value.toUpperCase()){
				alert("Cuando el Factor de Conversión es Igual a 1,\nla Unidad de Medida y la Unidad de Despacho Deben Ser Iguales");
				res = 0;
			}
		}			
	}

	//Verificar que el tamaño de la imagen proporcionada sea el soportado por el Sistema
	if(res==1){
		//Verificar el tamaño de la imagen
		if(document.getElementById("hdn_imgValida").value!="si"){
			alert("Verificar el Formato y Tamaño de la de la Imagen Proporcionada\nTamaño de la Imagen Seleccionada: "+parseInt(document.getElementById("hdn_tamImg").value/1024)+" Kb");
			res = 0;
		}
	}
		
	if(res==1 && frm_modificarMaterial.txt_codigoBarras.value==""){
		alert("Introducir o Escanear el Código de Barras");
		res = 0;
		frm_modificarMaterial.txt_codigoBarras.focus();
	}
	
	if(res==1)
		return true;	
	else
		return false;	
}


/*Esta función verifica que los campos obligatorios del formulario de Modificar Material no esten vacíos*/
function verContFormModificarMaterial(frm_modificarMaterial){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero que la Clave no este vacía
	if(frm_modificarMaterial.txt_clave.value==""){
		band = 0;		
		alert ("Introducir una Clave del Material");
	}
	
	//Verificar que la relevancia sea Proporcionada
	if (frm_modificarMaterial.cmb_relevancia.value=="" && band==1){
		band = 0;	
		alert ("Seleccionar la Relevancia del Material");
	}
			
	//Luego verificar que el Nombre no este vacío
	if(frm_modificarMaterial.txt_nombre.value=="" && band==1){
		band = 0;
		alert ("Introducir un Nombre del Material");
	}
	
	//Verificar que el Nivel Mínimo no este vacío
	if(frm_modificarMaterial.txt_nivelMinimo.value=="" && band==1){
		band = 0;	
		alert ("Introducir un Nivel Mínimo de Material");
	}

	//Verificar que el Nivel Máximo no este vacío
	if(frm_modificarMaterial.txt_nivelMaximo.value=="" && band==1){
		band = 0;	
		alert ("Introducir un Nivel Máximo de Material");
	}

	//Verificar que el Punto de Reorden no este vacío
	if(frm_modificarMaterial.txt_puntoReorden.value=="" && band==1){
		band = 0;	
		alert ("Introducir un  Punto de Reorden de Material");
	}	

	//Verificar que el ComboBox de la Línea del Artículo no este vacía
	if(frm_modificarMaterial.cmb_lineaArticulo.disabled==false && frm_modificarMaterial.cmb_lineaArticulo.value=="" && band==1){
		band = 0;
		alert ("Seleccionar una Línea del Material");
	}

	//Verificar que la Caja de Texto de la Línea del Artículo no este vacía
	if(frm_modificarMaterial.cmb_lineaArticulo.disabled==true && frm_modificarMaterial.txt_lineaArticulo.value=="" && band==1){
		band = 0;
		alert ("Introducir una Línea del Material en la Cuadro de Texo");
	}																																														

	//Verificar que el ComboBox de la Unidad de Medida no este vacía
	if(frm_modificarMaterial.cmb_unidadMedida.disabled==false && frm_modificarMaterial.cmb_unidadMedida.value=="" && band==1){
		band = 0;
		alert ("Seleccionar una Unidad de Medida");
	}

	//Verificar que la Caja de Texto de la Unidad de Medida no este vacía
	if(frm_modificarMaterial.cmb_unidadMedida.disabled==true && frm_modificarMaterial.txt_unidadMedida.value=="" && band==1){
		band = 0;
		alert ("Introducir una Unidad de Medida en el Cuadro de Texto");
	}																																																																											

	//Verificar el ComboBox de grupo no se encuentre vacío
	if(frm_modificarMaterial.cmb_grupo.disabled==false && frm_modificarMaterial.cmb_grupo.value=="" && band==1){
		band = 0;
		alert ("Seleccionar el Grupo al que Pertenece al Material");
	}

	//Verificar la Caja de Texto de grupo no se encuentre vacío
	if(frm_modificarMaterial.cmb_grupo.disabled==true && frm_modificarMaterial.txt_grupo.value=="" && band==1){
		band = 0;
		alert ("Introducir el Grupo al que Pertenece al Material en el Cuadro de Texto");
	}																																																													

	//Verificar que el el campo de Proveedor no se encuentre vacío
	if(frm_modificarMaterial.txt_ubicacion.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Ubicación del Material");
	}

	//Verificar que el el campo de Ubicación no se encuentre vacío
	if(frm_modificarMaterial.txt_factor.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Factor de Conversión del Material");
	}
														
	//Verificar que el el campo del Factor de Conversión no se encuentre vacío
	if(frm_modificarMaterial.txt_unidadDespacho.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Unidad de Despacho del Material");
	}

	//Verificar que el el campo de Unidad de Despacho no se encuentre vacío
	if(frm_modificarMaterial.cmb_proveedor.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Nombre del Proveedor");
	}							
	
	//Verificar que el Costo de la Unidad no este vacío
	if(frm_modificarMaterial.txt_costoUnidad.value=="" && band==1){
		band = 0;
		alert ("Introducir el Costo Unitario del Material");	
	}
	
	//Verificar que el Tipo de Moneda no este vacío
	if(frm_modificarMaterial.txt_moneda.value=="" && band==1){
		band = 0;
		alert ("Seleccionar el tipo de moneda");	
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/******************Funciones para activar y editar el contenido de los elementos en el formulario de Modificar Material*****************************/
function editarLinea(){	
	//Permitir editar el contenido de la caja de texto que contiene la Línea del Artículo
	document.getElementById("txt_lineaArticulo").disabled = false;
	//Deshabilitar el CheckBox que permite al usuario editar el campo que contiene el valor de la Línea del Artículo
	document.getElementById("ckb_editarLinea").disabled = true;	
	//Deshabilitar el ComboBox de la Línea del Artículo
	document.getElementById("cmb_lineaArticulo").disabled = true;
}
function editarUnidad(){
	//Permitir editar el contenido de la caja de texto que contiene la Unidad de Medida
	document.getElementById("txt_unidadMedida").disabled = false;
	//Deshabilitar el CheckBox que permite al usuario editar el campo que contiene el valor de la Unidad de Medida
	document.getElementById("ckb_editarUnidad").disabled = true;
	//Deshabilitar el ComboBox de la Unidad de Medida
	document.getElementById("cmb_unidadMedida").disabled = true;	
}
function editarGrupo(){
	//Permitir editar el contenido de la caja de texto que contiene el Grupo
	document.getElementById("txt_grupo").disabled = false;
	//Desabilitar el CheckBox que permite al usario editar el campo que contiene el valor del Grupo
	document.getElementById("ckb_editarGrupo").disabled = true;
	//Desabilitar el ComboBox del Grupo
	document.getElementById("cmb_grupo").disabled = true;
}
		
function deshabilitarElementos(){
	//Cuando el usuario de clic en el boton Limpiar se desactivarán los CheckBox que permiten editar los campos de Línea del Artículo, la Unidad de Medida y el Grupo
	document.getElementById("txt_lineaArticulo").disabled = true;
	document.getElementById("txt_unidadMedida").disabled = true;
	document.getElementById("txt_grupo").disabled = true;
	//Reactivar los ComboBox
	document.getElementById("cmb_lineaArticulo").disabled = false;
	document.getElementById("cmb_unidadMedida").disabled = false;
	document.getElementById("cmb_grupo").disabled = false;
	//Reactivar los CheckBox
	document.getElementById("ckb_editarLinea").disabled = false;	
	document.getElementById("ckb_editarUnidad").disabled = false;
	document.getElementById("ckb_editarGrupo").disabled = false;
	
}		
/******************Funciones para activar y editar el contenido de los elementos en el formulario de Modificar Material*****************************/

function valFormModificarXClave(frm_modificarXClave){
	//Si la variable permanece en 1, no se activo ningun error
	var band=1;
	
	if(frm_modificarXClave.txt_claveMod.value==""){
		alert("Introducir una Clave de Materiales");
		band = 0;
	}
	
	if(band==1)
		return true;	
	else
		return false;
	
}

/*****************************************************************************************************************************************************************************************/
/****************************************************************************ENTRADA MATERIAL*********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
function validarCantidadEntrada(cantEntrada, cantPedido, nomEntrada){
	if(cantEntrada > cantPedido){
		alert("La cantidad de entrada no debe de ser mayor a la pedida");
		document.getElementById(""+nomEntrada).value=cantPedido;
		document.getElementById(""+nomEntrada).focus();
	}
}

//Funcion aplicada en frm_entradaMaterial.php, esta funcion permite mostrar en el formulario de Entrada, los origenes y los numeros correspondientes a cada opcion
function seleccionarCriterio(origen){
	if(origen!=""){
		//Obtener la referencia del comboBox que será cargado con los datos
		objeto = document.getElementById("cmb_opciones");
		//COMPRA DIRECTA
		if(origen=="compra_directa"){
			//Mostrar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaNumero").style.visibility="hidden";
			//Ocultar la caja de texto requisiciones
			document.getElementById("txt_req").style.visibility="hidden";
			//Ocultar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaCriterio").style.visibility="hidden";
			//Ocultar la etiqueta del Pedido
			document.getElementById("etiquetaCriterio2").style.visibility="hidden";
			//Ocultar el combo de requisiciones
			document.getElementById("cmb_opciones").style.visibility="hidden";
			//Ocultar la caja de texto pedidos
			document.getElementById("txt_pedido").style.visibility="hidden";
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Seleccionar";
			objeto.options[objeto.length-1].value="";
			//Restablecer el valor del elemento Hidden que valida los Pedidos y Requisiciones de Paileria
			document.getElementById("hdn_valPal").value="stock";
		}
		//ORDEN DE COMPRA
		if(origen=="id_orden_compra"){
			//Funcion que carga las Ordenes de compra y pedidos
			cargarDatosEntrada("oc","cmb_opciones");
			//Ocultar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaCriterio").style.visibility="visible";
			//Ocultar el combo de requisiciones
			document.getElementById("cmb_opciones").style.visibility="visible";
			//Mostrar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaNumero").style.visibility="hidden";
			//Mostrar el combo de requisiciones
			document.getElementById("txt_req").style.visibility="hidden";
			//Cambiar la etiqueta de acuerdo al Combo
			document.getElementById("etiquetaCriterio").innerHTML="Seleccionar";
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Seleccionar";
			objeto.options[objeto.length-1].value="";
			document.getElementById("etiquetaCriterio").innerHTML="N&uacute;mero";
		}
		//REQUISICION
		if(origen=="id_requisicion"){
			//Ocultar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaCriterio").style.visibility="visible";
			//Ocultar la etiqueta del Pedido
			document.getElementById("etiquetaCriterio2").style.visibility="hidden";
			//Ocultar el combo de requisiciones
			document.getElementById("cmb_opciones").style.visibility="visible";
			//Ocultar la caja de texto del pedido
			document.getElementById("txt_pedido").style.visibility="hidden";
			//Mostrar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaNumero").style.visibility="hidden";
			//Mostrar el combo de requisiciones
			document.getElementById("txt_req").style.visibility="hidden";
			//Cambiar la etiqueta de acuerdo al Combo
			document.getElementById("etiquetaCriterio").innerHTML="Seleccionar";
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Seleccionar";
			objeto.options[objeto.length-1].value="";
			//Cambiar la etiqueta de acuerdo al Combo
			document.getElementById("etiquetaCriterio").innerHTML="Departamento";
			//Crear el arreglo con los departamentos
			var deptos=new Array("ALMACEN","DESARROLLO","TOPOGRAFIA","GERENCIA TECNICA","LABORATORIO","PRODUCCION","MANTENIMIENTO","SEGURIDAD INDUSTRIAL","RECURSOS HUMANOS","ASEGURAMIENTO CALIDAD","PAILERIA","MANTENIMIENTO ELECTRICO","CLINICA");
			var deptosValor=new Array("almacen","desarrollo","topografia","gerenciatecnica","laboratorio","produccion","mantenimiento","seguridadindustrial","recursoshumanos","aseguramientodecalidad","paileria","mttoE","clinica");
			//Agregar los departamentos
			for(var i=0;i<deptos.length;i++){												
				//Obtener cada uno de los datos que serán cargados en el Combo como Etiqueta
				valor = deptos[i];
				//Obtener el Value de cada Depto
				valorDepto = deptosValor[i];
				//Aumentar en 1 el tamaño del comboBox
				objeto.length++;
				//Agregar el dato que sera mostrado
				objeto.options[objeto.length-1].text=valor;
				//Agregar el valor dela atributo value
				objeto.options[objeto.length-1].value=valorDepto;
				//Colocarl el valor de la Id en el Atributo Title
				objeto.options[objeto.length-1].title=valor;
			}
			//Mostrar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaNumero").style.visibility="visible";
			//Mostrar el combo de requisiciones
			document.getElementById("txt_req").style.visibility="visible";
			//Vaciar la caja de requisicion
			document.getElementById("txt_req").value="";
		}
		//PEDIDO
		if(origen=="pedido"){
			//Funcion que carga las Ordenes de compra y pedidos
			//cargarDatosEntrada("pedido","cmb_opciones");
			//Ocultar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaCriterio").style.visibility="hidden";
			//Ocultar la etiqueta del pedido
			document.getElementById("etiquetaCriterio2").style.visibility="visible";
			//Ocultar el combo de requisiciones
			document.getElementById("cmb_opciones").style.visibility="hidden";
			//Ocultar la caja de pedido
			document.getElementById("txt_pedido").style.visibility="visible";
			//Vaciar la caja del pedido
			document.getElementById("txt_pedido").value="";
			//Mostrar la etiqueta del Numero de Requisicion
			document.getElementById("etiquetaNumero").style.visibility="hidden";
			//Mostrar el combo de requisiciones
			document.getElementById("txt_req").style.visibility="hidden";
			//Cambiar la etiqueta de acuerdo al Combo
			document.getElementById("etiquetaCriterio").innerHTML="Seleccionar";
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text="Seleccionar";
			objeto.options[objeto.length-1].value="";
			document.getElementById("etiquetaCriterio").innerHTML="N&uacute;mero";
		}
	}
	else{
		//Ocultar la etiqueta del Numero de Requisicion
		document.getElementById("etiquetaCriterio").style.visibility="hidden";
		//Ocultar la etiqueta del pedido
		document.getElementById("etiquetaCriterio2").style.visibility="hidden";
		//Ocultar el combo de requisiciones
		document.getElementById("cmb_opciones").style.visibility="hidden";
		//Ocultar la caja de pedido
		document.getElementById("txt_pedido").style.visibility="hidden";
		//Mostrar la etiqueta del Numero de Requisicion
		document.getElementById("etiquetaNumero").style.visibility="hidden";
		//Ocultar la caja de requisiciones
		document.getElementById("txt_req").style.visibility="hidden";
		//Cambiar la etiqueta de acuerdo al Combo
		document.getElementById("etiquetaCriterio").innerHTML="Seleccionar";
		//Vaciar el comboBox Antes de llenarlo
		objeto.length = 0;
		//Agregar el Primer Elemento Vacio
		objeto.length++;
		objeto.options[objeto.length-1].text="Seleccionar";
		objeto.options[objeto.length-1].value="";
	}
}

/*Esta funcion es una adecuacion al formulario de EntradaMaterial en frm_entradaMaterial.php, como tal se encarga de validar que segun los criterios seleccionados se puedan
validar o no, los combos del formulario*/
function valFormEntradaMaterialV2(frm_cargarInfo){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	
	//Validar el primer combo
	if(frm_cargarInfo.cmb_param.value==""){
		alert("Seleccionar el Orígen");
		res=0;
	}
	
	//Validar en caso de que se haya seleccionado la Orden de Compra
	if(frm_cargarInfo.cmb_param.value=="id_orden_compra"){
		if(frm_cargarInfo.cmb_opciones.value==""){//Mensaje de Orden de Compra
			alert("Seleccionar la Órden de Compra");
			res=0;
		}
	}
	
	//Validar en caso de que se haya seleccionado la Requisicion
	if(frm_cargarInfo.cmb_param.value=="id_requisicion"){
		if(frm_cargarInfo.cmb_opciones.value==""){//Mensaje de departamento
			alert("Seleccionar el Departamento");
			res=0;
		}
		if(res==1 && frm_cargarInfo.txt_req.value==""){//Mensaje de Requisicion
			alert("Seleccionar la Requisición");
			res=0;
		}
	}
	
	//Validar en caso de que se haya seleccionado el Pedido
	if(frm_cargarInfo.cmb_param.value=="pedido"){
		if(frm_cargarInfo.txt_pedido.value==""){//Mensaje de Pedido
			alert("Seleccionar el Pedido");
			res=0;
		}
	}

	if(res==1){
		//Verificar que el Material sea de Entrada/Salida para Paileria
		if(document.getElementById("hdn_valPal").value=="e_s"){
			//Preguntar si el Material es para Stock o simplemente tendra Entrada por Salida
			if(confirm("Se detectó un Pedido/Requisición de Paileria.\nSi los Materiales son de Stock, presionar Aceptar.\nEn caso contrario, si los Materiales NO son de Stock presionar Cancelar"))
				//Si el material es de Stock cambiar la variable que permite mostrar un formulario u otro
				document.getElementById("hdn_valPal").value="stock";
		}
		return true;
	}
	else
		return false;
}

/*Esta funcion indica que las Requisiciones o Pedidos de Paileria serán agregados o NO al Stock de materiales, o tendrán E/S directa*/
function verificarOpcion(origen,numero){
	document.getElementById("hdn_valPal").value="stock";
	if(origen=="pedido"){
		var indice = numero.selectedIndex;
		var num=numero.options[indice].text;
		var valorReal=num.split(" - ");
		if(valorReal[1].substring(0,3)=="PAI")
			document.getElementById("hdn_valPal").value="e_s";
	}
	if(origen=="id_requisicion"){
		if(numero.value=="paileria")
			document.getElementById("hdn_valPal").value="e_s";
	}
}

/*Esta función valida que los datos del Formulario Seleccionar Material para Registrar en la Entrada no esten vacios y verifica que la Cantida y el COsto sean numero validos 
  y mayores que 0*/
function valFormEntradaDetalle(frm_entradaDetalle){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	if(frm_entradaDetalle.hdn_validar.value=="1"){	
		if(frm_entradaDetalle.txt_clave.value==""){
			alert("Seleccionar una Categoría y Después un Material o Ingresar una Clave Válida de Material para Registrar su Entrada");
			res = 0;
			frm_entradaDetalle.txt_clave.focus();
		}
		
		if(res==1 && frm_entradaDetalle.txt_existencia.value==""){ 
			alert("Ingresar una Clave Válida de Material para Registrar su Entrada");
			res = 0;
			frm_entradaDetalle.txt_clave.focus();
		}
		
		if(res==1 && frm_entradaDetalle.txt_unidadMedida.value==""){ 
			alert("Ingresar una Clave Válida de Material para Registrar su Entrada");
			res = 0;
			frm_entradaDetalle.txt_clave.focus();
		}
		
		if(res==1 && frm_entradaDetalle.txt_cantEntrada.value==""){ 
			alert("Introducir Cantidad de Entrada");
			res = 0;
			frm_entradaDetalle.txt_cantEntrada.focus();
		}			
		
		if(res==1 && frm_entradaDetalle.txt_costoUnidad.value==""){ 
			alert("Introducir Costo Unitario de Entrada");
			res = 0;
			frm_entradaDetalle.txt_costoUnidad.focus();
		}
		
		if(res==1 && frm_entradaDetalle.cmb_tipoMoneda.value==""){ 
			alert("Introducir el tipo de Moneda");
			res = 0;
			frm_entradaDetalle.cmb_tipoMoneda.focus();
		}
	
		if(res==1 && !validarEntero(frm_entradaDetalle.txt_cantEntrada.value,"La Cantidad de Entrada del Material")){
			res = 0;
		}
		
		if(res==1 && !validarEntero(frm_entradaDetalle.txt_costoUnidad.value.replace(/,/g,''),"El Costo Unitario de Entrada del Material")){
			res = 0;
		}
	}

	if(res==1)
		return true;	
	else
		return false;	
}//Fin de valFormEntradaDetalle

/*Funcion que valida que se seleccionen materiales y se ingrese su cantidad de entrada, a través de un Pedido de Paileria*/
function valFormMatPedPai(form){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	//Variable para saber si al menos un material fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var cant=form.cant_ckbs.value;
	var ctrl=0;
	do{
		var check="ckb_mat"+(ctrl+1).toString();
		if (document.getElementById(check).checked){
			status=1;
			if (document.getElementById("txt_cant"+(ctrl+1).toString()).value==""){
				alert("Ingresar la Cantidad de Entrada de:\n\""+document.getElementById("txt_material"+(ctrl+1).toString()).value+"\"");
				res=0;
				break;
			}
		}
		ctrl++;
	}while(ctrl<(cant-1));
	//Verificar el valor de la variable status, si vale 0, no se selecciono ningun material
	if (status==0){
		alert("Seleccionar al Menos un Material");
		res=0;
	}
	//Verificar el valor de res, si vale 1, no hay fallas
	if(res==1)
		return true;	
	else
		return false;	
}//Fin de function valFormMatPedPai(frm_selecMateriales)

/*Esta función verifica que el Proveedor y el No. de Factura sean proporcionados en el Formulario de Complementar la Información de la Entrada de Material*/
function verContFormDatosEntrada(frm_datosEntrada){	
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
			
	//Verificar que sea proporcionado el Proveedor de los Materiales que estan entrando al Almacen
	if(frm_datosEntrada.cmb_provedor.value==""){
		band = 0;		
		alert ("Seleccionar el Proveedor de los Materiales");
	}
	
	//Verificar que el No. de Factura haya sido proporcionado
	if(frm_datosEntrada.txt_noFactura.value=="" && band==1){
		band = 0;		
		alert ("Introducir No. de Factura del Material");
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion se utiliza en Entrada de Material, Salida de Materia, Generar Requisicion y Generar Orden de Compra*/
function valFormTerminar(frm_terminar,msj){
	if(frm_terminar.hdn_materialAgregado.value == "si"){
		return true;
	}
	else{
		alert("Registrar al Menos un Material en la "+msj+" para Continuar");
		return false;
	}		
}


/*Esta función valida los materiales que provienen de una Requisición u Orden de Compra para ser agregados al arreglo datosEntrada 
  y posteriormente agregados a la Entrada del Almacén*/
function valFormSelecMateriales(nombreForm){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para manejar el mensaje de validación satisfactoria
	var msg = 0;
	//Variable para saber si al menos un material fue seleccionado de los que estan en el Catálogo
	var status = 0;
	//Variable para saber si al menos un material fue seleccionado de los que NO están en el catálogo
	var statusNR = 0;
	//Variable para almacenar la cantidad de registros
	var cantidad = nombreForm.cant_ckbs.value;
	//Si no hay Registros ya en la BD, dejar el status en 1
	if(cantidad==0)
		status=1;
	//Variable para almacenar la cantidad de registros que no estan en la BD
	var cantidadNuevos = nombreForm.cant_ckbsNR.value;
	//Si no hay Registros nuevos para la BD, dejar el statusNR en 1
	if(cantidadNuevos==0)
		statusNR=1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicacion relacionada a el
	var idCheckBox = "";
	var idTxtCantidad = "";
	var idTxtCostoUnit = "";
	var idHdnNombre = "";
	
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Recorrido de los Elementos check que SI estan en la BD
	while(ctrl<=cantidad){
		sufijo="";
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_mat"+sufijo+ctrl.toString();
		//Verificar que la cantidad y la aplicacion del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id del Caja de Texto Oculta de Nombre
			idHdnNombre = "hdn_nombre"+sufijo+ctrl.toString();
			var nombre = document.getElementById(idHdnNombre).value;
			//Crear el id de la Caja de Texto de Cantidad
			idTxtCantidad = "txt_cant"+sufijo+ctrl.toString();
			//Crear el id de la Caja de Texto de Aplicacion
			idTxtCostoUnit = "txt_cost"+sufijo+ctrl.toString();
			
			//Validar que la cantidad no este vacía
			if(document.getElementById(idTxtCantidad).value==""){				
				alert("Ingresar Cantidad Para el Material: "+nombre);
				msg = 1;
			}
			else{
				//Validar que la cantidad sea un numero entero valido
				if(validarEntero(document.getElementById(idTxtCantidad).value,"La Cantidad de Entrada del Material "+nombre)){				
					//Validar que el costo no este vacio
					if(document.getElementById(idTxtCostoUnit).value==""){
						msg = 1;
						alert("Ingresar el Costo Unitario Para el Material: "+nombre);
					}
					else{
						//Validad que el costo sea un numero valido
						if(!validarEntero(document.getElementById(idTxtCostoUnit).value.replace(/,/g,''),"El Costo Unitario de Entrada del Material "+nombre))
							msg = 1;
					}
				}
				else{
					msg = 1;
				}
			}
		}
		ctrl++;
	}//Fin del While

	/**********************/
	//Variable para controlar la cantidad de registros
	ctrl=1;
	//Recorrido de los Elementos check que NO estan en la BD
	while(ctrl<=cantidadNuevos){
		sufijo="NR";
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_mat"+sufijo+ctrl.toString();
		//Verificar que la cantidad y la aplicacion del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			statusNR = 1;
			//Crear el id del Caja de Texto Oculta de Nombre
			idHdnNombre = "hdn_nombre"+sufijo+ctrl.toString();
			var nombre = document.getElementById(idHdnNombre).value;
			//Crear el id de la Caja de Texto de Cantidad
			idTxtCantidad = "txt_cant"+sufijo+ctrl.toString();
			//Crear el id de la Caja de Texto de Aplicacion
			idTxtCostoUnit = "txt_cost"+sufijo+ctrl.toString();
			
			//Validar que la cantidad no este vacía
			if(document.getElementById(idTxtCantidad).value==""){				
				alert("Ingresar Cantidad Para el Material: "+nombre);
				msg = 1;
			}
			else{
				//Validar que la cantidad sea un numero entero valido
				if(validarEntero(document.getElementById(idTxtCantidad).value,"La Cantidad de Entrada del Material "+nombre)){				
					//Validar que el costo no este vacio
					if(document.getElementById(idTxtCostoUnit).value==""){
						msg = 1;
						alert("Ingresar el Costo Unitario Para el Material: "+nombre);
					}
					else{
						//Validad que el costo sea un numero valido
						if(!validarEntero(document.getElementById(idTxtCostoUnit).value.replace(/,/g,''),"El Costo Unitario de Entrada del Material "+nombre))
							msg = 1;
					}
				}
				else{
					msg = 1;
				}
			}
		}
		ctrl++;
	}//Fin del While
	/**********************/
	
	//Verificar que al menos un material haya sido seleccionado, siempre y cuando existan materiales dentro del Stock
	if(status==0 && document.getElementById("cant_ckbs").value>0){
		alert("Seleccionar al Menos un Material");
		res = 0;
	}
	//Verificar que al menos un material haya sido seleccionado, siempre y cuando existan materiales dentro del Stock
	if(statusNR==0 && document.getElementById("cant_ckbsNR").value>0 && res==1){
		alert("Seleccionar al Menos un Material");
		res = 0;
	}
	//Verificar que el ultimo proceso de Seleccion se haya pasado adecuadamente
	if(res==1){
		//Si hubo algun mensaje de que falta ingresar un datos, no se cumplio con el proceso de validacion 
		if(msg==1)
			res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;		
}

function valFormSelecMaterialesPedido(form){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para saber si al menos un material fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var cant=form.cant_ckbs.value;
	var ctrl=0;
	do{
		ctrl++;
		//Crear variable para los elementos checkados
		var check="ckb_mat"+(ctrl).toString();
		if (document.getElementById(check).checked){
			//Crear variables de validacion
			var hdnValido="hdn_validarDatoMaterial"+(ctrl).toString();
			var matPedido="cmb_materialP"+(ctrl).toString();
			var cantP="txt_cant"+(ctrl).toString();
			var costo="txt_cost"+(ctrl).toString();
			var nomMat="hdn_nombre"+(ctrl).toString();
			//Activar la variable de elementos seleccionados
			status=1;
			//Validar que se haya ingresado/seleccionado un material
			if(document.getElementById(matPedido).value==""){
				alert("Ingresar/Seleccionar el Material");
				res=0;
				//Asegurar que el hidden de validacion no cambie a resultado valido
				document.getElementById(hdnValido).value="NO";
				//Poner el foco en la caja de Texto seleccionada
				document.getElementById(matPedido).focus();
				break;
			}
			//Si la caja de Texto contiene informacion, verificar el hidden asociado a ella, para ver si el material es valido
			if(document.getElementById(hdnValido).value=="NO"){
				alert("Es Necesario Seleccionar el Material Correspondiente en el Stock, en caso que el Material NO exista, seleccionar la Opción 'NO HAY COINCIDENCIAS'");
				document.getElementById(matPedido).value="";
				res=0;
				document.getElementById(matPedido).focus();
				break;
			}
			//Verificar la cantidad a ingresar
			if (document.getElementById(cantP).value==""){
				alert("Ingresar la Cantidad de Entrada");
				res=0;
				document.getElementById(cantP).focus();
				break;
			}
			//Verificar el costo unitario de los materiales
			if (document.getElementById(costo).value=="0.00"){
				if(document.getElementById(matPedido).value!="" && document.getElementById(matPedido).value!="MATERIAL NUEVO")
					var nombre=document.getElementById(matPedido).value;
				else
					var nombre=document.getElementById(nomMat).value;
				alert("Ingresar el Costo Unitario de "+nombre);
				document.getElementById(costo).focus();
				res=0;
				break;
			}
		}
	}while(ctrl<(cant-1));
	/*
alert(document.getElementById(hdnValido).name+" "+document.getElementById(hdnValido).value+"\n"+document.getElementById(matPedido).name+" "+document.getElementById(matPedido).value+"\n"+document.getElementById(cantP).name+" "+document.getElementById(cantP).value+"\n"+document.getElementById(costo).name+" "+document.getElementById(costo).value+"\n"+document.getElementById(nomMat).name+" "+document.getElementById(nomMat).value);
	*/
	if (status==0){
		alert("Seleccionar al Menos un Material");
		res=0;
	}

	if(res==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/*****************************************************************************SALIDA MATERIAL*********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida que los datos de formulario Seleccionar Material para Registrar en la Salida no esten vacios y verifica que la Cantidad de Salida sea un numero valido y mayor a 0*/
function valFormSalidaDetalle(frm_salidaDetalle){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	if(frm_salidaDetalle.hdn_validar.value=="1"){
		if(frm_salidaDetalle.txt_clave.value==""){
			alert("Seleccionar una Categoría y Después un Material o Ingresar una Clave Válida de Material para Registrar su Salida");
			res = 0;
		}
		
		if(res==1 && frm_salidaDetalle.txt_cantSalida.value==""){ 
			alert("Introducir Cantidad de Salida");
			res = 0;
			frm_salidaDetalle.txt_cantSalida.focus();
		}			
	
		if(res==1 && !validarEntero(frm_salidaDetalle.txt_cantSalida.value,"La Cantidad de Salida del Material")){
			res = 0;
		}
		
		//Aqui inicia la validación para que la cantidad sea menor a la existencia		
		if(parseFloat(frm_salidaDetalle.txt_cantSalida.value) > parseFloat(frm_salidaDetalle.txt_existencia.value)){	
			alert("La Existencia del Material no Alcanza a Cubrir la Demanda");
			res = 0;
		}
		
		if(res==1 && frm_salidaDetalle.cmb_idEquipo.value==""){
			alert("Seleccionar un equipo o la opción NO APLICA");
			res = 0;
		}
		
		if(res==1 && frm_salidaDetalle.cmb_tipoMoneda.value==""){
			alert("Seleccionar el tipo de moneda");
			res = 0;
		}
	}
	
	if(frm_salidaDetalle.hdn_validar.value=="0"){
		for(var i=1; i<=frm_salidaDetalle.num_mat.value; i++){
			if(res==1 && document.getElementById("cmb_catMat"+i).value==""){
				alert("Seleccionar una categoria para el material");
				res = 0;
				document.getElementById("cmb_catMat"+i).focus();
			}
		}
	}

	if(res==1)
		return true;	
	else
		return false;
}

/*Funcion que verifica si la categoria de Material que se selecciona para dar Salida es de Equipo de Seguridad*/
function verificarEqSeg(categoria,cantMat){
	//Buscar la palabra SEG en el nombre de categoria
	var pos = categoria.indexOf('SEG');
	//Si pos es diferente de -1, indicar que puede ser Equipo de Seguridad
	if(pos>=0){
		if(cantMat>=1){
			//Dejar la respuesta en manos del usuario
			if(confirm("Se detectó una Categoría que es similar a 'Equipo de Seguridad'.\nPresione Aceptar para registrar la Salida como Equipo de Seguridad. \n\n**Nota: Los Materiales Registrados se Perderán"))
				location.href="frm_equipoSeguridad.php";
		}
		else{
			//Dejar la respuesta en manos del usuario
			if(confirm("Se detectó una Categoría que es similar a 'Equipo de Seguridad'.\nPresione Aceptar para registrar la Salida como Equipo de Seguridad."))
				location.href="frm_equipoSeguridad.php";
		}
	}
}//Fin de function verificarEqSeg(categoria,cantMat)

/*Funcion que valida el formulario de Salida de Materiales usando el código de Barras*/
function valFormSalidaBC(form){
	if(form.hdn_validar.value==1){
		if(form.txt_codBar.value==""){
			//alert("Ingresar o Escanear el Código de Barras");
			return false;
		}
		else{
			if(form.hdn_costoUnidad.value==""){
				return false;
			}
			else{
				if(form.cmb_tipoMoneda.value=="")
					return false;
			}
		}
	}
}//function valFormSalidaBC(form)

/*Esta función valida que los datos complementario de la Salida de Material no esten vacíos*/
function verContFormDatosSalida(frm_datosSalida){	
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	//Verificar primero que el proveedor no este vacío
	/*if(frm_datosSalida.txt_codBarTrabajador.value==""){
		band = 0;		
		alert ("Introducir el Codigo del Trabajador que Solicita el Material");
	}
	else{*/
	//Verificar primero que el proveedor no este vacío
	if(frm_datosSalida.txt_deptoSolicitante.value==""){
		band = 0;		
		alert ("Introducir el Departamento que Solicita el Material");
	}
	else{
		//Verificar que el nombre del Solicitante haya sido proporcionado
		if(frm_datosSalida.txt_solicitante.value==""){
			band = 0;		
			alert ("Introducir Nombre del Solicitante del Material");
		}		
		else{
			//Verificar que el control de costos haya sido proporcionado
			if(frm_datosSalida.cmb_con_cos.value==""){
				band = 0;		
				alert ("Introduce el Control de Costos");
			}
			else{
				//Verificar que la cuenta haya sido proporcionado
				if(frm_datosSalida.cmb_cuenta.value==""){
					band = 0;		
					alert ("Introduce la cuenta");
				}
				else{
					//Verificar que la subcuenta haya sido proporcionado
					if(frm_datosSalida.cmb_subcuenta.value=="" && frm_datosSalida.hdn_subcuenta.value==""){
						band = 0;		
						alert ("Seleccionar una subcuenta valida");
					}
					else{
						//Verificar que el Turno haya sido proporcionado
						if(frm_datosSalida.cmb_turno.value==""){
							band = 0;		
							alert ("Selecciona el Turno en el que Será Utilizado el Material");
						}
						else{
							//Verificar que el No. del Vale sea proporcionado
							if(frm_datosSalida.txt_noVale.value==""){
								band = 0;		
								alert ("Introducir el No. de Vale");
							}
							/*else{
								if(frm_datosSalida.cmb_tipoMoneda.value==""){
									band = 0;		
									alert ("Introducir el Tipo de Moneda");
								}
							}*///Else Moneda
						}//Else Turno
					}//Else Subcuenta
				}//Else Cuenta
			}//Else Destino
		}//Else Solicitante 		
	}//Else Depto. Solicitante
	//}//Else Codigo Trabajador
	
	if(band==1)
		return true;
	else
		return false;
}

//Funcion que aplica en el formulario ubicado en el archivo frm_salidaMaterial2.php
//Lo que hace es redibujar en el SPAN datosSolicitante, un combo vacio de nueva cuenta
function restablecerComboSalida(combo){
	document.getElementById("datosSolicitante").innerHTML="<select name=\"txt_solicitante\" id=\"txt_solicitante\" class=\"combo_box\"><option value=\"\">Solicitante</option></select>";
}

//Funcion comun al formulario de E/S
function limpiarCamposEntrada2(comboCategoria,opc){
	document.getElementById("txt_clave").value="";
	document.getElementById("txt_existencia").value="";
	document.getElementById("txt_unidadMedida").value="";
	if(opc=="S")
		document.getElementById("txt_costoUnidad").value="";
}

function permiteCB(elEvento,focoSiguiente){
	//Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);
	if(codigoCaracter=="13"){
		document.getElementById(focoSiguiente).focus();
		document.getElementById(focoSiguiente).onfocus();
		return false;
	}
}

/*****************************************************************************************************************************************************************************************/
/****************************************************************************GENERAR REQUISICION******************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida que sea selecionada una Categoría y un Material, asi como la Cantida y la Aplicación para ser agregados a la Requisición*/
function valFormGenerarRequisicion(frm_generarRequisicion){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	if(frm_generarRequisicion.cmb_material.value=="" && frm_generarRequisicion.txt_clave.value==""){
		alert("Seleccionar una Categoría y Después un Material o Ingresar Clave del Material para Registrar Requisicion");
		res = 0;
	}
	else{
		if(frm_generarRequisicion.txt_cantReq.value==""){
			alert("Introducir Cantidad del Material a Solicitar");
			res = 0;
		}
		else{
			if(!validarEntero(frm_generarRequisicion.txt_cantReq.value,"La Cantidad del Material")){
				res = 0;
			}
			else{
				if(frm_generarRequisicion.txt_aplicacionReq.value==""){
					alert("Introducir la Aplicación del Material que Esta Siendo Solicitado");
					res = 0;
				}
			}
		}
	}

	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta funcion valida los datos del material agregado a la requisición, cuando éste no está registrado en el Catálodo de Almacén*/
function  valFormMaterialesRequisicion(frm_MaterialesRequisicion){
//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	if(frm_MaterialesRequisicion.txt_matReq.value==""){
		alert("Introducir Nombre del Material");
		res = 0;
	}
	else{
		if(frm_MaterialesRequisicion.txt_unidadMedida.value==""){
			alert("Introducir la Unidad de Medida");
			res = 0;
		}
		else{
			if(frm_MaterialesRequisicion.txt_cantReq2.value==""){
				alert("Introducir la Cantidad del Material a Solicitar");
				res = 0;
			}	
			else{
				if(!validarEntero(frm_MaterialesRequisicion.txt_cantReq2.value,"La Cantidad del Material")){
					res = 0;
				}
				else{
					if(frm_MaterialesRequisicion.txt_aplicacionReq2.value==""){
						alert("Introducir la Aplicación del Material que Esta Siendo Solicitado");
						res = 0;
					}
				}
			}
		}
	}
	
	if(res==1&&frm_MaterialesRequisicion.hdn_claveValida.value=="no"){
		alert("La Clave escrita pertenece a otro Material");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función valida la información complementaria de la Requisición que esta siendo generada*/
function valFormInformacionRequisicion(frm_InformacionRequisicion){
//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;	
		
	
	if(frm_InformacionRequisicion.hdn_materialAgregado.value == "si"){						
		if(frm_InformacionRequisicion.txa_justificacionReq.value==""){
			alert("Introducir la Justificación del Material que Esta Siendo Solicitado");
			res = 0;
		}
		else{
			if(frm_InformacionRequisicion.txt_areaSolicitante.value==""){
				alert("Introducir el Área que Solicita el Material");
				res = 0;
			}
			else{
				if(frm_InformacionRequisicion.txt_solicitanteReq.value==""){
					alert("Introducir Nombre de la Persona que Solicita el Mateial");
					res = 0;
				}
				else{
					if(frm_InformacionRequisicion.cmb_prioridad.value==""){
						alert("Seleccionar la Prioridad");
						res = 0;
					}
					else{
						if(frm_InformacionRequisicion.txt_elaboradorReq.value==""){
						alert("Introducir el Nombre de la Persona que Elabora la Requisición");
						res = 0;
						}
					}
				}
			}
		}
	}
	else{
		alert("Al Menos se Debe Agregar un Material al Registro de la Requisicion");
		res = 0;
	}	
		
	
	if(res==1)
		return true;
	else
		return false;
}


/*****************************************************************************************************************************************************************************************/
/***************************************************************************GENERAR ORDEN DE COMPRA***************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida que sea seleccionado un Material del Catálodo de Alamcén y después la cantidad del mismo, asi como que la cantidad sea mayor que 0*/
function valFormGenerarOC(frm_generarOrdenC){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	if(frm_generarOrdenC.cmb_codigoMF.value==""){
		alert("Seleccionar Material para Agregar a la Orden de Compra");
		res = 0;
	}
	else{
		if(frm_generarOrdenC.txt_cantidad.value==""){
			alert("Introducir Cantidad del Material");
			res = 0;
		}
		else{
			if(!validarEntero(frm_generarOrdenC.txt_cantidad.value,"La Cantidad del Material")){
			res = 0;
			}
		}
	}	
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta fucnión valida que sean agregado todos los datos del Material, cuando éste no esta registrado en el Catálogo de Minera Fresnillo*/
function  valFormMaterialesOC(frm_MaterialesOC){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	if(frm_MaterialesOC.txt_clave.value==""){
		alert("Introducir Clave del Material");
		res = 0;
	}
	else{
		if(frm_MaterialesOC.txt_descripcion.value==""){
			alert("Introducir Nombre del Material");
			res = 0;
		}
		else{
			if(frm_MaterialesOC.txt_cantidad2.value==""){
				alert("Introducir Cantidad del Material");
				res = 0;
			}	
			else{
				if(!validarEntero(frm_MaterialesOC.txt_cantidad2.value,"La Cantidad del Material")){
				res = 0;
				}
			}
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función valida que la información complementaria de la Orden de Compra sea proporcionada*/
function valFormInformacionOC(frm_InformacionOC){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		

	if(frm_InformacionOC.hdn_materialOC.value == "si"){	
		if(frm_InformacionOC.txt_areaSolicitante.value==""){
			alert("Introducir el Nombre del Área que Esta Solicitando el Material");
			res = 0;
		}
		else{
			if(frm_InformacionOC.txt_solicitanteOC.value==""){
				alert("Introducir el Nombre del Solicitante");
				res = 0;
			}
		}	
	}
	else{
		alert("Al Menos se Debe Agregar un Material al Registro de la Orden de Compra");
		res = 0;	
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*****************************************************************************************************************************************************************************************/
/*****************************************************************************AGREGAR EQUIVALENCIAS***************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida los datos del formulario para Agregar Equivalencias*/
function verContFormEquivalencias(frm_agregarEquivalencias){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar que haya sido selecionado un material
	if(frm_agregarEquivalencias.cmb_material.value==""){
		band = 0;		
		alert ("Selecciona una Categoría y Después un Material ");
	}
	else{
		//Verificar que el campo de Clave Equivalencia no este vacio
		if(frm_agregarEquivalencias.txt_claveEquiv.value==""){
			band = 0;
			alert ("Introducir Clave de Equivalencia");
		}
		else{
			//Verificar que el campo Nombre no este vacio
			if(frm_agregarEquivalencias.txt_nombre.value==""){
				band = 0;
				alert ("Introducir el nombre del Material Equivalente");
			}				
			else{
				//Verificar que el campo Proveedor no este vacio
				if(frm_agregarEquivalencias.cmb_proveedor.value==""){
					band = 0;	
					alert ("Seleccionar un Proveedor");
				}																
			}//Else Nombre
		}//else Clave
	}//Else Nombre del Material
	
	if(band==1)
		return true;
	else
		return false;
}


/*****************************************************************************************************************************************************************************************/
/****************************************************************************ELIMINAR EQUIVALENCIAS***************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Funcion que valida el formulario que muestra los resultados de la búsqueda de material que tiene equivalencias registradas*/
function valFormEliminarEquiv(frm_eliminarEquiv){
	//Si el valor de la variable "res" se mantiene en 0, entonces el formulario no paso el proceso de validación
	var res = 0;
	
	//Verificar que un elemento haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarEquiv.rdb_clave.checked){
		res = 1;
	}
	else{
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		for(i=0;i<frm_eliminarEquiv.rdb_clave.length;i++){
			if(frm_eliminarEquiv.rdb_clave[i].checked){
				res = 1;	
			}
		}			
	}
	
	if(res==1)
		return true;
	else{
		alert("Seleccionar un Material Equivalente Para Eliminar");						
		return false;	
	}							
}


/*****************************************************************************************************************************************************************************************/
/*******************************************************************************CONSULTAR ALERTAS*****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida los datos de la Requisicioón u Orden de Compra al momento de generar cada documento*/
function valFormVerMateriales(frm_verMateriales){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para manejar el mensaje de validación satisfactoria
	var msg = 0;
	//Variable para saber si al menos un material fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = frm_verMateriales.cant_ckbs.value;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicación relacionada a el
	var idCheckBox = "";
	var idTxtCantidad = "";
	var idHdnNombre = "";
	
	while(ctrl<cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_select"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id del Caja de Texto Oculta de Nombre
			idHdnNombre = "hdn_nombre"+ctrl.toString();
			var nombre = document.getElementById(idHdnNombre).value;
			//Crear el id de la Caja de Texto de Cantidad
			idTxtCantidad = "txt_cantidad"+ctrl.toString();
			
			if(document.getElementById(idTxtCantidad).value==""){				
				alert("Ingresar Cantidad Para el Material: "+nombre);
				msg = 1;
			}
			else{
				//Validar que la cantidad sea un numero entero valido
				if(validarEntero(document.getElementById(idTxtCantidad).value,"La Cantidad del Material "+nombre)){				
					var asd;
				}
				else{
					msg = 1;
				}
			}
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un material haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
	if(status==1){
		//Si hubo algun mensaje de que falta ingresar un datos, no se cumplio con el proceso de validacion 
		if(msg==1)
			res = 0;
	}
	else{
		alert("Seleccionar al Menos un Material");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;		
}


/*****************************************************************************************************************************************************************************************/
/*******************************************************************************EQUIPO SEGURIDAD******************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida que sean proporcionados los datos del Trabajador al que se le esta entregando el material, así como que al menos sea seleccionado un equipo de seguridad*/
function valFormSeguridad(frm_verEquipoSeguridad){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para saber si al menos un material fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable que controla la cantidad de checkbox que hay en la pagina
	var cantidad=0;
	//Variable que obtiene los elementos por tipo especifico del formulario
	var elemento=0;
	
	//Revisar si el nombre del trabajador ha sido seleccionado
	if (frm_verEquipoSeguridad.txt_codigo.value==""){
		alert("Seleccionar un Trabajador");
		res=0;
	}

	//Revisar si el vale ha sido introducido
	if (document.getElementById("txt_noVale").value==""&&res==1){
		alert("Especificar el Número del Vale de Salida");
		res=0;
	}
	
	//Revisar si el destino ha sido introducido
	if (document.getElementById("txt_destino").value==""&&res==1){
		alert("Especificar el Destino al que va Dirigido el Equipo");
		res=0;
	}

	//Revisar si si ha seleccionado el Turno
	if (frm_verEquipoSeguridad.cmb_turno.value==""&&res==1){
		alert ("Selecciona el Turno en el que será Utilizado el Material");
		res=0;
	}
	
	//Revisa si hubo vale insertado para poder proceder a revisar los elementos checkbox revisados
	if (res==1){
		//Funcion For para obtener todos los elementos que son Checkbox en el formulario
		for(var i=0;i<document.frm_verEquipoSeguridad.elements.length;i++){
			//Variable
			elemento=document.frm_verEquipoSeguridad.elements[i];
			if (elemento.type=="checkbox" && document.frm_verEquipoSeguridad.elements[i].value!="Todo" && document.frm_verEquipoSeguridad.elements[i].name.substring(0,4)!="ckb_"){
				idCheckBox=document.frm_verEquipoSeguridad.elements[i].id;
				//Verificar que de los checkbox al menos uno este seleccionado
				if(document.getElementById(idCheckBox).checked){
					status = 1;
				}
			}
		}
		//Verificar que al menos un material haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
		if(status==1)
			res = 1;
		else{
			alert("Seleccionar al Menos un Material");
			res = 0;
		}
	}
	
	for(var i=1; i<frm_verEquipoSeguridad.num_mat.value; i++){
		if(document.getElementById("ckb"+i).checked){
			if(res==1 && document.getElementById("cmb_catMat"+i).value==""){
				alert("Seleccionar una categoria para el material");
				res = 0;
				document.getElementById("cmb_catMat"+i).focus();
			}
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Estan función activa todos lo CheckBox del Equipo de Seguridad*/
function checarTodos(chkbox){
	for(var i=0;i<document.frm_verEquipoSeguridad.elements.length;i++){
		//Variable
		elemento=document.frm_verEquipoSeguridad.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=chkbox.checked;
	}	
}


/*Esta funcion desactiva el CheckBox de Seleccionar Todo cuando un CheckBox del equipo de seguridad es desseleccionado*/
function desSeleccionar(checkbox){
	if (!checkbox.checked){
		document.getElementById("ckbTodo").checked=false;
		if(checkbox.name.substr(0,3)=="ckb"&&checkbox.name.substr(0,4)!="ckb_")
			document.getElementById(checkbox.name.substr(0,3)+"_"+checkbox.name.substr(3)).checked=false;
	}
	else{
		if(checkbox.name.substr(0,4)=="ckb_")
			document.getElementById(checkbox.name.substr(0,3)+checkbox.name.substr(4)).checked=true;
	}
}

//Funcion para validar que se haya ingresado una foto
function valFormFoto(frm_agregarFoto){
	var band=1;
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarFoto.file_documento.value==""){
		alert ("Introducir Fotografía");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}
/*******************************************************************************************************************************************************************/
/******************************************************************FORMULARIO CONSULTAR REQUISICIONES***************************************************************/
/*******************************************************************************************************************************************************************/
//Funcion que permite habilitar la area de texto de la descripcion
function activarBusqReq(checkbox){
	if(checkbox.checked){
		//Activar los campos de Buscar Por y Notas
		document.getElementById("cmb_buscarPor").disabled = false;
		document.getElementById("txa_notas").readOnly = false;
	}
	else{
		//Vaciar los datos de los campos de Buscar Por y Notas
		document.getElementById("cmb_buscarPor").value = "";
		document.getElementById("cmb_buscarPor").disabled = true;
		document.getElementById("txa_notas").value = "";
		document.getElementById("txa_notas").readOnly = true;
	}
}

/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormFechasReq(fechaIni, fechaFin){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=fechaIni.substr(0,2);
	var iniMes=fechaIni.substr(3,2);
	var iniAnio=fechaIni.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=fechaFin.substr(0,2);
	var finMes=fechaFin.substr(3,2);
	var finAnio=fechaFin.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}
/*****************************************************************************************************************************************************************************************/
/*****************************************************************************FORMULARIO CONSULTAR REPORTES*******************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta funcion valida que las fechas elegidas en los Reportes sean correctas*/
function valFormFechas(formulario){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=formulario.txt_fechaInicio.value.substr(0,2);
	var iniMes=formulario.txt_fechaInicio.value.substr(3,2);
	var iniAnio=formulario.txt_fechaInicio.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=formulario.txt_fechaCierre.value.substr(0,2);
	var finMes=formulario.txt_fechaCierre.value.substr(3,2);
	var finAnio=formulario.txt_fechaCierre.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/************************************************************************EDITAR REGISTROS***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta funcion valida que los datos ingresados en el formulario de editar registro de entrada no esten vacios*/
function valFormEditarRegistroEntrada(frm_editarRegistroEntrada){
	//Si el valor se mantiene en 1 el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que el dato de costo y cantidad no esten vacios
	if(frm_editarRegistroEntrada.txt_cantEntrada.value==""){
		alert("Ingresar la Cantidad de Entrada del Material");
		band = 0;
	}
	else{
		if(frm_editarRegistroEntrada.txt_costoUnidad.value==""){
			alert("Ingresar el Costo del Material");
			band = 0;
		}
		else{
			if(frm_editarRegistroEntrada.cmb_tipoMoneda.value==""){
				alert("Seleccionar el tipo de moneda del registro");
				band = 0;
			}
		}
	}
	
	//Validar que sean numeros validos
	if(band==1){
		if(validarEntero(frm_editarRegistroEntrada.txt_cantEntrada.value.replace(/,/g,''),"La Cantidad de Entrada del Material")){
			if(!validarEntero(frm_editarRegistroEntrada.txt_costoUnidad.value.replace(/,/g,''),"El Costo Unitario de Entrada del Material"))
				band = 0;	
		}
		else
			band = 0;																																  
	}
	
	if(band==1)
		return true;
	else
		return false;
	
}


/*Esta funcion valida que los datos ingresados en el formulario de editar registro de salida no esten vacios*/
function valFormEditarRegistroSalida(frm_editarRegistroSalida){
	//Si el valor se mantiene en 1 el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que el dato de cantidad no esten vacio
	if(frm_editarRegistroSalida.txt_cantSalida.value==""){
		alert("Ingresar la Cantidad de Salida del Material");
		band = 0;
	}	
		
	//Validar que sean numeros validos
	if(band==1){
		if(!validarEntero(frm_editarRegistroSalida.txt_cantSalida.value.replace(/,/g,''),"La Cantidad de Salida del Material"))
			band = 0;
	}
	
	//Verificar que la cantidad de salida no sea mayor a la exitencia del material
	if(band==1){
		if(parseInt(frm_editarRegistroSalida.txt_cantSalida.value) > parseInt(frm_editarRegistroSalida.txt_existencia.value)){
			alert("La Cantidad de Salida es Mayor a la Existencia del Material");
			band = 0;
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
	
}


/*Esta funcion valida que los datos ingresados en el formulario de editar registro de requisicion no esten vacios*/
function valFormEditarRegistroRequisicion(frm_editarRegistroRequisicion){
	//Si el valor se mantiene en 1 el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que el dato de costo y cantidad no esten vacios
	if(frm_editarRegistroRequisicion.txt_cantReq.value==""){
		alert("Ingresar la Cantidad que Será Solicitada del Material");
		band = 0;
	}
	else{
		if(frm_editarRegistroRequisicion.txt_aplicacion.value==""){
			alert("Ingresar la Aplicación del Material");
			band = 0;
		}
	}
	
	
	//Validar que sean numeros validos
	if(band==1){
		if(!validarEntero(frm_editarRegistroRequisicion.txt_cantReq.value.replace(/,/g,''),"La Cantidad de Requisición del Material"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;
	
}


/*Esta funcion valida que los datos ingresados en el formulario de editar registro de orden de compra no esten vacios*/
function valFormEditarRegistroOC(frm_editarRegistroOC){
	//Si el valor se mantiene en 1 el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que el dato de cantidad no esten vacio
	if(frm_editarRegistroOC.txt_cantOC.value==""){
		alert("Ingresar la Cantidad que Será Solicitada del Material");
		band = 0;
	}	
		
	//Validar que sean numeros validos
	if(band==1){
		if(!validarEntero(frm_editarRegistroOC.txt_cantOC.value.replace(/,/g,''),"La Cantidad Solicitada del Material"))
			band = 0;
	}
		
	
	if(band==1)
		return true;
	else
		return false;
	
}
/********************************************************************************************************************************************************************/
/************************************************************************REGRESAR MATERIAL***************************************************************************/
/*********************************************************************************************************************************************************************/
/*Esta funcion Activa y desactiva el comboBox en las pagina de registro del material que es regresado por el material*/
function activarCampos (campo, noRegistro){
	if (campo.checked){
		document.getElementById("cmb_estado" + noRegistro).disabled=false;
		document.getElementById("txt_observaciones" + noRegistro).disabled=false;
	}
	else{
		document.getElementById("cmb_estado" + noRegistro).value="";
		document.getElementById("cmb_estado" + noRegistro).disabled=true;
		document.getElementById("txt_observaciones" + noRegistro).value="";
		document.getElementById("txt_observaciones" + noRegistro).disabled=true;
	}
}


/*Esta función valida los datos en el registro de los materiales regresados por los empleados*/
function valFormEquipo(frm_verEquipoSeguridad){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para manejar el mensaje de validación satisfactoria
	var msg = 0;
	//Variable para saber si al menos un equipo fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = parseInt(document.getElementById("hdn_cant").value) - 1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicación relacionada a el
	var idCheckBox = "";
	var idCmbestado = "";
	var idHdnNombre = "";
	while(ctrl<=cantidad){
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_equipo"+ctrl.toString();		
		//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id del Caja de Texto Oculta de Nombre
			idHdnNombre = "hdn_nombre"+ctrl.toString();
			var nombre = document.getElementById(idHdnNombre).value;
			//Crear el id del combo del turno
			idCmbestado=document.getElementById("cmb_estado"+ctrl.toString());
			if(idCmbestado.value==""){				
				alert("Seleccionar Estado del Equipo "+ nombre);
				msg = 1;
			}
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
	if(status==1){
		//Si hubo algun mensaje de que falta ingresar un datos, no se cumplio con el proceso de validacion 
		if(msg==1)
			res = 0;
	}
	else{
		alert("Seleccionar al Menos un Equipo");
		res = 0;
	}
	if(res==1)
		return true;
	else
		return false;		
}

/********************************************************************************************************************************************************************/
/****************************************************************CONSULTAR REQUISICIONES EXTERNAS********************************************************************/
/********************************************************************************************************************************************************************/
/*Esta funcion Activa y desactiva el comboBox en las pagina de registro del material que es regresado por el material*/
function valFormConsultarRequisicionesExternas(frm_consultarRequisicionesExternas){
	if (frm_consultarRequisicionesExternas.cmb_departamento.value==""){
		alert("Seleccionar un Departamento");
		return false;
	}
	else
		return true;
}

function activarMatAlertReq(num){
	var chk_mat = document.getElementById("ckb_select"+num);
	var equipo = document.getElementById("txt_aplicacion"+num);
	var cc = document.getElementById("cmb_con_cos"+num);
	var cuenta = document.getElementById("cmb_cuenta"+num);
	var subcuenta = document.getElementById("cmb_subcuenta"+num);
	
	if(chk_mat.checked){
		equipo.disabled=false;
		cc.disabled=false;
		cuenta.disabled=false;
		subcuenta.disabled=false;
	} else {
		equipo.disabled=true;
		cc.disabled=true;
		cuenta.disabled=true;
		subcuenta.disabled=true;
	}
}
