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
    <title>Insertion_avis</title>
</head>


<nav class="navbar navbar-expand-lg bg-light">
    <?php
    if(empty($_SESSION)) {
        echo "<p> Vous n'êtes pas connecté</p>";
        redirect("index.php",1);
    }
    else {
        afficheMenu();
    }

    ?>
</nav>

<!-- Menu insertion affiché seulement si l'utilisateur est connecté -->
<main class="container">
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
        <div class="col-md-6 px-0">
            <h1 class="display-4 fst-italic">Insertion d'un nouvel avis</h1>
            <p class="lead my-3">Insérer un nouvel avis</p>
            <?php
            echo '<p>Vous êtes connectés avec le compte ' . $_SESSION['username'] . '</p>';
            ?>
        </div>
    </div>

    <article>

        <?php
        echo '<h2>Insérer un nouvel avis :</h2>';
        afficheFormulaireAjoutAvis();
        if(!empty($_POST) && isset($_POST["note"]) && isset($_POST ["com"]) && isset($_POST ["chaussure"])){
            #fonction qui ajoute un avis si les données sont bien renseignées
            ajoutAvis($_POST['note'],$_POST ['com'],$_POST ['chaussure']);
        }
        ?>
    </article>

    <aside>
        <?php
        #Affiche le tableau des avis
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

</html>