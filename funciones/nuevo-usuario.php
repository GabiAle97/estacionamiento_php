<?php
    if(isset($_SESSION)){ //existe la sesion?
        if(isset($_POST)){ //existe mensaje post?
            require("incuded/connect.php");

            $nombre=$_POST["nombre"];
            $usuario=$_POST["usuario"];
            $password=$_POST["password"];
            $id=$_POST["id"];


            $query=$conn->prepare("INSERT INTO usuario(`nombre`,`usuario`,'password', `ID`)VALUES(:nombre,:usuario,:PASSWORD,:ID)");
            $query->bindParam(':nombre',$nombre);
            $query->bindParam(':usuario',$usuario);
            $query->bindParam(':ID',$id);
            $query->bindParam(':PASSWORD',$password);

            $query->execute();
        }
        else{ //redireccionar
            
        }
    }
    else{
        header("Location:../login.php");
    }
?>
