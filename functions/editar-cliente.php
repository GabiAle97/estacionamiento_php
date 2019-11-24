<?php 
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_POST['key'])) {
        $url = $_POST['key'];
    }
    include __DIR__ . '/../functions/revisar-permiso.php';
    //si editarCliente existe significa que se quiere editar al empleado
    if(isset($_SESSION['cargo'])){
        if(consultar_permiso($_SESSION['cargo'], 8)){
            //si editarCliente existe significa que se quiere editar al empleado
            if(isset($_POST['editarCliente'])) {
                include __DIR__ . '/../includes/connect.php';
                $id = $_POST['editarCliente'];
            
                $sql = 'SELECT DNI, ID, PATENTE FROM cliente WHERE PATENTE = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
            
                $cliente = $stmt->fetch();
                //mostrar formulario
                $titulo = 'Editar cliente';
                ob_start();
                include __DIR__ . '/../templates/registro-cliente.html.php';
                $contenido = ob_get_clean();
                print_r($contenido);
            
                
            } else {
                $id = $_POST['ID'];
                include __DIR__ . '/../includes/connect.php';
                $sql = 'SELECT * FROM `estadia` WHERE PATENTE = :id';
                
                $upEst = $pdo->prepare($sql);
                $upEst->bindValue(':id', $id);
                $upEst->execute();
            
                $sql = 'SELECT * FROM `historialpagos` WHERE PATENTE = :id';
                
                $upHist = $pdo->prepare($sql);
                $upHist->bindValue(':id', $id);
                $upHist->execute();
                $sql = 'DELETE FROM `estadia` WHERE PATENTE = :id';
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
                $sql = 'DELETE FROM `historialpagos` WHERE PATENTE = :id';
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
            
                $sql = 'UPDATE cliente SET
                            DNI = :dni,
                            PATENTE = :patente,
                            ID = :tipo
                        WHERE PATENTE = :id
                        ';
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':dni', $_POST['DNI']);
                $stmt->bindValue(':patente', strtoupper($_POST['PATENTE']));
                $stmt->bindValue(':tipo', $_POST['TIPO']);
                $stmt->bindValue(':id', $id);
            
                $stmt->execute();
                if ($_POST['TIPO'] == $_POST['clientePrev']) {
                    while ($EdEst = $upEst->fetch()) {
                        $sql = 'INSERT INTO estadia (
                                PATENTE, 
                                ID_USUARIO,
                                ID_PRECIO,
                                INGRESO,
                                EGRESO,
                                TOTAL
                                ) VALUES (
                                :patente,
                                :id_usuario,
                                :id_precio,
                                :ingreso,
                                :egreso,
                                :total
                                )
                            ';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':patente', strtoupper($_POST['PATENTE']));
                        $stmt->bindValue(':id_usuario', $EdEst['ID_USUARIO']);
                        $stmt->bindValue(':id_precio', $EdEst['ID_PRECIO']);
                        $stmt->bindValue(':ingreso', $EdEst['INGRESO']);
                        $stmt->bindValue(':egreso', $EdEst['EGRESO']);
                        $stmt->bindValue(':total', $EdEst['TOTAL']);
                
                        $stmt->execute();
                    }
                    while ($EdHist = $upHist->fetch()) {
                        $sql = 'INSERT INTO historialpagos(
                                PATENTE, 
                                ID_PRECIO,
                                FECHA_PAGO
                                ) VALUES (
                                :patente,
                                :id_precio,
                                :fecha_pago
                                )
                            ';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':patente', strtoupper($_POST['PATENTE']));
                        $stmt->bindValue(':id_precio', $EdHist['ID_PRECIO']);
                        $stmt->bindValue(':fecha_pago', $EdHist['FECHA_PAGO']);
                
                        $stmt->execute();
                    }
                }
            
                ob_start();
                include __DIR__ . '/../functions/todos-clientes.php';
                $contenido = ob_get_clean();
                print_r($contenido);
            }
        } 
        else {
            $_SESSION['error'] = 'No posee permisos para realizar esa acción';
            ob_start();
            include __DIR__ . '/../templates/home-empleado.html.php';
            $contenido = ob_get_clean();
            print_r($contenido);
        }
    }
 	else {
        $_SESSION['error'] = 'No se encontró una sesión para ingresar a la URL';
        ob_start();
        include __DIR__ . '/../index.php';
        $contenido = ob_get_clean();
        print_r($contenido);
    }