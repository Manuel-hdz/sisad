<?php
	/**
	  * Nombre del Módulo: Panel de Control
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 13/Agosto/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de DesbloquearUsuarios del Sistema
	**/

	//Funcion que muestra los Usuarios Registrados
	function mostrarUsuarios(){
		//Variable para identificar si hubo resultados o no dada la consulta
		$band=0;
		//Archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		//Crear la sentencia para mostrar los Usuarios Bloqueados
		$stm_sql = "SELECT * FROM bloqueados ORDER BY hora_bloqueo";
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
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>DIRECCI&Oacute;N IP DEL EQUIPO</td>
        				<td class='nombres_columnas' align='center'>HORA DE BLOQUEO</td>
						<td class='nombres_columnas' align='center'>HORA DE DESBLOQUEO</td>
						<td class='nombres_columnas' align='center'>DESBLOQUEAR</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>$cont.-</td>
						<td class='$nom_clase' align='center'>$datos[direccion_ip]</td>
						<td class='$nom_clase' align='center'>$datos[hora_bloqueo]</td>
						<td class='$nom_clase' align='center'>$datos[hora_desbloqueo]</td>";
						?>
						<td class="<?php echo $nom_clase;?>" align="center">
						<input type="hidden" name="hdn<?php echo $cont;?>" id="hdn<?php echo $cont;?>" value="<?php echo $datos["direccion_ip"];?>"/>
						<img src="images/bloqueo.png" width="30" height="30" border="0" title="Desbloquear el Equipo Seleccionado" onclick="desbloquear(this);" id="img<?php echo $cont;?>" name="img<?php echo $cont;?>"/>
						</td>
						<?php
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
		return $band;
	}
	
	function desbloquearIP(){
		//Recuperar la IP
		$ip=$_POST["hdnBorrar"];
		$ip=$_POST["hdn$ip"];
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		
		//Crear la sentencia para desbloquear a los Usuarios
		$stm_sql = "DELETE FROM bloqueados WHERE direccion_ip='$ip'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		if ($rs)
			return 1;
		else
			return mysql_error();
	}
?>