<?php


$dest = "destinataire@gmail.com";
$sujet = "Email de test";
$corp = "Salut ceci est un email de test envoyer par un script PHP";
$headers = "From: VotreGmailId@gmail.com";
if (mail($dest, $sujet, $corp, $headers)) {
    echo "Email envoyé avec succès à $dest ...";
} else {
    echo "Échec de l'envoi de l'email...";
}
/*
$exp=$_POST["email"];
$sujet=$_POST["sujet"];
$message=$_POST["message"];



$retour = mail($exp, $sujet, $message);
if ($retour)
    echo '<p>Votre message a bien été envoyé.</p>';*/
?>

