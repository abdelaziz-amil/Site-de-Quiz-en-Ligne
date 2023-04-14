<header class="masthead">
            <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Le Site de Quiz en ligne</h1>
</br>
                        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

                        <?php echo form_open('page_accueil'); ?>
                            <div class="form-group mx-sm-3 mb-2">
                                <input type="text" class="form-control form-control-lg" name="code_match" id="code_match" placeholder="Entrez le code d'un match">
                            </div>
</br>
                            <button type="submit" class="btn btn-primary" href="<?php echo base_url();?>index.php/match/">COMMENCER</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
            <div class="container mt-2 px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Actualité</th>
                            <th scope="col">Date de publication</th>
                            <th scope="col">Auteur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($actualite != NULL) {
                                foreach($actualite as $actu){
                                    echo "<tr>";
                                    echo '<td><a href = "actualite/afficher/'.$actu['act_id'].'">'.$actu['act_titre'].'</a></td>';
                                    echo "<td>".$actu['act_contenu']."</td>";
                                    echo "<td>".$actu['act_date']."</td>";
                                    echo "<td>".$actu['cpt_pseudo']."</td>";
                                    echo "</tr>";
                                }
                            }
                            else {echo "<br />";
                            echo "Aucune actualité !";
                            }
                        ?>
                        
                    </tbody>
                </table>
            </div>
            