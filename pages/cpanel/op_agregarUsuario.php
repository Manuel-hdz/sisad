<?php
	/**
	  * Nombre del M�dulo: Panel de Control
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 12/Agosto/2011
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de AgregarUsuarios del Sistema
	**/


	//Esta funcion Agrega el Usuario en la Base de Datos
	function agregarUsuario(){
		//Variable que indicara si el registro fue exitoso o se presentaron errores
		$bandera=0;
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Recopilar los datos del Usuario a ingresar
		$userName=$_POST["txt_usuario"];
		$clave=$_POST["txt_pass"];
		$tipoUsuario=$_POST["cmb_tipo"];
		$depto=$_POST["cmb_depto"];
		$trabajador=strtoupper($_POST["txt_nombre"]);

		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_usuarios");
		$num_dep = obtenerNumeroDepto($depto);
		//Creamos la sentencia SQL para insertar los datos en Usuarios
		$stm_sql="INSERT INTO usuarios(usuario,clave,tipo_usuario,depto,no_depto)VALUES('$userName',AES_ENCRYPT('$clave',128),'$tipoUsuario','$depto',$num_dep)";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if($rs)
			$bandera=1;
		else
			$bandera=mysql_error();
			
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		
		//Si la bandera vale 1, el usuario se registro correctamente, registrar los permisos en cerrados para el usuario nuevo
		if ($bandera==1){
			registrarPermisosInicio($userName,$depto);
			registrarCredencial($userName,$trabajador);
		}
		//Retornamos el valor de la bandera y mostrar un mensaje de exito o error segun corresponda
		return $bandera;
	}//Fin de la funcion para agrega al Nuevo Usuario
	
	//Funcion que muestra los Usuarios Registrados
	function mostrarUsuarios(){
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		
		//Crear la sentencia para mostrar los usuarios con claves encriptadas y desencriptadas
		$stm_sql = "SELECT depto,tipo_usuario,usuario,nombre FROM usuarios JOIN credenciales ON usuarios_usuario=usuario WHERE usuario!='CPanel' ORDER BY depto,tipo_usuario,nombre";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
										
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%'>      			
				<tr>
				    <td colspan='18' align='center' class='titulo_etiqueta'>USUARIOS REGISTRADOS</td>
  				</tr>
					<tr>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
						<td class='nombres_columnas' align='center'>TIPO DE USUARIO</td>
						<td class='nombres_columnas' align='center'>USUARIO</td>
						<td class='nombres_columnas' align='center'>ASIGNADO A</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>$datos[depto]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_usuario]</td>
						<td class='$nom_clase' align='center'>$datos[usuario]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
					";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion que muestra los usuarios registrados
	
	/*Funcion que registra al usuario con los permisos correspondientes SIN ACCESO a ningun �rea por Default*/
	function registrarPermisosInicio($usuario,$depto){
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		//Verificar el valor del departamento para adecuarlo a los parametros indicados segun la tabla de Permisos
		switch ($depto){
			case "Almacen":
				$depto="almacen";
				break;
			case "Compras";
				$depto="compras";
				break;
			case "GerenciaTecnica":
				$depto="gerencia";
				break;
			case "RecursosHumanos":
				$depto="recursos";
				break;
			case "Produccion":
				$depto="produccion";
				break;
			case "Calidad":
				$depto="calidad";
				break;
			case "Desarrollo":
				$depto="desarrollo";
				break;
			case "MttoConcreto":
				$depto="mantenimiento";
				break;
			case "MttoMina":
				$depto="mantenimiento";
				break;
			case "Topografia":
				$depto="topografia";
				break;
			case "Laboratorio":
				$depto="laboratorio";
				break;
			case "Lampisteria":
				$depto="lampisteria";
				break;
			case "Seguridad":
				$depto="seguridad";
				break;
			case "SeguridadAmbiental":
				$depto="seguridad";
				break;
			case "Panel":
				$depto="panel";
				break;
			case "DireccionGral":
				$depto="gerencia";
				break;
			case "Comaro":
				$depto="comaro";
				break;
			case "Sistemas":
				$depto="sistemas";
				break;
			case "SupervisionDes":
				$depto="sup_des";
				break;
		}
		//Crear y ejecutar la sentencia que trae las paginas y las secciones que corresponden al m�dulo seleccionado
		$rs=mysql_query("SELECT DISTINCT (seccion),area FROM permisos WHERE modulo='$depto' ORDER BY seccion;");
		if ($datos=mysql_fetch_array($rs)){
			do{
				//Crear por cada pagina y area una sentencia de insercion de datos SIN permisos otorgados
				$stm_sql="INSERT INTO permisos(usuarios_usuario,seccion,estatus,modulo,area) VALUES('$usuario','$datos[seccion]','0','$depto','$datos[area]')";
				$res=mysql_query($stm_sql);
			}while($datos=mysql_fetch_array($rs));
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Fin de registrarPermisosInicio($usuario,$depto)
	
	//Funcion que registra que usuario pertenece a que Trabajador
	function registrarCredencial($userName,$trabajador){
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		//Creamos la sentencia SQL para insertar los datos en Usuarios
		$stm_sql="INSERT INTO credenciales(usuarios_usuario,nombre)VALUES('$userName','$trabajador');";
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
	}
	
	function obtenerNumeroDepto($depto){
		$num_depto = 0;
		
		$con = conecta("bd_usuarios");
		
		$sql = "SELECT  `no_depto` 
				FROM  `usuarios` 
				WHERE  `depto` =  '$depto'";
		$rs = mysql_query($sql);
		
		
		if($datos = mysql_fetch_array($rs)){
			$num_depto = $datos['no_depto'];
		} else {
			$sql_nuevo = "SELECT MAX(`no_depto`)+1 AS num
						  FROM  `usuarios`";
			$rs_nuevo = mysql_query($sql_nuevo);
			$datos_nuevo = mysql_fetch_array($rs_nuevo);
			$num_depto = $datos_nuevo['num'];
		}
		return $num_depto;
	}
?>