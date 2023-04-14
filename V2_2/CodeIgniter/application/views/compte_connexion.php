<?php if($this->session->has_userdata('username')){
                    $info['formateur']=$this->db_model->info_formateur();
                    $info['pseudos'] = $this->db_model->get_all_compte();
                    $info['qui'] = $this->db_model->get_quiz();
                    $this->load->view('templates/menu_administrateur');
                    $this->load->view('compte_backoffice',$info);
                    $this->load->view('templates/bas');
}else{ $this->load->view('templates/haut');
?>
<header class="masthead">
    
<div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Connexion</h1>
</br>

                        <?php echo validation_errors(); ?>
                        <?php echo form_open('compte_connexion'); ?>
                        <div class="form-group mx-sm-3 mb-2">
                                <input type="input" class="form-control form-control-lg" name="id" placeholder="Identifiant"/><br />
                                <input type="password" class="form-control form-control-lg" name="mdp" placeholder="Mot de Passe"/><br />
                                <input type="submit" class="btn btn-primary" name="submit" value="Connexion" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</header>
<?php  $this->load->view('templates/bas');}?>