<!-- /*/* ==============================
// Nom du fichier : page_match.php
// Auteur: Abdelaziz AMIL
// Date de création : 
// Version: 
// ++
// Description: affiche les données d'un match.
//
//==/-->
<header class="masthead">
</br>
</br>
</br>
<div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase"> quiz : <?php
if($match != NULL) {
    //affichage titre
    foreach($match as $qui){
        if (!isset($traite[$qui["qui_id"]])){
            $qst_id=$qui["qui_id"];
            echo $qui['qui_intitule'];
            $traite[$qui["qui_id"]]=1;
        }
    }?>
    </h1>
                    </div>
                </div>
            </div>
</header>
<div>
<form method="get" action="<?php echo'../score/'.$code_match.'/20';?>">
<?php
//affichage question reponse
$cpt = 0;
    foreach($match as $qst){
        if (!isset($traite[$qst["qst_id"]])){
            $qst_id=$qst["qst_id"];
            echo '<h3 class="text-align-center">'.$qst['qst_intitule_question']."</h3>";
            foreach($match as $rep){
                if(strcmp($qst_id,$rep["qst_id"])==0){
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="radio" name="'.$cpt.'" value="'.$rep["rep_id"].'">';
                        echo $rep['rep_texte_reponse'];
                        echo '</div>';
                    }
                }
            $traite[$qst["qst_id"]]=1;
            $cpt++;
        }
    }
}else{
    echo "aucune question dans le quiz du match";
}
echo '<input type="hidden" name="nb_qst" value="'.$cpt.'" />';
echo '</br><button type="submit" class="btn btn-primary">Voir Résultat</button>'
?>
</form>
</div>
