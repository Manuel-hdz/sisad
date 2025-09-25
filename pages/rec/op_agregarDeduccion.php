<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 20/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar deduccion en la BD
	**/
	
	//Funcion que se encarga de desplegar las deducciones agregados
	function mostrarDeduccionesAdd(){
		echo "<table cellpadding='5' width='900'>";
		echo "  
			<tr>
				<td class='nombres_columnas' align='center'>RFC</td>
        		<td class='nombres_columnas' align='center'>NOMBRE DEL TRABAJADOR</td>
        		<td class='nombres_columnas' align='center'>CLAVE DEDUCCI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>NOMBRE DE LA DEDUCCI&Oacute;N</td>
			    <td class='nombres_columnas' align='center'>CANTIDAD</td>
			    <td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$total = 0;
		
		foreach ($_SESSION['deducciones'] as $ind => $info) {
			echo "<tr>";
			foreach ($info as $key => $value) {
				switch($key){
					case "rfc":
						echo "<td class='nombres_filas'>$value</td>";
					break;
					case "nom_empleado":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "claveDed":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "tipoDeduccion":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantidad":
						echo "<td class='$nom_clase' align='center'>$".number_format($value, 2,".",",")."</td>";
					break;
					case "descripcion";
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;		
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarDeduccionesAdd()	


	 //Funcion para guardar las deducciones agregadas al arreglo de session
	function guardarDeducciones(){

		//Recorrer el arreglo que contiene las deducciones
		foreach($_SESSION['deducciones'] as $ind => $concepto){
			//Conectarcs a la Base de Datos
			$conn = conecta("bd_recursos");

			//Retirar la coma de la cantidad en caso que exista, para alamcenar el dato en la BD
			$cantidad = $concepto['cantidad'];
			if (strlen($cantidad)> 6 )
				$cantidad= str_replace(",","",$concepto["cantidad"]);	
				
			$fecha= modfecha($concepto['fecha'],3);
			
			//Crear la Sentencia SQL para Alamcenar las deducciones agregados 
			//El estado al momento de ser registrada la deduccion por default es activo
			$stm_sql= "INSERT INTO deducciones (empleados_rfc_empleado, id_deduccion, nom_deduccion, descripcion, total, fecha_alta, estado)
			VALUES ('$concepto[rfc]','$concepto[claveDed]', '$concepto[tipoDeduccion]','$concepto[descripcion]', $cantidad, '$fecha', 'ACTIVO')";
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs)
				$band=1;
			else
				$band=0;
		}// Fin foreach($_SESSION['deducciones'] as $ind => $concepto)	
		if($band==1){
			//Guardar el registro de movimientos
			registrarOperacion("bd_recursos",$concepto['claveDed'],"AgregaDeduccion",$_SESSION['usr_reg']);
			$conn = conecta("bd_recursos");
			//Si el resultado es exitoso registrar el detalle de la deduccion
			regDetalleDeduccion();
		}
		if($band==0){
			unset ($_SESSION['deducciones']);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}		
			
	}// Fin function guardarDeducciones()
	
	//Funcion que se encarga de registrar el detalle de la deduccion
	function regDetalleDeduccion(){
		//Conectarcs a la Base de Datos
		$conn2 = conecta("bd_recursos");

		//Recorrer el arreglo que contiene las deducciones
		foreach($_SESSION['deducciones'] as $ind => $concepto){

			//Retirar la coma de la cantidad en caso que exista, para alamcenar el dato en la BD
			$cantidad = $concepto['cantidad'];
			if (strlen($cantidad)> 6 )
				$cantidad= str_replace(",","",$concepto["cantidad"]);	
				
			$fecha= modfecha($concepto['fecha'],3);
		
			//Crear la Sentencia SQL para Alamcenar las deducciones agregados 
			$stm_sql= "INSERT INTO detalle_abonos (deducciones_id_deduccion, saldo_inicial, saldo_final, fecha_abono) 
			VALUES ('$concepto[claveDed]', $cantidad, $cantidad, '$fecha')";
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs)
				$band=1;
			else 
				$band=0;
		}// Fin foreach($_SESSION['deducciones'] as $ind => $concepto)
		if($band==1){
			unset ($_SESSION['deducciones']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		if($band==0){
			unset ($_SESSION['deducciones']);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
		//Cerrar la Conexion con la BD
		mysql_close($conn2);
	}
?>