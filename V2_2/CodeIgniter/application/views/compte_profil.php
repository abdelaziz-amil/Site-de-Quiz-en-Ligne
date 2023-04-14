<?php if(!($this->session->has_userdata('username'))){
    redirect('compte_connexion');
} ?>
<header class="masthead text-white text-center">
</br>
</br>
</br>
<h2>Espace d'administration</h2>
<br />
<h2>Session ouverte ! Bienvenue <?php echo $pseudo->cpt_pseudo; ?>
</h2>
<div class="container align-items-center justify-content-center">
</br>
</br>
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Prenom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($pseudo != NULL) {
                                echo "<tr>";
                                echo '<td class="text-white">'.$pseudo->pfl_nom."</td>";
                                echo '<td class="text-white">'.$pseudo->pfl_prenom."</td>";
                                echo "</tr>";
                            }
                            else {echo "<br />";
                            echo "Aucune actualité !";
                            }
                        ?>
                        
                    </tbody>
                </table>
                <a href="<?php echo base_url();?>index.php/compte/modifier"><button type="submit" class="btn btn-primary">Modifier Mes Informations</button></a>
            </div>
</header>
