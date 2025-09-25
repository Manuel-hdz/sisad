<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 18/Octubre/2012
	  * Descripción: Este archivo contiene funciones para Consultar las Llantas y el Catálogo de la BD de Mantenimiento
	**/

	//Esta funcion Muestra los Aceites del Catálogo
	function mostrarLlantas(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Creamos la sentencia SQL para mostrar los datos del Equipo
		$stm_sql="SELECT * FROM llantas WHERE estado!='-1'";
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Cat&aacute;logo de Llantas</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>MARCA</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>DISPONIBILIDAD</td>
						<td class='nombres_columnas' align='center'>MEDIDA LLANTA</td>
						<td class='nombres_columnas' align='center'>MEDIDA RIN</td>
						<td class='nombres_columnas' align='center'>COSTO</td>
						
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$estado="";
			do{	
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[id_llanta]</td>
						<td class='$nom_clase' align='center'>$datos[marca]</td>
						<td class='$nom_clase' align='center'>$datos[estado]</td>
						<td class='$nom_clase' align='center'>$datos[ubicacion]</td>
						<td class='$nom_clase' align='center'>$datos[disponible]</td>
						<td class='$nom_clase' align='center'>$datos[medida]</td>
						<td class='$nom_clase' align='center'>$datos[medida_rin]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["costo"],2,".",",")."</td>
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
	}//Fin de la funcion para mostrar el Catálogo de Aceites
	
	//Esta funcion se encargar de Actualizar el catálogo de Aceites
	function guardarActualizacionLlanta(){
		/****************************************/
		//Calcular el ID de la Llanta con rutina
		//$idLlanta=obtenerIdLlanta();
		/****************************************/
		//Obtener el ID para la bitacora de Llantas
		$idBitacora=obtenerIdBitacoraLlanta();
		//Fecha
		$fecha=date("Y-m-d");
		//Extraer los datos del POST
		$idLlanta=strtoupper($_POST["cmb_llanta"]);
		$marca=strtoupper($_POST["cmb_marca"]);
		$medida=strtoupper($_POST["txt_medida"]);
		$medidaRin=strtoupper($_POST["txt_medidaRin"]);
		$estado=$_POST["cmb_estado"];
		$costo=str_replace(",","",$_POST["txt_costo"]);
		$ubicacion=strtoupper($_POST["cmb_ubicacion"]);
		$disponible=strtoupper($_POST["cmb_disponibilidad"]);
		//Verificar el tipo de Movimiento a realizar en la bitacora
		if($_POST["hdn_estado"]=="Agregar"){
			//Abrimos la conexion con la Base de datos
			$conn=conecta("bd_mantenimiento");
			//Sentencia para agregar la Llanta al "STOCK"
			$sql_stm="INSERT INTO llantas (id_llanta,marca,medida,medida_rin,estado,costo,ubicacion,disponible) 
						VALUES ('$idLlanta','$marca','$medida','$medidaRin','$estado','$costo','$ubicacion','$disponible')";
			$rs=mysql_query($sql_stm);
			if($rs){
				//Cerramos la conexion con la Base de Datos
				mysql_close($conn);
				//Registrar el movimiento en la bitácora de Movimientos
				registrarOperacion("bd_mantenimiento","$idLlanta","AgregarLlanta",$_SESSION['usr_reg']);
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('¡Llanta Registrada con Éxito!');",1000);
				</script>
				<?php
			}
			else{
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Llanta NO pudo ser Registrada');",1000);
				</script>
				<?php
				mysql_close($conn);
			}
		}
		else{
			//Abrimos la conexion con la Base de datos
			$conn=conecta("bd_mantenimiento");
			//Sentencia para actualizar la llanta en Stock
			$sql_stm="UPDATE llantas SET marca='$marca',medida='$medida',medida_rin='$medidaRin',estado='$estado',costo='$costo',ubicacion='$ubicacion',disponible='$disponible' WHERE id_llanta='$idLlanta'";
			$rs=mysql_query($sql_stm);
			if($rs){
				//Cerramos la conexion con la Base de Datos
				mysql_close($conn);
				//Registrar el movimiento en la bitácora de Movimientos
				registrarOperacion("bd_mantenimiento","$idLlanta","ActualizarLlanta",$_SESSION['usr_reg']);
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('¡Llanta Actualizada con Éxito!');",1000);
				</script>
				<?php
			}
			else{
				//Cerrar la conexion
				mysql_close($conn);
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Llanta NO pudo ser Actualizada');",1000);
				</script>
				<?php
			}
		}
	}//Fin de function guardarActualizacionLlanta()
	
	//Funcion que calcula el id para la bitacora de Aceites
	function obtenerIdBitacoraLlanta(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Sentencia SQl para extraer el id
		$sql="SELECT MAX(id_bitacora)+1 AS id FROM bitacora_llantas";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["id"]==NULL)
				$id=1;
			else
				$id=$datos["id"];
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		//Retornar el ID calculado
		return $id;
	}//Fin de obtenerIdBitacoraAceite()
	
	//Funcion que calcula el id para la bitacora de Aceites
	function obtenerIdLlanta(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Sentencia SQl para extraer el id
		$sql="SELECT MAX(id_llanta)+1 AS id FROM llantas";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["id"]==NULL)
				$id=1;
			else
				$id=$datos["id"];
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		//Retornar el ID calculado
		return $id;
	}//Fin de obtenerIdBitacoraAceite()
	
	//funcion que registra en la bitacora las salidas de las llantas
	function guardarRegistroLlantas(){
		//Obtener el ID para la bitacora de Llantas
		$idBitacora=obtenerIdBitacoraLlanta();
		//Recuperar los datos del POST
		$llanta=$_POST["cmb_llanta"];
		$equipo=$_POST["cmb_equipo"];
		$fecha=modFecha($_POST["txt_fecha"],3);
		$turno=$_POST["cmb_turno"];
		$tipoTrabajo=$_POST["cmb_tipoTrabajo"];
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$costo=str_replace(",","",$_POST["txt_costo"]);
		//Abrir la conexion a la BD
		$conn=conecta("bd_mantenimiento");
		//Sentencias SQL
		$sql="INSERT INTO bitacora_llantas (id_bitacora,llantas_id_llanta,equipo,fecha,turno,tipo_trabajo,descripcion,costo) 
			VALUES ('$idBitacora','$llanta','$equipo','$fecha','$turno','$tipoTrabajo','$descripcion','$costo')";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar el resultado de la sentencia
		if(!$rs){
			$error = mysql_error();	
			mysql_close($conn);		
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{
			//Si la llanta se Deshecha, quitarla de sus apariciones en el combo de seleccion marcando su estado como DESHECHO
			if($tipoTrabajo=="DESHECHAR"){
				mysql_query("UPDATE llantas SET estado='DESHECHO' WHERE id_llanta='$llanta'");
			}
			mysql_close($conn);
			registrarOperacion("bd_mantenimiento","$idBitacora","RegistrarBitacoraLlantas",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}
?>