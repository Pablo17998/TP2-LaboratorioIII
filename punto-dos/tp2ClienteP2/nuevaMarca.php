<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="style.css" rel="stylesheet">
        <style>
            .lbl1 {
                display: flex;
                position: absolute;
                left: 30%;
                font-size: 22.5px;
                font-weight: bold;
            }

            .btnMarca {
                display: flex;
                position: absolute;
                bottom: 50%; right: 45%;
                padding: 1% 2% 1% 2%;
                font-size: 22.5px;
                font-weight: bold;
            }

            .divNyA {
                display: flex;
                position: absolute;
                left: 32.5%;
                width: 24.5vw; height: 5vh;
                border: 2px solid rgb(63, 158, 212);
                justify-content: center;
            }
            .pP2 {
                left: 45%;
            }
        </style>
    </head>

    <body>
        <div class="divMain">
            <a href="autosUsados.php">Ingresar Vehiculo</a>
            <div class="divForm">
                <h2 class="h2Form">REGISTRAR NUEVA MARCA</h2>

                <div class="divFormContenedor">
                    <form method="POST">
                        <label class="lbl1">Marca</label>
                        <input class="ip1" type="text" name="marca" required>

                        <button class="btnMarca" type="submit">GUARDAR</button>
                    </form>
                </div>
            
            </div>
            
        </div>
    </body>
</html>

<?php
    if(isset($_POST["marca"])) {
        $d1 = trim($_POST["marca"]);
        $d2 = trim(0);

        $v = [
            "a"=>$d1,
            "id"=>$d2
        ];
        $v_json = json_encode($v);

        $cr = curl_init();
        curl_setopt($cr, CURLOPT_URL, "http://localhost/tp2/punto-dos/marcaServer.php");
        curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cr, CURLOPT_POSTFIELDS, $v_json);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        $dtMarca = curl_exec($cr);
        curl_close($cr);

        $dtMarcaReturn = json_decode($dtMarca);

        echo "<div class='divRetorno'>
            <h2 class='h2Retorno'>Marca Agregada</h2>
            <div class='divParrafo'>
                <p class='pP2'>Marca</p>
            </div>

            <div class='divCuadro'>
                <div class='divNyA'>
                    <p>".$dtMarcaReturn->marca."</p>
                </div><br>
            </div>
        </div>";
    }
?>