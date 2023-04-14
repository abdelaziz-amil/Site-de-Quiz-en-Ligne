<?php if(!($this->session->has_userdata('username'))){
    redirect('compte_connexion');
} ?>
<header class="masthead">
    
<div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Modification</h1>
</br>

                        <?php echo validation_errors(); ?>
                        <?php echo form_open('compte_modifier'); ?>
                        <div class="form-group mx-sm-3 mb-2">
                                <input type="input" class="form-control form-control-lg" value = "<?php echo $info->pfl_nom;?>" name="nom" placeholder="Nom"/><br />
                            <input type="input" class="form-control form-control-lg" value ="<?php echo $info->pfl_prenom;?>" name="prenom" placeholder="Prenom"/><br />
                                <input type="password" class="form-control form-control-lg" name="mdp" placeholder="Mot de Passe"/><br />
                                <input type="password" class="form-control form-control-lg" name="conf_mdp" placeholder="Confirmation Mot de Passe"/><br />
                                <input type="submit" class="btn btn-primary" name="submit" value="Modifier" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</header>