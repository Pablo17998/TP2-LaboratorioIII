<?php
    ///////////////////////////
    // OBTENCION DE DATOS
    $dtCliente = json_decode(file_get_contents("php://input"), true);

    ///////////////////////////
    // CONEXION MYSQL
    $db = new mysqli("localhost", "root", "", "tp2p2");

    ///////////////////////////
    // CRUD
    function guardarMarca($db, $dtCliente) {
        $marca = trim($dtCliente["a"]);

        $query = "insert into marcas(marca_vehiculo) values(?)";
        $send = $db->prepare($query);

        if($send === false) {
            return "Error al GUARDAR los datos: ". $send->connect_error;
        }
        else {
            $send->bind_param("s", $marca);
            $send->execute();
            $send->close();
        }
    }

    function obtenerDatos($db) {
        $query = "select * from marcas";
        $get = $db->prepare($query);
    
        if ($get === false) {
            return "Error al preparar la consulta: " . $db->error;
        } else {
            $get->execute();
            $result = $get->get_result();
            
            if ($result === false) {
                return "Error al ejecutar la consulta: " . $db->error;
            } else {
                $vReturn = [];
                while ($row = $result->fetch_assoc()) {
                    $vReturn[] = $row;
                }
                echo json_encode($vReturn);
            }
        }
    }

    function modificar($db, $dtCliente) {
        $id = trim($dtCliente["id"]);
        $marca = trim($dtCliente["a"]);

        $query = "update marcas set marca_vehiculo=? where id=?";
        $update = $db->prepare($query);

        if ($update === false) {
            return "Error al preparar la consulta: " . $db->error;
        } else {
            $update->bind_param("si", $marca, $id);
            $update->execute();

            if ($update->affected_rows > 0) {
                return "Datos actualizados correctamente";
            } else {
                return "No se encontraron registros para actualizar o los datos no cambiaron";
            }
        }
        $update->close();
    }

    function eliminar($db, $id) {
        $query = "delete from autosusados where id=?";
        $deleteData1 = $db->prepare($query);

        if($deleteData1 === false) {
            return "Error al preparar la consulta: " . $db->error;
        }
        else {
            $deleteData1->bind_param("i", $id);
            $deleteData1->execute();
            $deleteData1->close();
        }

        $query = "delete from marcas where id=?";
        $deleteData2 = $db->prepare($query);
    
        if($deleteData2 === false) {
            return "Error al preparar la consulta: " . $db->error;
        }
        else {
            $deleteData2->bind_param("i", $id);
            $deleteData2->execute();
            $deleteData2->close();
        }
    }

    ////////////////////////////////////
    // Retorno de JSON
    function retornarDatos($dtCliente) {
        $marca = trim($dtCliente["a"]);

        $vReturn = [
            "ok"=>true,
            "marca"=>$marca
        ];
        return json_encode($vReturn);
    }

    ///////////////////////////
    // SOLICITUDES HTTP
    switch($_SERVER["REQUEST_METHOD"]) {
        case "POST":
                guardarMarca($db, $dtCliente);

                $marca = retornarDatos($dtCliente);
                echo $marca;
            break;
        
        case "PUT":
                if (isset($dtCliente["id"])) {
                    $resultado = modificar($db, $dtCliente);
                    echo $resultado;
                } else {
                    echo "ID no proporcionado";
                }
            break;

        case "GET": 
                obtenerDatos($db);
            break;
        
        case "DELETE":
                $id = $dtCliente["id"]; 
                if ($id) { 
                    $deleteMarca = eliminar($db, $id);
                    echo $deleteMarca;
                } else {
                    echo "Marca no encontrada";
                }
            break;

        default:
                http_response_code(500);
                $db->close();
            break;
    }
?>