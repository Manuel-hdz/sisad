<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                           
	  * Fecha: 28/Agosto/2012
	  * Descripción: Este archivo permite gestionar los Subtipos de las Obras
	  **/

	//Funcion que muestra las Subcategorias registradas en la Base de Datos
	function mostrarSubtipos(){
		$conn=conecta("bd_topografia");
		$sql="SELECT * FROM subcategorias WHERE id>0 ORDER BY orden";
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='4' align='center' class='titulo_etiqueta'>Subtipos Registrados</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>&Oacute;RDEN</td>
					<td class='nombres_columnas' align='center'>SUBCATEGOR&Iacute;A</td>					
					<td class='nombres_columnas' align='center'>P.U. M.N.</td>
					<td class='nombres_columnas' align='center'>P.U. USD</td>
					<td class='nombres_columnas' align='center'>SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<input type=\"hidden\" name=\"hdn_id$cont\" id=\"hdn_id$cont\" value=\"$datos[id]\"/>
					
					<tr>
						<td class='$nom_clase'>
							<input name=\"txt_orden$cont\" id=\"txt_orden$cont\" type=\"text\" class=\"caja_de_num\" 
							onkeypress=\"return permite(event,'num',2);\" value=\"$datos[orden]\" size=\"5\"/>
						</td>
						<td class='$nom_clase'>
							<input name=\"txt_nombreSubcategoria$cont\" type=\"text\" class=\"caja_de_texto\" id=\"txt_nombreSubcategoria$cont\" onkeypress=\"return permite(event,'num_car',0);\" 
							value=\"$datos[subcategoria]\" size=\"30\" maxlength=\"20\"/>
						</td>
						<td class='$nom_clase'>
							$<input name=\"txt_precioEstimacionMN$cont\" id=\"txt_precioEstimacionMN$cont\" type=\"text\" class=\"caja_de_num\" onkeypress=\"return permite(event,'num',2);\" 
							value=\"$datos[pu_umn]\" onchange=\"formatCurrency(value,'txt_precioEstimacionMN$cont')\" size=\"10\"/>
						</td>
						<td class='$nom_clase'>
							$<input name=\"txt_precioEstimacionUSD$cont\" id=\"txt_precioEstimacionUSD$cont\" type=\"text\" class=\"caja_de_num\" onkeypress=\"return permite(event,'num',2);\" 
							value=\"$datos[pu_usd]\" onchange=\"formatCurrency(value,'txt_precioEstimacionUSD$cont')\" size=\"10\"/>
						</td>
						<td class='$nom_clase'>
							<input name=\"txt_seccion$cont\" id=\"txt_seccion$cont\" type=\"text\" class=\"caja_de_texto\" size=\"10\" maxlength=\"10\" value=\"$datos[seccion]\" 
							onkeypress=\"return permite(event,'num',6);\" onchange=\"calcularArea();\"/>
							
						</td>
						<td class='$nom_clase'>
							<input name=\"txt_area$cont\" id=\"txt_area$cont\" type=\"text\" class=\"caja_de_num\" value=\"$datos[area]\" readonly=\"readonly\" size='10'/>
						</td>
						
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "<input type='hidden' name='hdn_cantidad' id='hdn_cantidad' value='$cont'/>";
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
		}
		else{
			return 0;
			echo "<br /><br /><br /><br /><br /><label class='msje_correcto' align='center'>No Existen Registros de Subtipos</label>";
		}
	}
	
	function obtenerOrden(){
		$conn=conecta("bd_topografia");
		$sql="SELECT MAX(orden)+1 FROM subcategorias";
		$rs=mysql_query($sql);
		$orden=1;
		if($datos=mysql_fetch_array($rs)){
			if($datos[0]!=NULL)
				$orden=$datos[0];
		}
		mysql_close($conn);
		return $orden;
	}
	
	function obtenerIdSubcategoria(){
		$conn=conecta("bd_topografia");
		$sql="SELECT MAX(id)+1 FROM subcategorias";
		$rs=mysql_query($sql);
		$orden=1;
		if($datos=mysql_fetch_array($rs)){
			if($datos[0]!=NULL)
				$orden=$datos[0];
		}
		mysql_close($conn);
		return $orden;
	}
	
	function agregarSubtipo(){
		$id=obtenerIdSubcategoria();
		$conn=conecta("bd_topografia");
		$orden=$_POST["txt_orden"];
		$subcategoria=strtoupper($_POST["txt_nombreSubtipo"]);
		$pu_mn=str_replace(",","",$_POST["txt_precioEstimacionMN"]);
		$pu_usd=str_replace(",","",$_POST["txt_precioEstimacionUSD"]);
		$seccion=$_POST["txt_seccion"];
		$area=$_POST["txt_area"];
		//Sentencia SQL de Actualizacion de Datos
		$sql="INSERT INTO subcategorias (id,orden,subcategoria,pu_umn,pu_usd,seccion,area) VALUES ('$id','$orden','$subcategoria','$pu_mn','$pu_usd','$seccion','$area')";
		$rs=mysql_query($sql);
		$error="";
		if(!$rs)
			$error=mysql_error();
		mysql_close($conn);
		if($error!="")
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('¡Subtipo Registrado!');",1000);
			</script>
			<?php
		}
	}
	
	//Funcion que modifica los subtipos ya agregados al sistema
	function modificarSubtipos(){
		//Obtener la cantidad de subtipos
		$cantidad=$_POST["hdn_cantidad"];
		$cont=1;
		$conn=conecta("bd_topografia");
		$error="";
		do{
			//Obtener los datos de las subcategorias
			$id=$_POST["hdn_id$cont"];
			$orden=$_POST["txt_orden$cont"];
			$subcategoria=strtoupper($_POST["txt_nombreSubcategoria$cont"]);
			$pu_mn=str_replace(",","",$_POST["txt_precioEstimacionMN$cont"]);
			$pu_usd=str_replace(",","",$_POST["txt_precioEstimacionUSD$cont"]);
			$seccion=$_POST["txt_seccion$cont"];
			$area=$_POST["txt_area$cont"];
			//Sentencia SQL de Actualizacion de Datos
			$sql="UPDATE subcategorias SET orden='$orden',subcategoria='$subcategoria',pu_umn='$pu_mn',pu_usd='$pu_usd' WHERE id='$id'";
			$rs=mysql_query($sql);
			if(!$rs){
				$error=mysql_error();
				break;
			}
			$cont++;
		}while($cont<$cantidad);
		mysql_close($conn);
		if($error!="")
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('¡Actualización Realizada!');",1000);
			</script>
			<?php
		}
	}
?>