
	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_material;

	var caja_nombre_material;
	var clave_material;
	var nombre_material;

	function validarMaterialRequi(clave_mat, txt_mat, nom_mat){
		caja_nombre_material = txt_mat;
		clave_material = clave_mat;
		nombre_material = nom_mat;

		if (clave_material != "") {
			var url = "../../includes/ajax/verificarMatRequi.php?material_clave="+clave_material+"&material_nombre="+nombre_material;
			url += "&nocache=" + Math.random();
			cargaContenidoMaterial(url, "GET", cargarDatosMaterial);
		}
	}

	function cargaContenidoMaterial(url, metodo, funcion) {
		peticion_material = iniciar_xhr_req();
		if(peticion_material){
			peticion_material.onreadystatechange = funcion;
			peticion_material.open(metodo, url, true);
			peticion_material.send(null);
		}
	}

	function cargarDatosMaterial(){				
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_material.readyState==READY_STATE_COMPLETE){
 			if (peticion_material.status==200) {
 				var respuesta = peticion_material.responseXML;
 				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
 				if (existe == "false") {
 					caja_nombre_material.value = "";
 				}
 			}
 		}
	}

	function iniciar_xhr_req() {		
	 	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
	 		return new XMLHttpRequest();
	 	}else if (window.ActiveXObject) { // IE
	 		return new ActiveXObject("Microsoft.XMLHTTP");
	 	}
	}