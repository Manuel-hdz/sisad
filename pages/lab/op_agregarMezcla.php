<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 18/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar mezcla en la BD
	**/
	
	
	//Funcion que se encarga de desplegar los materiales agregados
	function mostrarMatAdd(){
		echo "<table cellpadding='5' width='900'>";
		echo "  
			<tr>
				<td class='nombres_columnas' align='center'>CLAVE MATERIAL</td>
        		<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
				<td class='nombres_columnas' align='center'>NOMBRE</td>
			    <td class='nombres_columnas' align='center'>CANTIDAD</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$total = 0;

		foreach ($_SESSION['materiales'] as $ind => $datosMat) {
			$nomMaterial=obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datosMat['claveMat']);
			echo "<tr>			
					<td class='nombres_filas' align='center'>$datosMat[claveMat]</td>
					<td class='$nom_clase' align='center'>$datosMat[categoria]</td>
					<td class='$nom_clase' align='center'>$nomMaterial</td>
					<td class='$nom_clase' align='center'>$datosMat[cantidad] $datosMat[unidad]</td>
			</tr>";			
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";
	}//Fin de la funcion mostrarMatAdd()	


	//Funcion para guardar los materiales agregados
	function guardarMateriales(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_laboratorio");
		$idMezcla= $_SESSION['mezclaGral']['idMezcla'];	
		
		//Recorrer el arreglo que contiene los materiales
		foreach($_SESSION['materiales'] as $ind => $concepto){
			//Al momento de agregar los componenetes Eliminar la posible como(,) y redondear el numero hasta 5 decimales
			$cant = round(str_replace(",","",$concepto['cantidad']),5);
			
			//Crear la Sentencia SQL para Alamcenar los materiales agregados 
			$stm_sql = "INSERT INTO materiales_de_mezclas (mezclas_id_mezcla, catalogo_materiales_id_material, cantidad, unidad_medida, volumen)
			VALUES ('$idMezcla', '$concepto[claveMat]', $cant, '$concepto[unidad]', 1)";
			//Aqui el Volumen se colaca en 1 ya que es la medida base, pero puede cambiarse por una variable en el caso de que la mezcla que esta siendo registrada de como
			//mas de 1 metro cubico.
			
			
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){
				$band=1;
			}
			else{
				$band=0;
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset($_SESSION['mezclaGral']);
				unset($_SESSION['materiales']);
			}
		}// Fin foreach($_SESSION['materiales'] as $ind => $concepto)
		if($band==1){
			//llamar la funcion que guarda los datos generales de la mezcla
			guardarMezcla();
		}		
	}// Fin function guardarMateriales()
	
	 //Funcion para guardar la mezcla
	function guardarMezcla(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_laboratorio");

		//Recuperar la informacion de la sesion		
		$idMezcla = $_SESSION['mezclaGral']['idMezcla'];
		$nomMezcla = $_SESSION['mezclaGral']['nomMezcla'];
		$expediente= $_SESSION['mezclaGral']['expediente'];
		$eqMezclado = $_SESSION['mezclaGral']['eqMezclado'];
		$fechaRegistro = $_SESSION['mezclaGral']['fechaReg'];
		
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "INSERT INTO mezclas (id_mezcla, nombre, expediente, equipo_mezclado, fecha_registro)				
					VALUES ('$idMezcla', '$nomMezcla', '$expediente', '$eqMezclado', '$fechaRegistro')";		
				
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$idMezcla,"AgregarMezcla",$_SESSION['usr_reg']);
			$conn = conecta("bd_laboratorio");
			unset($_SESSION['mezclaGral']);
			unset($_SESSION['materiales']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			unset($_SESSION['mezclaGral']);
			unset($_SESSION['materiales']);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	 }// Fin function guardarMezcla()	
	 
	 
?>