<header class="masthead">
    
<div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Création de Compte</h1>
</br>

                        <?php echo validation_errors(); ?>
                        <?php echo form_open('match_creer'); ?>
                        <div class="form-group mx-sm-3 mb-2">
                                <input type="input" class="form-control form-control-lg" name="intitule" placeholder="Intitulé du match"/><br />
                                <label class="text-white">Date de début du match</label>
                                <input type="date" class="form-control form-control-lg" name="debut" min="2022-01-01" max="2030-12-31"/><br />
                                <input type="input" class="form-control form-control-lg" name="AouD" placeholder="Affichage du corrigé A(activer)/D(désactiver)"/><br />
                                <?php
                                echo '<select class="form-control form-control-lg" name="quiz" id="quiz">';
                                echo '<option value="">--Choisir un Quiz--</option>';
                                foreach($quiz as $qui){
                                    echo '<option value="'. $qui['qui_id'] .'" required="required">'. $qui['qui_intitule'] .'</option>';  
                                }
                                echo '</select>';
                                ?>
                                </br>
                                <input type="submit" class="btn btn-primary" name="submit" value="Créer le match" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</header>