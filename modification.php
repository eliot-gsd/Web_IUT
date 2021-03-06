<!--Démarrage des sessions et appelle des fonctions -->

<?php session_start();?>
<?php
include 'fonctions.php';
include 'formulaire.php';


?>
<!DOCTYPE html>
<html lang='fr'>

<!-- Fonction appelées ainsi que les links (bootstrap) -->
<head>
    <meta charset="utf-8">
    <link href="style1.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="verif_form.js"></script>

    <title>Modification produit</title>
</head>

<body>

<!--Nav bar menu-->
<nav class="navbar navbar-expand-lg bg-light">
    <?php
    if(empty($_SESSION) || isset ($_SESSION["statut"]) && $_SESSION["statut"] !="administrateur") {
        echo "<p> Vous n'etes pas connecté ou pas admin</p>";
        redirect("index.php",1);
    }
    else {

        afficheMenu();
    }

    ?>
</nav>

<!-- Page pour la modification d'un avis-->
<main class="container">
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
        <div class="col-md-6 px-0">
            <h1 class="display-4 fst-italic">Modification d'un avis</h1>
            <p class="lead my-3">Page accessible uniquement en administrateur pour pouvoir modifier un avis</p>
            <?php
            echo '<p>Vous êtes connectés avec le compte ' . $_SESSION['username'] . '</p>';
            ?>
        </div>
    </div>

<article>
    <!--Les tests pour modifier les avis -->

    <?php
    echo '<h2>Modifier les avis :</h2>';
    $prod = listeAvis();
    afficheFormulaireChoixModifAvis($prod);

    // Si la session n'est pas vide et que le commentaire est bien rentré dans le post
    //Cela affiche le formulaire
    if(isset($_SESSION) && isset ($_POST["com"])) {
        $choix_avi=listeAvisPrenom($_POST["com"]);
        afficheFormulaireModifAvis($choix_avi);

        // Verification du captcha si il est bon la fonction modifAvis est envoyé
        if(empty($_SESSION) || isset($_POST["note"]) && isset($_POST["com"]) && $_SESSION['code'] == $_POST['captcha']){
            modifAvis($_POST);
        }
        if(empty($_SESSION) || isset($_POST["note"]) && isset($_POST["com"]) && $_SESSION['code'] != $_POST['captcha']) {
            echo ' Votre captcha  est incorrect';
            }

    }
    
    ?>

</article>


<aside>
    <!--Liste l'ensemble des avis-->
    <?php
    echo '<h2> Liste des avis:</h2>';
    $prod = listeAvis();
    if ($prod) afficheTableau($prod);
    ?>
</aside>
</main>




<!-- Affichage du footer -->
<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-8 my-4 border-top">
        <div class="col-md-4 align-items-center">
            <p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?></p>
            <a href="javascript:history.back()">Retour à la page précédente</a>

        </div>
    </footer>
</div>
</body>

</html>