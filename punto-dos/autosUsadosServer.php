<?php
    ///////////////////////////
    // OBTENCION DE DATOS
    $dtCliente = json_decode(file_get_contents("php://input"), true);

    ///////////////////////////
    // CONEXION MYSQL
    $db = new mysqli("localhost", "root", "", "tp2p2");

    ///////////////////////////
    // CRUD
    function guardarAuto($db, $dtCliente) {
        $dominio = trim($dtCliente["a"]);
        $marca_id = trim($dtCliente["b"]);
        $fabricacion = trim($dtCliente["c"]);
        $kilometraje = trim($dtCliente["d"]);

        $query = "insert into autosusados(dominio, marca_id, a_fabricacion, kilometraje) values(?, ?, ?, ?)";
        $send = $db->prepare($query);
    
        if($send === false) {
            return "Error al GUARDAR los datos: ". $send->connect_error;
        }
        else {
            $send->bind_param("ssss", $dominio, $marca_id, $fabricacion, $kilometraje);
            $send->execute();
            $send->close();
        }
    }
    function obtenerDatos($db) {
        $query = "select * from autosusados";
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
    function obtenerDatosById($db, $id) {
        $query = "select * from autosusados where id=?";
        $get = $db->prepare($query);

        if ($get === false) {
            return "Error al preparar la consulta: " . $db->error;
        } else {
            $get->bind_param("i", $id);
            $get->execute();
            $result = $get->get_result();
    
            if ($result === false) {
                return "Error al ejecutar la consulta: " . $db->error;
            } else {
                $auto = $result->fetch_assoc();
                if ($auto) {
                    return json_encode($auto);
                }       
            }
        }
    }
    function modificar($db, $dtCliente) {
        $id = trim($dtCliente["id"]);
        $dominio = trim($dtCliente["a"]);
        $marca_id = trim($dtCliente["b"]);
        $fabricacion = trim($dtCliente["c"]);
        $kilometraje = trim($dtCliente["d"]);

        $query = "update autosusados set dominio=?, marca_id=?, a_fabricacion=?, kilometraje=? where id=?";
        $send = $db->prepare($query);
    
        if($send === false) {
            return "Error al GUARDAR los datos: ". $send->connect_error;
        }
        else {
            $send->bind_param("ssssi", $dominio, $marca_id, $fabricacion, $kilometraje, $id);
            $send->execute();
            $send->close();

            $vReturn = [
                "dominio"=>$dominio,
                "marca"=>$marca_id,
                "fabricacion"=>$fabricacion,
                "kilometraje"=>$kilometraje
            ];
        }
    }
    function eliminar($db, $id) {
        $query = "delete from autosusados where id=?";
        $deleteData = $db->prepare($query);
    
        if ($deleteData === FALSE) {
            echo "Error al PREPARAR la consulta: " . $db->error;
        } else {
            $deleteData->bind_param("i", $id);

            if ($deleteData->execute()) {
                echo "Datos ELIMINADOS correctamente";
            } else {
                echo "Error al ELIMINAR los datos: " . $deleteData->error;
            }
            $deleteData->close();
        }
    }

    ////////////////////////////////////
    // Retorno de JSON
    function retornarDatos($db, $dtCliente) {
        $dominio = trim($dtCliente["a"]);
        $marca_id = trim($dtCliente["b"]);
        $fabricacion = trim($dtCliente["c"]);
        $kilometraje = trim($dtCliente["d"]);

        $query = "select marca_vehiculo from marcas where id=?";
        $get = $db->prepare($query);

        if($get === false) {
            return "Error al OBTENER los datos: ". $send->connect_error;
        }
        else {
            $get->bind_param("i", $marca_id);
            $get->execute();
            $get->bind_result($c1);

            if($get->fetch()) {
                $m_vehiculo = $c1;

                $get->close();

                if(isset($m_vehiculo)) {
                    $vReturn = [
                        "ok"=>true,
                        "dominio"=>$dominio,
                        "marca"=>$m_vehiculo,
                        "fabricacion"=>$fabricacion,
                        "kilometraje"=>$kilometraje
                    ];
                }
            }
        }
        
        return json_encode($vReturn);
    }

    ///////////////////////////
    // SOLICITUDES HTTP
    switch($_SERVER["REQUEST_METHOD"]) {
        case "POST":
                guardarAuto($db, $dtCliente);

                $rCar = retornarDatos($db, $dtCliente);
                echo $rCar;
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
                $id = $dtCliente["id"]; 
                if ($id) { 
                    $datosAlumno = obtenerDatosById($db, $id);
                    echo $datosAlumno;
                } else {
                    obtenerDatos($db);
                }
            break;
        case "DELETE":
                $id = $dtCliente["id"]; 
                if ($id) { 
                    $deleteAlumno = eliminar($db, $id);
                    echo $deleteAlumno;
                } else {
                    echo "Alumno no encontrado";
                }
            break;
        default:
                http_response_code(500);
            break; 
        
    }
?>