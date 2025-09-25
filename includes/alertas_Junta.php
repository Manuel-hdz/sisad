<?php
function mostrarAlertaJunta($departamento){
    $actividad = obtenerDatos($departamento);
    if($actividad[0] > 0) {
    ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

        <link rel="stylesheet" type="text/css" href="../../includes/alertas//sample.css" />
        <script type="text/javascript" src="../../includes/alertas//popup-window.js"></script>
    </head>
    
    <body>
        <script type="text/javascript" language="javascript">
            setTimeout(
                "popup_show('popup_alertJunta', 'popup_drag_alertJunta', 'popup_exit_alertJunta', 'screen-top-lef', 0, 0);",
                1000);
        </script>
        <!-- ********************************************************* Popup Window **************************************************** -->
        <div class="sample_popup" id="popup_alertJunta" style="display: none;">
            <div align="center" class="menu_form_header" id="popup_drag_alertJunta">
                <img class="menu_form_exit" id="popup_exit_alertJunta" src="../../includes/alertas/aviso-form-exit.png"
                    title="Posponer" />
                AVISO JUNTA ADMINISTRATIVA
            </div>
            
            <div class="menu_form_body_red">
                <?php
                if($actividad[0] > 1) {
                ?>
                <form name="frm_alertasJunta" action="actAlerta_Junta.php" method="post">
                    <font color="white">
                        <table>
                            <tr>
                                <td colspan="2" align="center">
                                    <strong>
                                        <p>TIENES <?php echo $actividad[0]; ?> ACTIVIDADES QUE TERMINARON SU FECHA
                                            DE ENTREGA</p>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <u>Se Recomienda Consultar el Detalle</u>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"><strong><br>&iquest;Ver Actividades?</strong></td>
                            </tr>

                            <tr>
                                <td align="center" colspan="2">
                                    <input type="hidden" name="txt_consulta" id="txt_consulta" value="<?php echo $actividad[1]; ?>">

                                    <input name="btn_aceptar" type="submit" value="Aceptar" class="botones"
                                        title="Revisar Datos Ahora!" onMouseOver="window.status='';return true" />
                                </td>
                            </tr>
                        </table>
                    </font>
                </form>
                <?php
                }
                else {
                ?>
                    <font color="white">
                        <table>
                            <tr><td>.</td></tr>
                            <tr><td>.</td></tr>
                            <tr><td>.</td></tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <p>
                                        LA ACTIVIDAD <strong><?php echo $actividad[1]; ?></strong> TERMINO SU FECHA DE ENTREGA
                                    </p>
                                </td>
                            </tr>
                            <tr><td>.</td></tr>
                            <tr><td>.</td></tr>
                            <tr><td>.</td></tr>
                        </table>
                    </font>
                <?php
                }
                ?>
            </div>

        </div>
    </body>
    <?php
    }
}

function obtenerDatos($departamento){
    $conn = conecta("bd_gerencia");

    $stm_sql = 
    "SELECT * 
    FROM  `actividades_junta` 
    JOIN responsables_junta
    USING ( id_actividad ) 
    WHERE estado LIKE  'ALERTA'
    AND area LIKE  '$departamento'";

    $rs = mysql_query($stm_sql);
    $registros = mysql_num_rows($rs);
    if($registros == 1) {
        $datos = mysql_fetch_array($rs);
        return array($registros,$datos['actividad']);
    }
    else {
        return array($registros,$stm_sql);
    }
}
?>