<?php

$exp=$_POST["email"];
$sujet=$_POST["sujet"];
$message=$_POST["message"];

if (mail($exp, $sujet, $message)) {
    echo "Email envoyé avec succès à $exp ...";
} else {
    echo "Échec de l'envoi de l'email...";
}

?>

