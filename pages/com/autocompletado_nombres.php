<?php
$html = '';
    if (isset($_POST['key'])) {
        $html .= '<div>
            <a class="suggest-element form-control" data="'.utf8_encode("fv").'" id="empleado'.$_POST['key'].'">'.$_POST['key'].'</a>
        </div>';
    } else {
        echo "Error en el autocompleatado";
    }

    echo $html;
    
?>