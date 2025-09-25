<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 02/Junio/2011
	  * Descripción: Este archivo contiene funciones para Mostrar el detalle de requisiciones por cada departamento
	  **/	 		  	  	

	//Funcion para mostrar las requisiciones de los demas departamentos
	function mostrarRequisiciones($depto){
		//Obtenemos el nombre del departamento
		switch ($depto){
			case "gerenciatecnica":
				$departamento="GERENCIA TECNICA";
				$base="bd_gerencia";
				break;
			case "recursoshumanos":
				$departamento="RECURSOS HUMANOS";
				$base="bd_recursos";
				break;
			case "produccion":
				$departamento="PRODUCCION";
				$base="bd_produccion";
				break;
			case "aseguramientodecalidad":
				$departamento="ASEGURAMIENTO DE CALIDAD";
				$base="bd_aseguramiento";
				break;
			case "desarrollo":
				$departamento="DESARROLLO";
				$base="bd_desarrollo";
				break;
			case "mantenimiento":
				$departamento="MANTENIMIENTO";
				$base="bd_mantenimiento";
				break;
			case "topografia":
				$departamento="TOPOGRAFIA";
				$base="bd_topografia";
				break;
			case "laboratorio":
				$departamento="LABORATORIO";
				$base="bd_laboratorio";
				break;
			case "seguridadindustrial":
				$departamento="SEGURIDAD INDUSTRIAL";
				$base="bd_seguridad";
				break;
			case "paileria":
				$departamento="PAILERIA";
				$base="bd_paileria";
				break;
			default:
				$base="";
				break;
		}
		
		//Conectar a la BD que corresponde
		$conn=conecta($base);
		if (!$conn){
			//Redireccionamos a la pagina de modulo en construccion en caso que la BD no exista
			echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
			//Retornamos 0 para evitar mostrar errores y que el proceso termine aqui
			return 0;
		}
		//Sentencia SQL para la obtencion de las Requisiciones
		$stm_sql="SELECT DISTINCT id_requisicion,area_solicitante,fecha_req,solicitante_req FROM requisiciones JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion WHERE detalle_requisicion.estado=1";
		$rs=mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "
				<br><br>
				<table cellpadding='5' width='100%'> 
					<caption><strong>Copias de Requisiciones de <em><u>$departamento</u><em></strong></caption>
					<tr>
						<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>SOLICITANTE</td>
						<td class='nombres_columnas' align='center's>VER PDF</td>
					</tr>
			";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
					<tr>
						<td class='nombres_filas' align='center'>$datos[id_requisicion]</td>
						<td class='$nom_clase' align='center'>$datos[area_solicitante]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha_req"],2)."</td>
						<td class='$nom_clase' align='center'>$datos[solicitante_req]</td>";
					?>
						<td align="center" class="<?php echo $nom_clase;?>">
						<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF de la Requisición Seleccionada" onmouseover="window.status='';return true" 
						onclick="window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $datos["id_requisicion"]; ?>&copia=si','_blank',
						'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no,location=no, directories=no')" />
						</td>
					<?php
				echo "</tr>";
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
			echo"</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>No existen Requisiciones Nuevas de $departamento</p>";
		}
	}
?>
 