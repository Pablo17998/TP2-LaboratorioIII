<?php
    ////////////////////////////////////
    // Obtencion de Datos del Cliente
    $dtCliente = json_decode(file_get_contents("php://input"), true);

    ////////////////////////////////////
    // Conexion con MySQL
    $db = new mysqli("localhost", "root", "", "facultad");

        /*if($db->connect_error) {
            return "Conexion Fallida: ". $db->connect_error;
            die();
        }
        else {
            return "Conexion Exitosa<br><br>";
        }*/

    ////////////////////////////////////
    // CRUD
    function guardar($db, $dtCliente) {
        $legajo = trim($dtCliente["a"]);
        $fullname = trim($dtCliente["b"]);
        $dni = trim($dtCliente["c"]);
        $telefono = trim($dtCliente["d"]);
        $email = trim($dtCliente["e"]);

        $query = "insert into alumnos(legajo, nombre_completo, dni, telefono, email) values(?, ?, ?, ?, ?)";
        $send = $db->prepare($query);

        if($send === false) {
            return "Error al GUARDAR los datos: ".$db->connect_error;
        }
        else {
            $send->bind_param("sssss", $legajo, $fullname, $dni, $telefono, $email);
            $send->execute();
            $send->close();
        }
    }
    function obtenerDatos($db) {
        $query = "select * from alumnos";
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
        $query = "select * from alumnos where id=?";
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
                $alumno = $result->fetch_assoc();
                if ($alumno) {
                    return json_encode($alumno);
                }       
            }
        }
    }
    function modificar($db, $dtCliente) {
        $id = trim($dtCliente["id"]);
        $legajo = trim($dtCliente["a"]);
        $fullname = trim($dtCliente["b"]);
        $dni = trim($dtCliente["c"]);
        $telefono = trim($dtCliente["d"]);
        $email = trim($dtCliente["e"]);

        $query = "update alumnos set legajo=?, nombre_completo=?, dni=?, telefono=?, email=? where id=?";
        $update = $db->prepare($query);

        if ($update === false) {
            return "Error al preparar la consulta: " . $db->error;
        } else {
            $update->bind_param("sssssi", $legajo, $fullname, $dni, $telefono, $email, $id);
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
        $query = "delete from alumnos where id=?";
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
    function retornarDatos($dtCliente) {
        $legajo = trim($dtCliente["a"]);
        $fullname = trim($dtCliente["b"]);
        $dni = trim($dtCliente["c"]);
        $telefono = trim($dtCliente["d"]);
        $email = trim($dtCliente["e"]);
        $vReturn = [
            "ok"=>true,
            "legajo"=>$legajo,
            "fullname"=>$fullname,
            "dni"=>$dni,
            "telefono"=>$telefono,
            "email"=>$email
        ];
        return json_encode($vReturn);
    }

    ////////////////////////////////////
    // Solicitudes HTTP
    switch($_SERVER["REQUEST_METHOD"]) {
        case "POST":
                guardar($db, $dtCliente);
                $devolverDatos = retornarDatos($dtCliente);

                echo $devolverDatos;
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
                $db->close();
            break;
    }
?>