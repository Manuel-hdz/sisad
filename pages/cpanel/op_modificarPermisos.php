<?php
	/**
	  * Nombre del M�dulo: Panel de Control
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 13/Agosto/2011
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de Desbloquear Usuarios del Sistema
	**/

	//Funcion que muestra los Permisos Registrados a los Usuarios
	function mostrarArchivos($depto,$usuario){
		//Verificar el valor del departamento para mostrar el Encabezado
		switch ($depto){
				case "Almacen":
					$departamento="Almac&eacute;n";
					$modulo="almacen";
					break;
				case "Compras";
					$departamento="Compras";
					$modulo="compras";
					break;
				case "GerenciaTecnica":
					$departamento="Gerencia T&eacute;cnica";
					$modulo="gerencia";
					break;
				case "RecursosHumanos":
					$departamento="Recursos Humanos";
					$modulo="recursos";
					break;
				case "Produccion":
					$departamento="Producci&oacute;n";
					$modulo="produccion";
					break;
				case "Calidad":
					$departamento="Aseguramiento de Calidad";
					$modulo="calidad";
					break;
				case "Desarrollo":
					$departamento="Desarrollo";
					$modulo="desarrollo";
					break;
				case "MttoConcreto":
					$departamento="Mantenimiento Concreto";
					$modulo="mantenimiento";
					break;
				case "MttoMina":
					$departamento="Mantenimiento Mina";
					$modulo="mantenimiento";
					break;
				case "Topografia":
					$departamento="Topograf&iacute;a";
					$modulo="topografia";
					break;
				case "Laboratorio":
					$departamento="Laboratorio";
					$modulo="laboratorio";
					break;
				case "Lampisteria":
					$departamento="Lampisteria";
					$modulo="lampisteria";
					break;
				case "Seguridad":
					$departamento="Seguridad Industrial";
					$modulo="seguridad";
					break;
				case "SeguridadAmbiental":
					$departamento="Seguridad Ambiental";
					$modulo="seguridad";
					break;
				case "Paileria":
					$departamento="Paileria";
					$modulo="paileria";
					break;
				case "Comaro":
					$departamento="Comaro";
					$modulo="comaro";
					break;
				case "Sistemas":
					$departamento="Sistemas";
					$modulo="sistemas";
					break;
				case "SupervisionDes":
					$departamento="Supervision Desarrollo";
					$modulo="sup_des";
					break;
			}
		//El valor de band es 0 por default, si no cambia, significa que la consulta no genero resultados
		$band=0;
		//Archivo de conexion
		include_once("../../includes/conexion.inc");
		//Conectar a la BD de Usuarios
		$conn=conecta("bd_usuarios");
		//Crear la sentencia para mostrar los Usuarios Bloqueados
		$stm_sql = "SELECT area,estatus FROM permisos WHERE usuarios_usuario='$usuario' AND modulo='$modulo' GROUP BY area ORDER BY area";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		echo "<input type='hidden' name='cmb_depto' id='cmb_depto' value='$depto'/>";
		echo "<input type='hidden' name='cmb_modulo' id='cmb_modulo' value='$modulo'/>";
		echo "<input type='hidden' name='cmb_usuario' id='cmb_usuario' value='$usuario'/>";
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%'>      			
				<tr>
					<td colspan='4' align='center' class='titulo_etiqueta'>M&oacute;dulo <u><em>$departamento</em></u> - Permisos De <u><em>$usuario</em></u></td>
				</tr>
					<tr>
						<td class='nombres_columnas' align='center' colspan='2'>SECCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>ACCESO<br>PERMITIDO/BLOQUEADO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "<tr>
						<td class='nombres_filas' align='center' width='5%'>$cont.-</td>
						<td class='nombres_filas' align='center' width='60%'>".$datos["area"]."</td>
						<td class='$nom_clase' align='center' width='35%'>";
					//Verificar ESTATUS, si es igual a 1, mostrar opci�n de BLOQUEO, si es igual a 0, mostrar la de DESBLOQUEO
					if ($datos["estatus"]==1){
						?>
						<img src="images/a-desbloqueo.png" width="50" height="50" border="0" title="Bloquear el Acceso a la Secci&oacute;n del M&oacute;dulo" onclick="bloquearAcceso(this);" id="img<?php echo $datos["area"];?>" name="img<?php echo $datos["area"];?>" style="cursor:pointer;"/>
						<?php
					}
					else{
						?>
						<img src="images/a-bloqueo.png" width="50" height="50" border="0" title="Desbloquear el Acceso a la Secci&oacute;n del M&oacute;dulo" onclick="desbloquearAcceso(this);" id="img<?php echo $datos["area"];?>" name="img<?php echo $datos["area"];?>" style="cursor:pointer;"/>
						<?php
					}
				echo "</td></tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			//Esta seccion debe ser inalcanzable una vez terminado el sistema en su totalidad,
			//En caso de mostrarse, se muestra la pagina que indica que aun se encuentra en construccion
			//echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//Regresar el valor de la variable bandera
		return $band;
	}//Fin de mostrarArchivos($modulo,$usuario)
	
	//Funcion que modifica los permisos del Usuario
	function modificarPermiso($seccion,$accion,$modulo,$usuario){
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		//Verificar la accion a tomar, Bloqueo o Desbloqueo
		if ($accion=="desbloqueo")
			//Crear la sentencia para DESBLOQUEAR al Usuario de alguna secci�n
			$stm_sql = "UPDATE permisos SET estatus='1' WHERE usuarios_usuario='$usuario' AND area='$seccion' AND modulo='$modulo'";
		else
			//Crear la sentencia para BLOQUEAR al Usuario de alguna secci�n
			$stm_sql = "UPDATE permisos SET estatus='0' WHERE usuarios_usuario='$usuario' AND area='$seccion' AND modulo='$modulo'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Verificar el proceso de Actualizacion de Permisos para retornar el valor correspondiente y mostrar el mensaje adecuado
		if ($rs)
			return 1;
		else
			return mysql_error();
	}//Fin de la funcion modificarPermiso($seccion,$accion,$modulo,$usuario)
?>