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

<?php
//affichage question reponse
    foreach($match as $qst){
        if (!isset($traite[$qst["qst_id"]])){
            $qst_id=$qst["qst_id"];
            echo '<h3 class="text-align-center">'.$qst['qst_intitule_question']."</h3>";
            foreach($match as $rep){
                if(strcmp($qst_id,$rep["qst_id"])==0){
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="radio" name="'.$qst_id.'" id="'.$rep["rep_id"].'">';
                        echo $rep['rep_texte_reponse'];
                        echo '</div>';
                    }
                }
            $traite[$qst["qst_id"]]=1;
        }
    }
}else{
    echo "aucune question dans le quiz du match";
}

?>
</div>
