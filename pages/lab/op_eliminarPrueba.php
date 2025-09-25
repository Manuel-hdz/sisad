<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 18/Junio/2011
	  * Descripción: Este archivo contiene funciones para Consultar una Prueba de la BD de Laboratorio y poder Eliminarla
	**/

	//Verificar que el boton de Eliminar venga en el POST, de esta manera podemos mandar llamar a la funcion que 
	 //Realiza el borrado de la Prueba
	 if (isset($_POST["sbt_eliminar"])){
	 	eliminarPrueba();
	 }

	/*
		Valores de Patron
		1 => Busqueda por Tipo de Prueba
		2 => Busqueda por Norma de Prueba
	*/
	//Funcion que muestra las Pruebas segun los criterios especificados
	function mostrarPruebas($criterio,$patron){
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar las pruebas por TIPO
			$stm_sql="SELECT * FROM catalogo_pruebas WHERE tipo='$criterio' AND estado='1'";	
			//Creamos el titulo de la tabla
			$titulo="Pruebas de Tipo <em>$criterio</em>";
		}
		if ($patron==2){
			//Creamos la sentencia SQL para mostrar las pruebas por NORMA
			$stm_sql="SELECT * FROM catalogo_pruebas WHERE norma='$criterio' AND estado='1'";	
			if ($criterio!="N/A")
				//Creamos el titulo de la tabla
				$titulo="Pruebas de la Norma <em>$criterio</em>";
			else
				//Creamos el titulo de la tabla
				$titulo="Pruebas Sin Norma de Referencia";
		}
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_laboratorio");
			
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>NORMA</td>
						<td class='nombres_columnas' align='center'>TIPO</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>
							<td class='nombres_filas' align='center'><input type='radio' name='rdb_id' id='rdb_id' value='$datos[id_prueba]'/></td>			
							<td class='nombres_filas' align='center'>$datos[norma]</td>
							<td class='$nom_clase' align='left'>$datos[tipo]</td>
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
							<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "</table>";
		}

		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion mostrarPruebas
	
	//Funcion para actualizar el estado de las Pruebas a uno de Inactividad, esto en caso de ser eliminadas
	function eliminarPrueba(){
		//Incluir el modulo de conexion
		include_once("../../includes/conexion.inc");
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_laboratorio");
		//Crear la sentencia SQL para actualizar el estado
		$stm_sql="UPDATE catalogo_pruebas SET estado='0' WHERE id_prueba='$_POST[rdb_id]'";
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs)
			//registrarOperacion("bd_laboratorio",$_POST[rdb_id],"EliminarPrueba",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		else{
			$error = mysql_error();
			//Cerrar la conexion con la BD
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";

		}
	}