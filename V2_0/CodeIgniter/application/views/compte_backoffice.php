<?php if(!($this->session->has_userdata('username'))){
    redirect('compte_connexion');
} ?>
<header class="masthead">
</br>
</br>
</br>
<div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Espace d'administration</h1>
                    </div>
                </div>
            </div>
</header>

<?php if($this->session->userdata('role') == 'F'){ 
?>

<h1>Les Matchs : </h1> <a href="<?php echo base_url();?>index.php/match/creer" class="btn btn-primary">CRÉER UN MATCH</a>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Code du match</th>
      <th scope="col">intitulé du match</th>
      <th scope="col">intitulé du quiz</th>
      <th scope="col">Date de Debut</th>
      <th scope="col">Date de Fin</th>
      <th scope="col">Score</th>
      <th scope="col">relancer/arrêter le match</th>
      <th scope="col">activer/désactiver Match</th>
      <th scope="col">supprimer match</th>
    </tr>
  </thead>
  <tbody>
<?php
if($formateur != NULL){

    foreach($formateur as $quiz){
        if(!isset($traite[$quiz['mat_code']])){
            echo '<tr>';
            $qui_id=$quiz['qui_id'];
            echo '<th><a href="compte/afficher_match/'.$quiz['mat_code'].'">'.$quiz['mat_code'].'</th>';
            echo '<td>'.$quiz['mat_intitule'].'</td>';
            echo '<td>'.$quiz['qui_intitule'].'</td>';
            echo '<td>'.$quiz['mat_date_debut'].'</td>';
            echo '<td>'.$quiz['mat_date_fin'].'</td>';
            echo '<td>'.$quiz['mat_score'].'</td>';
            echo '<td>';
            if($quiz["mat_date_fin"]==null && $quiz['cpt_id'] == $this->session->userdata('id')){
                echo '<a href="compte/desactiver_match/'.$quiz['mat_code'].'">Arrêter le match</a>';
            }else if($quiz["mat_date_fin"]!=null && $quiz['cpt_id'] == $this->session->userdata('id')){
                echo '<a href="compte/activer_match/'.$quiz['mat_code'].'">RAZ le match</a>';
            }
            echo '</td>';
            echo '<td>';
            if($quiz["mat_etat"]=='A' && $quiz['cpt_id'] == $this->session->userdata('id')){
                echo '<a href="compte/A_match/'.$quiz['mat_code'].'">Désactiver le match</a>';
            }else if($quiz["mat_etat"]=='D' && $quiz['cpt_id'] == $this->session->userdata('id')) {
                echo '<a href="compte/D_match/'.$quiz['mat_code'].'">Activer le match</a>';
            }
            echo '</td>';
            echo '<td>';
            if($quiz['cpt_id'] == $this->session->userdata('id')){
                echo '<a href="../index.php/match/supprimer/'.$quiz['mat_code'].'">Supprimer match</a>';
            }
            echo '</td>';
            $traite[$quiz['mat_code']] = 1;
        }
        //echo '<h4>'..'</h4>';
    }
}
echo '</tbody>';
echo '</table>';
echo '</br>';
echo '</br>';
echo '</br>';
echo '<h1>Mes Quiz</h1>';
foreach($qui as $quiz){
    echo $quiz['qui_intitule'];
    echo '</br>';
}
?>

<?php ;}else{
    echo "<h1>Liste de tous les comptes : </h1>";
        if($pseudos != NULL) {
            foreach($pseudos as $login){
                echo "<br />";
                echo " -- ";
                echo $login["cpt_pseudo"];
                echo " -- ";
                if($login["cpt_role"]=='A'){
                    echo 'Administrateur';
                }else{ echo 'Formateur';}
                echo " -- ";
                if($login["cpt_etat"]== 'A'){
                    echo 'Compte Activer';
                }else{ echo 'Compte Désactiver';}
                echo " -- ";
                if($login["cpt_id"] != 1){
                    if($login["cpt_etat"]=='A'){
                    echo '<a href="compte/desactiver/'.$login['cpt_id'].'">Désactiver le Compte</a>';
                    }else {echo '<a href="compte/activer/'.$login['cpt_id'].'">Activer le Compte</a>';}
                
                }
                echo "<br />";
            }
        }
    }
?>

  </tbody>
</table>