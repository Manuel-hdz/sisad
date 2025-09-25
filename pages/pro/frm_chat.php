<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
//	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
//		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
//	}
//	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<?php
		$h=opendir('.');
		while ($file=readdir($h)){
			if (substr($file,0,6)=='inicio'){
				break;
			}
		}
		closedir($h);
	?>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/jquery-1.5.1.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarAceptado.js"></script>
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('usermsg').focus();verificarAceptado('<?php echo $_SESSION["usr_reg"]?>','<?php echo $file;?>');",1000);
	</script>

	<script type="text/javascript">
	// jQuery Document
	$(document).ready(function(){
		//If user submits the form
		$("#submitmsg").click(function(){	
			var clientmsg = $("#usermsg").val();
			$.post("op_guardarMsje.php", {text: clientmsg});			
			$("#usermsg").attr("value", "");
			return false;
		});
		
		//Load the file containing the chat log
		function loadLog(){		
			var oldscrollHeight = $("#tabla-mensajes").attr("scrollHeight") - 20;
			$.ajax({
				url: "../../includes/chat/log.html",
				cache: false,
				success: function(html){		
					$("#tabla-mensajes").html(html); //Insert chat log into the #chatbox div				
					var newscrollHeight = $("#tabla-mensajes").attr("scrollHeight") - 20;
					if(newscrollHeight > oldscrollHeight){
						$("#tabla-mensajes").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
					}				
				},
			});
		}
		setInterval (loadLog, 2500);	//Reload file every 2.5 seconds
		
		//If user wants to end session
		$("#exit").click(function(){
			window.location = 'head_menu.php?logout=true';
		});
	});
	</script>
	
	<script type="text/javascript" language="javascript">
		setTimeout("acomodarScroll()",500);
		function acomodarScroll(){
			var finScroll = $("#tabla-mensajes").attr("scrollHeight") - 20;
			$("#tabla-mensajes").animate({ scrollTop: finScroll }, 'normal'); //Autoscroll to bottom of div
		}
	</script>
    <style type="text/css">
		<!--
		#tabla-mensajes {position:absolute; left:30px; top:190px; width:940px; height:420px; z-index:12; overflow:scroll;}
		#titulo-chat {position:absolute; left:30px; top:146px; width:191px; height:19px; z-index:11; }
		#botones { position: absolute; left:30px; top:650px; width:940px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-chat">Sala de Comunicaci&oacute;n </div>
	<form name="message" action="">
	<div id="tabla-mensajes" class="borde_seccion2" align="left" style="background-color:#EBF4FB">
		<?php
		if(file_exists("../../includes/chat/log.html") && filesize("../../includes/chat/log.html") > 0){
			$handle = fopen("../../includes/chat/log.html", "r");
			$contents = fread($handle, filesize("../../includes/chat/log.html"));
			fclose($handle);
			echo $contents;
		}
		?>
	</div>
	<div id="botones" align="center">
	<table width="100%" cellpadding="12">		
		<tr>
			<td align="center">
			<label class="msje_correcto">Escriba su Mensaje:</label>
			<input name="usermsg" id="usermsg" type="text" class="caja_de_texto" maxlength="120" size="63" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="submitmsg" id="submitmsg" type="submit" value="Enviar" class="botones"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" onclick="location.href='<?php echo $file;?>'" value="Regresar" title="Regresar al Inicio" class="botones"/>
			</td>
		</tr>
	</table>
	</div>
	</form>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>