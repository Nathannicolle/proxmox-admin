


<div class="FormContact">
    <h1>Vous avez besoin d'aide ? Posez-nous votre question ici</h1>
    <form class="formContact" action="contact.php">
        <!-- <div class="form formName">
            <label for="nom">Nom & prénom</label><br>
            <input type="text" id="nom" name="nom" placeholder="Votre nom">
        </div>
        -->
        <div class="form formSujet">
            <label for="sujet">Sujet</label><br>
            <input type="text" id="sujet" name="sujet" placeholder="L'objet de votre message">
        </div>
        <div class="form formEmail">
            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" placeholder="Votre email">
        </div>
        <div class="form formMessage">
            <label for="message">Message</label><br>
            <textarea id="message" name="message" placeholder="Votre message" style="height:100px"></textarea>
        </div>
        <div class="form formEnvoyer">
            <input type="submit" value="Envoyer">
        </div>
    </form>
</div>
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

