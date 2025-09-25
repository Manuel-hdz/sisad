<?php
/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Nadia Madahí López Hernández                            
	  * Fecha: 11/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para guardar en la BD la información de los convenios que se tiene con algun proveedor en especifico.
	  **/
	function guardarConvenio(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");			
		//Convertir a Mayusculas los datos de los campos de Texto
		$convenio=strtoupper($_POST["txt_convenio"]);
		$responsable=strtoupper($_POST["txt_responsable"]);
		$comentarios=strtoupper($_POST["txa_comentarios"]);
		$autoriza=strtoupper($_POST["txt_autoriza"]);
		$nom=$_POST["txt_nombre"];
		//Transformar las fechas del formato dd/mm/aaa al formato aaaa-mm-dd
		$fecha_ini = modFecha($_POST["txt_fechaInicio"],3);
		$fecha_fin = modFecha($_POST["txt_fechaFin"],3);
		$fecha_elab = modFecha($_POST["txt_fechaElaboracion"],3);
		//Obtener los valores numéricos
		$subtotal=$_POST["txt_subtotal"];
		$iva=$_POST["txt_iva"];
		$total=$_POST["txt_total"];
		
		if (strlen($subtotal)>6)
			$subtotal=str_replace(",","",$subtotal);
		if (strlen($iva)>6)
			$iva=str_replace(",","",$iva);
		if (strlen($total)>6)
			$total=str_replace(",","",$total);
		
		//Obtener el valor del Combo
		$estado=$_POST["cmb_estado"];
		
		/*********Obtener el RFC del proveedor seleccionado**************/
		//Crear sentencia SQL
		$stm_sql="SELECT rfc FROM proveedores WHERE razon_social='$nom'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Confirmar que se obtiene el RFC y guardarlo en una variable
		if($datos=mysql_fetch_array($rs))
			$rfc = $datos['rfc'];
		/****************************************************************/
		
		//Crear la sentencia para realizar el registro del convenio en la BD de Compras en la tabla de Convenios
		$stm_sql = "INSERT INTO convenios
		(id_convenio,proveedores_rfc,fecha_inicio,fecha_fin,fecha_elaboracion,subtotal,iva,total,estado,responsable,autorizador,comentarios)
		VALUES('$convenio','$rfc','$fecha_ini','$fecha_fin','$fecha_elab',$subtotal,$iva,$total,'$estado','$responsable','$autoriza','$comentarios')";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);

		//Confirmar que la inserción de datos fue realizada con exito.
		if($rs) {
			if (isset($_SESSION["detallesconvenio"])){
				guardarConvenioDetalle($convenio);
			registrarOperacion("bd_compras",$convenio,"RegistrarConvenio",$_SESSION['usr_reg']);
			}
			else 
			
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Redireccionar a una pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		//Cerrar la conexion con la BD		
		//La Conexion a la BD se cierra en la funcion registrarOperacion("bd_compras",$txt_rfc,"AgregarCliente",$_SESSION['usr_reg']);	
	}
	
	function guardarConvenioDetalle($convenio){
		$band=0;
		//Registrar todos los materiales dados de alta en el arreglo $datosEntrada
		foreach ($_SESSION['detallesconvenio'] as $ind => $termino){
			if (strlen($termino["precio"])>6)
				$termino["precio"]=str_replace(",","",$termino["precio"]);
			if (strlen($termino["importe"])>6)
				$termino["importe"]=str_replace(",","",$termino["importe"]);
						
			//Crear la sentencia para realizar el registro de los datos del detalle de la Entrada de Material
			$stm_sql = "INSERT INTO detalles_convenio (convenios_id_convenio,numero,unidad,cantidad,material_servicio,precio_unitario,importe)
			VALUES('$convenio','$termino[numero]','$termino[unidad]','$termino[cantidad]','$termino[mat_serv]','$termino[precio]','$termino[importe]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_entradas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;
			else
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}
	
	function mostrarConvenioDet($detallesconvenio){
		$importe=0;
		echo "				
		<table cellpadding='5' width='690'>      			
			<tr>
				<td class='nombres_columnas' align='center'>N&Uacute;MERO</td>
				<td class='nombres_columnas' align='center'>MATERIAL Y/O SERVICIO</td>
				<td class='nombres_columnas' align='center'>CANTIDAD</td>
				<td class='nombres_columnas' align='center'>UNIDAD</td>
				<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
				<td class='nombres_columnas' align='center'>IMPORTE</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($detallesconvenio as $ind => $detalle) {
			echo "<tr>";
			foreach ($detalle as $key => $value) {
				switch($key){
					case "numero":
						echo "<td class='nombres_filas' align='center'>$value</td>";
					break;
					case "mat_serv":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "cantidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "precio":
						echo "<td class='$nom_clase' align='center'>$$value</td>";
					break;
					case "importe":
						echo "<td class='$nom_clase' align='center'>$$value</td>";
						$importe+=str_replace(',','',$value);
					break;
					case "unidad":
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
		echo "<tr>
			<td colspan='5' align='right'><strong>TOTAL</strong></td>
			<td align='center' class='nombres_columnas'>$".number_format($importe,2,".",",")."
			<input type='hidden' name='hdn_imp' id='hdn_imp' value='".$importe."'/>			
			</td>
		</tr>";
		echo "</table>";
	}//Fin de la funcion mostrarRegistros($datosEntrada)
	
	
?>