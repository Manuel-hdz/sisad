<?php
	/**
	  * Nombre del Módulo: Panel de Control
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 13/Agosto/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de BorrarUsuarios del Sistema
	**/

	//Funcion que muestra los Usuarios Registrados
	function mostrarUsuarios(){
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_usuarios");
		
		//Crear la sentencia para mostrar el personal
		$stm_sql = "SELECT depto,tipo_usuario,usuario,nombre FROM usuarios JOIN credenciales ON usuarios_usuario=usuario WHERE usuario!='CPanel' ORDER BY depto";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
										
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%'>      			
				<tr>
				    <td colspan='18' align='center' class='titulo_etiqueta'>Seleccione un Usuario para Borrar</td>
  				</tr>
					<tr>
						<td class='nombres_columnas' align='center' colspan='2'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
						<td class='nombres_columnas' align='center'>TIPO DE USUARIO</td>
						<td class='nombres_columnas' align='center'>USUARIO</td>
						<td class='nombres_columnas' align='center'>ASIGNADO A</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>$cont.-</td>
						<td class='nombres_filas' align='center'>";?><input type="radio" name="rdb_usuario" id="rdb_usuario" value="<?php echo $datos["usuario"];?>" title="Eliminar Registro Seleccionado"/><?php echo "</td>
						<td class='nombres_filas' align='center'>$datos[depto]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_usuario]</td>
						<td class='$nom_clase' align='center'>$datos[usuario]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>";
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
	}
	
	function borrarUsuario(){
		$usuario=$_POST["rdb_usuario"];
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_usuarios");
		//Crear la sentencia para mostrar el catalogo de Materiales
		$stm_sql = "DELETE FROM usuarios WHERE usuario='$usuario'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Cerrar la conexion con la Base de Datos
		mysql_close($conn);
		if ($rs){
			borrarPermisos($usuario);
			borrarCredenciales($usuario);
			return 1;
		}
		else
			return mysql_error();
	}

	function borrarPermisos($usuario){
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_usuarios");
		//Crear la sentencia para mostrar el catalogo de Materiales
		$stm_sql = "DELETE FROM permisos WHERE usuarios_usuario='$usuario'";
		//Ejecutar la sentencia previamente creada
		mysql_query($stm_sql);
		//Cerrar la conexion con la Base de Datos
		mysql_close($conn);
	}
	
	function borrarCredenciales($usuario){
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_usuarios");
		//Crear la sentencia para mostrar el catalogo de Materiales
		$stm_sql = "DELETE FROM credenciales WHERE usuarios_usuario='$usuario'";
		//Ejecutar la sentencia previamente creada
		mysql_query($stm_sql);
		//Cerrar la conexion con la Base de Datos
		mysql_close($conn);
	}
?>