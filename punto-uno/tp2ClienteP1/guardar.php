<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de Alumnos</title>
        <link href="style.css" rel="stylesheet">
    </head>

    <body>
        <div class="divMain">
            
            <div class="divForm">
                <h2 class="h2Form">AGREGAR ALUMNOS/S</h2>

                <div class="divFormContenedor">
                    <form method="POST">
                        <label class="lbl1">Legajo</label>
                        <input class="ip1" type="text" name="legajo" required>

                        <label class="lbl2">Nombre y Apellido</label>
                        <input class="ip2" type="text" name="fullname" required>

                        <label class="lbl3">DNI</label>
                        <input class="ip3" type="text" name="dni" required>

                        <label class="lbl4">Telefono</label>
                        <input class="ip4" type="text" name="telefono" required>

                        <label class="lbl5">Correo Electronico</label>
                        <input class="ip5" type="email" name="email" required>

                        <button type="submit">GUARDAR</button>
                    </form>
                </div>
            
            </div>
            
        </div>
    </body>
</html>

<?php
    if(isset($_POST["legajo"])) {
        $d1 = trim($_POST["legajo"]);
        $d2 = trim($_POST["fullname"]);
        $d3 = trim($_POST["dni"]);
        $d4 = trim($_POST["telefono"]);
        $d5 = trim($_POST["email"]);
        $d6 = trim(0);
        $vector = [
            "a" => $d1, 
            "b" => $d2, 
            "c" => $d3, 
            "d" => $d4, 
            "e" => $d5,
            "id" => $d6
        ];
        $vector_json = json_encode($vector);

        /*print_r($vector_json);
        die();*/

        $cr = curl_init();
        curl_setopt($cr, CURLOPT_URL, "http://localhost/tp2/punto-uno/alumnos.php");
        curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cr, CURLOPT_POSTFIELDS, $vector_json);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        $dtCliente = curl_exec($cr);
        curl_close($cr);

        /*print_r($dtCliente);
        die();*/

        $dtReturn = json_decode($dtCliente);

        /*print_r($dtReturn->legajo);
        die();*/

        echo "<div class='divRetorno'>
                <h2 class='h2Retorno'>Alumno Agregado</h2>
                <div class='divParrafo'>
                    <p class='pP1'>Legajo</p>
                    <p class='pP2'>Nombre y Apellido</p>
                    <p class='pP3'>Documento</p>
                    <p class='pP4'>Telefono</p>
                </div>

                <div class='divCuadro'>
                    <div class='divLegajo'>
                        <p>".$dtReturn->legajo."</p>
                    </div><br>
                    <div class='divNyA'>
                        <p>".$dtReturn->fullname."</p>
                    </div><br>
                    <div class='divDNI'>
                        <p>".$dtReturn->dni."</p>
                     </div><br>
                    <div class='divTelefono'>
                        <p>".$dtReturn->telefono."</p>
                    </div><br>
                </div>
            </div>";
    }
?> 