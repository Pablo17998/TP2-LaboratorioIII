<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="style.css" rel="stylesheet">
        <style>
            .pP2 {
                left: 35%;
            }
        </style>
    </head>

    <body>
        <div class="divMain">
            <a href="nuevaMarca.php">Ingresar Nueva Marca</a>

            <div class="divForm">
                <h2 class="h2Form">REGISTRO AUTOMOVIL</h2>

                <div class="divFormContenedor">
                    <form method="POST">
                        <label class="lbl1">Dominio</label>
                        <input class="ip1" type="text" name="dominio" required>

                        <label class="lbl2">Marca</label>
                        <select class="ip2" name="sMarca">
                            <option selected>Seleccione Marca</option>
                            <?php
                                $db = new mysqli("localhost", "root", "", "tp2p2");

                                $query = "select id, marca_vehiculo from marcas";
                                $getMarca = $db->prepare($query);
                                $getMarca->execute();
                                $result = $getMarca->get_result();

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row["id"] . "'>" . $row["marca_vehiculo"] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Marca no Encontrada</option>";
                                }

                                $getMarca->close();
                                $db->close();
                            ?>
                        </select>

                        <label class="lbl3">Fabricacion</label>
                        <input class="ip3" type="text" name="fabricacion" required>

                        <label class="lbl4">Kilometraje</label>
                        <input class="ip4" type="text" name="kilometraje" required>

                        <button type="submit">GUARDAR</button>
                    </form>
                </div>
            
            </div>
            
        </div>
    </body>
</html>

<?php
    if(isset($_POST["dominio"])) {
        $d1 = trim($_POST["dominio"]);
        $d2 = trim($_POST["sMarca"]);
        $d3 = trim($_POST["fabricacion"]);
        $d4 = trim($_POST["kilometraje"]);
        $d5 = trim(0);
        $v = [
            "a"=>$d1,
            "b"=>$d2,
            "c"=>$d3,
            "d"=>$d4,
            "id"=>$d5
        ];
        $v_json = json_encode($v);

        /*print_r($v_json);
        die();*/

        $cr = curl_init();
        curl_setopt($cr, CURLOPT_URL, "http://localhost/tp2/punto-dos/autosUsadosServer.php");
        curl_setopt($cr, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cr, CURLOPT_POSTFIELDS, $v_json);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        $dtCar = curl_exec($cr);
        curl_close($cr);

        $dtCarReturn = json_decode($dtCar);

        echo "<div class='divRetorno'>
            <h2 class='h2Retorno'>Autos Agregados</h2>
            <div class='divParrafo'>
                <p class='pP1'>Dominio</p>
                <p class='pP2'>Marca</p>
                <p class='pP3'>Fabricacion</p>
                <p class='pP4'>Kilometraje</p>
            </div>

            <div class='divCuadro'>
                <div class='divLegajo'>
                    <p>".$dtCarReturn->dominio."</p>
                </div><br>
                <div class='divNyA'>
                    <p>".$dtCarReturn->marca."</p>
                </div><br>
                <div class='divDNI'>
                    <p>".$dtCarReturn->fabricacion."</p>
                </div><br>
                <div class='divTelefono'>
                    <p>".$dtCarReturn->kilometraje."</p>
                </div><br>
            </div>
        </div>";
    }
?>