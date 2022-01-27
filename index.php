<?php 

include 'conexion.php';
$pdo = new Conexion();
function getUserIpAddress() {
    foreach ( [ 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ] as $key ) {
        if ( array_key_exists( $key, $_SERVER ) ) {
            foreach ( array_map( 'trim', explode( ',', $_SERVER[ $key ] ) ) as $ip ) {
                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
                    return $ip;
                }
            }
        }
    }
    return '?';
}

$authip = getUserIpAddress();
$ips = array('AQUI VA LAS IP');
foreach($ips as $ip){
    if($authip == $ip){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($_GET['id']))
            {
                $sql = $pdo->prepare("SELECT * FROM contactos WHERE id=:id");
                $sql->bindValue(':id', $_GET['id']);
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header("HTTP/1.1 200 OK");
                echo json_encode($sql->fetchAll());
                exit;
            } else {
                $sql = $pdo->prepare("SELECT * FROM contactos");
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header("HTTP/1.1 200 OK");
                echo json_encode($sql->fetchAll());
                exit;
            }
        }
    
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $sql = "INSERT INTO contactos (nombre, telefono, email, pais) VALUES (:nombre, :telefono, :email, :pais)";
            $stmt = $pdo->prepare($sql);
            $smt->bindValue(':nombre', $_POST['nombre']);
            $smt->bindValue(':telefono', $_POST['telefono']);
            $smt->bindValue(':email', $_POST['email']);
            $smt->bindValue(':pais', $_POST['pais']);
            $smt->execute();
            $idPost = $pdo->lastInsertId();
            if($idPost){
                header("HTTP/1.1 200 OK");
                echo json_encode($idPost);
                exit;
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'PUT'){
            $sql = "UPDATE contactos SET nombre=:nombre, telefono=:telefono, email=:email, pais=:pais WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $smt->bindValue(':nombre', $_GET['nombre']);
            $smt->bindValue(':telefono', $_GET['telefono']);
            $smt->bindValue(':email', $_GET['email']);
            $smt->bindValue(':pais', $_GET['pais']);
            $smt->bindValue(':id', $_GET['id']);
            $smt->execute();
            if($idPost){
                header("HTTP/1.1 200 OK");
                echo json_encode($idPost);
                exit;
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
            $sql = "DELETE FROM contactos WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $smt->bindValue(':id', $_GET['id']);
            $smt->execute();
            if($idPost){
                header("HTTP/1.1 200 OK");
                echo json_encode($idPost);
                exit;
            }
        }

        header("HTTP/1.1 400 Bad Request");
    }
}
