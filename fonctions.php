<?php

// Verifie si le compte existe a l'aide de la base de donné
function compteExiste($mail, $pass)
{
    $retour = false;
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');
    $mail = $madb->quote($mail);
    $pass = $madb->quote($pass);
    $requete = "SELECT login,password FROM comptes WHERE login = $mail AND password = $pass";
    //var_dump($requete);echo "<br/>";
    $resultat = $madb->query($requete);
    $tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
    if (sizeof($tableau_assoc) != 0) $retour = true;
    return $retour;
}

//*******************************************************************************************

// Vérifie si l'utilisateur qui se connecte est un administrateur
function isAdmin($login)
{
    $retour = false;
    // A faire
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');
    $login = $madb->quote($login);
    $requete = "SELECT statut FROM comptes WHERE login = $login;";
    $resultat = $madb->query($requete);
    if ($resultat) {
        $res = $resultat->fetch(PDO::FETCH_ASSOC);
        $retour = $res['statut'];
    }
    return $retour;
}

//*******************************************************************************************

// Fonction qui liste tous les produits de la base avisClientsProduits*
function listeProd()
{

    $retour = false;
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');
    $requete = "SELECT DISTINCT prixTTC,designation FROM produit;";
    $resultat = $madb->query($requete);
    if ($resultat) {
        $retour = $resultat->fetchAll(PDO::FETCH_ASSOC);
    }
    return $retour;
}

//*******************************************************************************************

// Fonction qui liste tous les avis de la base avisClientsProduits
function listeAvis()
{
    $retour = false;
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');
    $requete = "SELECT prenom,designation as 'paire de chaussures',note,commentaire FROM avisclient INNER JOIN produit ON produit.idProduit = avisclient.idProduit INNER JOIN client on client.email = avisclient.email;";
    $resultat = $madb->query($requete);
    if ($resultat) {
        $retour = $resultat->fetchAll(PDO::FETCH_ASSOC);
    }
    return $retour;
}
//*******************************************************************************************

//Fonction qui liste les produits avec un menu deroulant a partir de leur prix
function listeProduitsParPrix($prix){
    $retour = false ;
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');

    $requete = "SELECT designation as 'Modele de chaussure au prix de ".$prix." : ' FROM produit where prixTTC = $prix;";
    $resultat = $madb->query($requete);
    if($resultat){
        $retour = $resultat->fetchAll(PDO::FETCH_ASSOC);
    }


    return $retour;

}
//*******************************************************************************************


//Fonction qui ajoute des avis elle est appelé par un test qui verifie les données envoyées par le formulaire
function ajoutAvis($note,$com,$chaussures)
{
    $retour = 0;
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');
    $prenom = ($_SESSION['username']);
    $chaussures = $madb->quote($chaussures);
    $requete = "SELECT idProduit FROM produit WHERE designation=$chaussures;";
    $resultat = $madb->query($requete);
    if ($resultat) {
        $resultat = $resultat->fetchAll(PDO::FETCH_ASSOC);
    }

    $idProd = $resultat[0]['idProduit'];
    $idProd = (int)$idProd;
    $note = (int)$note;
    $prenom = (string)$prenom;
    $com = $madb->quote($com);
    $prenom = $madb->quote($prenom);



    if (isset($resultat)) {
        $requete = "INSERT INTO avisclient (email,idProduit,note,commentaire) VALUES ($prenom,$idProd,$note,$com);";
        try {
            $resultat = $madb->exec($requete);
        } catch (exception $e) {

        }
        if ($resultat == false) $retour = 0;
        return $retour;
    }
}

//*******************************************************************************************

// Fournit le top 3 des chaussures
function topchaussure(){
    $retour = '';
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');

    $requete = "select DISTINCT designation,image from avisclient INNER JOIN produit on avisclient.idProduit = produit.idProduit group by note ORDER BY note DESC LIMIT 3;";
    $resultat =$madb->query($requete);
    $resultat=$resultat->fetchAll(PDO::FETCH_ASSOC);
    if(isset($resultat)) {
        $retour = $resultat;
    }

    foreach ($resultat as $res) {
        $retourne[] = $res["image"];
        $retourne[] = $res["designation"];
    }


    return $retourne;
}

//*******************************************************************************************

//Donne l'avis par prénom
function listeAvisPrenom($prenom)
{
    $retour = false;
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');
    $prenom = $madb->quote($prenom);
    $requete = "SELECT prenom,designation as 'paire de chaussures',note,commentaire FROM avisclient INNER JOIN produit ON produit.idProduit = avisclient.idProduit INNER JOIN client on client.email = avisclient.email WHERE prenom = $prenom;";
    $resultat = $madb->query($requete);
    if ($resultat) {
        $retour = $resultat->fetchAll(PDO::FETCH_ASSOC);
    }
    return $retour;
}
//*******************************************************************************************

//Fonction qui permet de modifier un avis
function modifAvis($choix_avi){
    $retour=0;
    $madb = new PDO('sqlite:bdd/avisClientsProduits.sqlite');
    $prenom = $madb->quote($choix_avi["prenom"]);
    $note = $madb->quote($choix_avi["note"]);
    $com = $madb->quote($choix_avi["com"]);
    $requete = " UPDATE avisclient SET note = $note, commentaire = $com WHERE email = (SELECT email FROM client WHERE prenom = $prenom);  ";
    $resultat = $madb->exec($requete);
    if ($resultat == false ) {
        $retour = 0;
        echo "<p>La modification à échoué.</p>";
    }
    else{
        $retour = $resultat;
    }

    return $retour;
}
//*******************************************************************************************

//Fontion qui redirige vers une page donnée la page et le temps avant l'envoie vers la page
function redirect($url,$tps)
{
$temps = $tps * 1000;

echo "
<script type=\"text/javascript\">\n"
            . "<!--\n"
            . "\n"
            . "function redirect() {\n"
            . "window.location='" . $url . "'\n"
            . "}\n"
            . "setTimeout('redirect()','" . $temps ."');\n"
            . "\n"
            . "// -->\n"
            . "

</script>\n";

}

//*******************************************************************************************

//Fonction qui affiche un tableau à partir d'un array (tableau), il met en forme
function afficheTableau($tab)
{
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';// les entetes des colonnes qu'on lit dans le premier tableau par exemple
    foreach ($tab[0] as $colonne => $valeur) {
        echo "<th scope='col'>$colonne</th>";
    }
    echo "</tr>\n";
    echo '</thead>';
    echo '<tbody class="table-group-divider">';
    // le corps de la table
    foreach ($tab as $ligne) {
        echo '<tr>';
        foreach ($ligne as $cellule) {
            echo "<td>$cellule</td>";
        }
        echo "</tr>\n";
    }
    echo '</tbody>';
    echo '</table>';
}


?>