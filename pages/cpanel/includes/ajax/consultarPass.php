<?php
	/**
	  * Nombre del M�dulo: Panel de Control                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas                            
	  * Fecha: 03/Mayo/2012
	  * Descripci�n: Este archivo se encarga de consultar la BD en busqueda de un empleado que haya sido dado de baja con anterioridad
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
	/**   C�digo en: pages\alm\includes\validarPassword.php                                   
      **/	
	 
	//Obtener la contrase�a mandada
	if(isset($_GET["pass"])){//Validar una clave en la BD
		//Recuperar los datos a buscar de la URL
		$pass = $_GET["pass"];
		//Conectarse a la BD
		$conn = conecta("bd_usuarios");
		//Crear la Sentencia SQL para verificar si hay entrada Registrada
		$sql_stm = "SELECT AES_DECRYPT(clave,128) AS clave FROM usuarios WHERE usuario='CPanel'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Recuperar la clave de la BD
		$datos=mysql_fetch_array($rs);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Comparar los resultados obtenidos 
		if($pass==$datos["clave"]){
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
				</existe>"
			);
		}
		else{
			//Crear XML de error
			echo utf8_encode("
			<existe>
				<valor>false</valor>
			</existe>");
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre else if(isset($_GET["datoBusq"]))
	
	//Obtener el RFC del Empleado y verificar su antiguedad y la posible existencia de un prestamo para el Empleado Seleccionado		
	if(isset($_GET["user"])){//Validar una clave en la BD
		//Recuperar los datos a buscar de la URL
		$user = $_GET["user"];
		//Conectarse a la BD
		$conn = conecta("bd_usuarios");
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
			$tabla=dibujarTabla($user);
			if($tabla!="")
				$tabla=str_replace("<","�",$tabla);
			else
				$tabla="^^";
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<tabla>$tabla</tabla>
				</existe>"
			);
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre else if(isset($_GET["datoBusq"]))
	
	function dibujarTabla($user){
		//Sentencia SQL
		$stm_sql = "SELECT depto,tipo_usuario,usuario,nombre,activo,AES_DECRYPT(clave,128) AS clave FROM usuarios JOIN credenciales ON usuarios_usuario=usuario WHERE usuario='$user'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			$tabla="				
				<table cellpadding='5' width='80%'>
					<caption class='titulo_etiqueta'>Datos del Usuario $user</caption>      			
					<tr>
						<td colspan='2'></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
						<td class='renglon_gris' align='center'>$datos[depto]</td>
					</tr>
					<tr>
						<td colspan='2'></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>TIPO DE USUARIO</td>
						<td class='renglon_gris' align='center'>$datos[tipo_usuario]</td>
					</tr>
					<tr>
						<td colspan='2'></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>USUARIO</td>
						<td class='renglon_gris' align='center'>$datos[usuario]</td>
					</tr>
					<tr>
						<td colspan='2'></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CONTRASE�A</td>
						<td class='renglon_gris' align='center'>$datos[clave]</td>
					</tr>
					<tr>
						<td colspan='2'></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>ASIGNADO A</td>
						<td class='renglon_gris' align='center'>$datos[nombre]</td>
					</tr>
					<tr>
						<td colspan='2'></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>HABILITADO</td>
						<td class='renglon_gris' align='center'>$datos[activo]</td>
					</tr>
				</table>	
				";
			return $tabla;
		}
		else
			return "";
	}
?>
