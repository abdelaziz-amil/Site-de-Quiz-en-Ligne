<header class="masthead">
    
<div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Création de Compte</h1>
</br>

                        <?php echo validation_errors('<div class="alert alert-danger" role="alert">','</div>'); ?>
                        <?php echo form_open('compte_creer'); ?>
                        <div class="form-group mx-sm-3 mb-2">
                                <input type="input" class="form-control form-control-lg" name="nom" placeholder="Nom"/><br />
                                <input type="input" class="form-control form-control-lg" name="prenom" placeholder="Prénom"/><br />
                                <input type="input" class="form-control form-control-lg" name="mail" placeholder="E-mail"/><br />
                                <input type="input" class="form-control form-control-lg" name="id" placeholder="Identifiant"/><br />
                                <input type="password" class="form-control form-control-lg" name="mdp" placeholder="Mot de Passe"/><br />
                                <input type="submit" class="btn btn-primary" name="submit" value="Créer un compte" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</header>