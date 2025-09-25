<?php
	/**
	  * Nombre del M�dulo: Panel de Control
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 13/Agosto/2011
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de DesbloquearUsuarios del Sistema
	**/

	//Funcion que muestra los Usuarios Registrados
	function mostrarMovimientos($modulo,$fechaI,$fechaF){
		//El valor de band es 0 por default, si no cambia, significa que la consulta no genero resultados
		$band=0;
		//Archivo de conexion
		include_once("../../includes/conexion.inc");
		//Archivo de fechas
		include_once("../../includes/func_fechas.php");
		//Verificar la Base de Datos de Conexion
		switch ($modulo){
				case "Almacen":
					$departamento="ALMACEN";
					$base="bd_almacen";
					break;
				case "Compras";
					$departamento="COMPRAS";
					$base="bd_compras";
					break;
				case "GerenciaTecnica":
					$departamento="GERENCIA TECNICA";
					$base="bd_gerencia";
					break;
				case "RecursosHumanos":
					$departamento="RECURSOS HUMANOS";
					$base="bd_recursos";
					break;
				case "Produccion":
					$departamento="PRODUCCION";
					$base="bd_produccion";
					break;
				case "AseguramientoCalidad":
					$departamento="ASEGURAMIENTO DE CALIDAD";
					$base="bd_aseguramiento";
					break;
				case "Desarrollo":
					$departamento="DESARROLLO";
					$base="bd_desarrollo";
					break;
				case "Mantenimiento":
					$departamento="MANTENIMIENTO";
					$base="bd_mantenimiento";
					break;
				case "Topografia":
					$departamento="TOPOGRAFIA";
					$base="bd_topografia";
					break;
				case "Laboratorio":
					$departamento="LABORATORIO";
					$base="bd_laboratorio";
					break;
				case "Lampisteria":
					$departamento="LAMPISTERIA";
					$base="bd_lampisteria";
					break;
				case "Seguridad":
					$departamento="SEGURIDAD INDUSTRIAL Y AMBIENTAL";
					$base="bd_seguridad";
					break;
				case "Paileria":
					$departamento="PAILERIA";
					$base="bd_paileria";
					break;
				case "MttoElectrico":
					$departamento="MANTENIMIENTO ELECTRICO";
					$base="bd_mantenimientoe";
					break;
				case "Clinica":
					$departamento="UNIDAD DE SALUD OCUPACIONAL";
					$base="bd_clinica";
					break;
				case "Comaro":
					$departamento="COMARO";
					$base="bd_comaro";
					break;
				case "Sistemas":
					$departamento="SISTEMAS";
					$base="bd_sistemas";
					break;
				case "SupervisionDes":
					$departamento="SUPERVISION DESARROLLO";
					$base="bd_desarrollo";
					break;
			}
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta($base);
		if (!$conn){
			echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
			//Asignar a band el valor de 2, indicando que el modulo se encuentra en construccion
			$band=2;
		}
		else{
			$fechaI=modFecha($fechaI,3);
			$fechaF=modFecha($fechaF,3);
			//Crear la sentencia para mostrar los Usuarios Bloqueados
			$stm_sql = "SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora";
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
				//Desplegar los resultados de la consulta en una tabla
				echo "				
					<table cellpadding='5' width='100%'>      			
					<tr>
						<td colspan='18' align='center' class='titulo_etiqueta'>MOVIMIENTOS REALIZADOS EN $departamento DEL ".modFecha($fechaI,1)." AL ".modFecha($fechaF,1)."</td>
					</tr>
						<tr>
							<td class='nombres_columnas' align='center'>No.</td>
							<td class='nombres_columnas' align='center'>USUARIO</td>
							<td class='nombres_columnas' align='center'>ID OPERACI&Oacute;N</td>
							<td class='nombres_columnas' align='center'>OPERACI&Oacute;N</td>
							<td class='nombres_columnas' align='center'>FECHA</td>
							<td class='nombres_columnas' align='center'>HORA</td>
						</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					echo "	<tr>
							<td class='nombres_filas' align='center'>$cont.-</td>
							<td class='$nom_clase' align='center'>$datos[usuario]</td>
							<td class='$nom_clase' align='center'>$datos[id_operacion]</td>
							<td class='$nom_clase' align='center'>$datos[tipo_operacion]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos["fecha"],1)."</td>
							<td class='$nom_clase' align='center'>".modHora($datos["hora"])."</td>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs));
				echo "</table>";
				$band=1;
			}
			//Cerrar la conexion con la BD		
			mysql_close($conn);
		}
		return $band;
	}
?>