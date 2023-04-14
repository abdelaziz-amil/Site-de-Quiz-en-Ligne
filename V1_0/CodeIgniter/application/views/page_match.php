<div class="bg-black">
    </br>
    </br>
    </br>
    <?php
if($match != NULL) {
    //affichage titre
    foreach($match as $qui){
        if (!isset($traite[$qui["qui_id"]])){
            $qst_id=$qui["qui_id"];
            echo '<h2 class="text-white ml-4"> Quiz : '.$qui['qui_intitule'].'<h1>';
            $traite[$qui["qui_id"]]=1;
        }
    }
//affichage question reponse
    foreach($match as $qst){
        if (!isset($traite[$qst["qst_id"]])){
            $qst_id=$qst["qst_id"];
            echo '<h3 class="text-white text-align-center">'.$qst['qst_intitule_question']."</h3>";
                foreach($match as $rep){
                    if(strcmp($qst_id,$rep["qst_id"])==0){
                        echo '<h4 class="text-white ml-4">'.$rep['rep_texte_reponse']."</h4>";
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