<header class="masthead">
            <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Le Site de Quiz en ligne</h1>
</br>
                            <?php echo validation_errors('<div class="alert alert-danger">',' </div>'); ?>

                            <?php echo form_open('/joueur/afficher/'.$code); ?>
                            <div class="form-group mx-sm-3 mb-2">
                                <input type="text" class="form-control form-control-lg" name="pseudo" id="pseudo" placeholder="Entrez votre Pseudo">
                            </div>
</br>
                            <button type="submit" class="btn btn-primary">COMMENCER LE MATCH</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
            