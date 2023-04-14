<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Match extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url');
    }
    public function afficher($param)
    {
        $data['match'] = $this->db_model->get_info_match($param);
        $data['code_match']=$param;
        //Chargement de la view haut.php
        $this->load->view('templates/menu_visiteur');
        //Chargement de la view du milieu : page_quiz.php
        $this->load->view('page_match',$data);
        //Chargement de la view bas.php
        $this->load->view('templates/bas');
    }
    public function afficher_corriger($param)
    {
        $data['match'] = $this->db_model->get_info_match($param);
        //Chargement de la view haut.php
        $this->load->view('templates/menu_visiteur');
        //Chargement de la view du milieu : page_quiz.php
        $this->load->view('page_matchcorr',$data);
        //Chargement de la view bas.php
        $this->load->view('templates/bas');
    }
    public function supprimer($code){
        $id_mat=$this->db_model->get_mat_id($code);
        $this->db_model->delete_joueur($id_mat->id);
        $this->db_model->delete_match($code);
        redirect('compte_connexion');
    }
    public function creer(){
        // $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $string = '';
        // for($i=0; $i<8; $i++){
        //     $string .= $char[rand(0, strlen($char)-1)];
        // }
        // echo $string;
        $data['quiz']=$this->db_model->get_liste_quiz();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('intitule', 'intitule', 'required',array('required'=>
        'Veuillez saisir un intitulé !'));
        $this->form_validation->set_rules('debut', 'debut', 'required',array('required'=>
        'Veuillez saisir une date de début !'));
        $this->form_validation->set_rules('AouD', 'AouD', 'required|exact_length[1]',array('required'=>
        'Veuillez entrer une lettre pour savoir si le match est activer ou pas à sa création!', 'exact_length'=>'A ou D'));
        $this->form_validation->set_rules('quiz', 'quiz', 'required',array('required'=>
        'Veuillez choisir un Quiz si vous en avez un !'));
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('templates/menu_accueil');
            $this->load->view('match_creer',$data);
        }else{
            $this->db_model->set_match();
            redirect('compte_connexion');
        }
        
    }
    public function score($code,$cpt){
        $this->load->helper('form');
        $this->load->library('form_validation');
        $nb_qst=$this->input->get('nb_qst');
        $res = 0 ;
        for($i=0;$i<$cpt;$i++){
            $reponse=$this->input->get($i);
            if(isset($reponse)){
                $vrai = $this->db_model->bonne_reponse($reponse);
                $yes='Y';
                if($vrai->v == $yes){
                    $res++;
                }
                
            }
        }
        $match_c=$this->db_model->match_c($code);
        $this->load->view('templates/menu_visiteur');
        if($match_c->c == 'A'){
            echo '<header class="masthead">
            </br>
            </br>
            </br>
            <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                            <div class="d-flex justify-content-center">
                                <div class="text-center">
                                    <h1 class="mx-auto my-0 text-uppercase"> vous avez '.(($res/$nb_qst)*100).'% de bonne reponse
                </h1>
                </br>
                <a href="'.base_url().'index.php/match/afficher_corriger/'.$code.'"><input type="submit" class="btn btn-primary" name="submit" value="Voir les bonnes réponses" /></a>
                                </div>
                            </div>
                        </div>
            </header>';
        }else{
            echo '<header class="masthead">
            </br>
            </br>
            </br>
            <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                            <div class="d-flex justify-content-center">
                                <div class="text-center">
                                    <h1 class="mx-auto my-0 text-uppercase"> vous avez '.(($res/$nb_qst)*100).'% de bonne reponse
                </h1>
                </br>
                <a href="'.base_url().'index.php"><input type="submit" class="btn btn-primary" name="submit" value="Retour à la page d\'acceuil" /></a>
                                </div>
                            </div>
                        </div>
            </header>';
        }
        $this->load->view('templates/bas');
        $score = (($res/$nb_qst)*100);
        $pseudo=$this->session->userdata('pseudo');
        $id_mat=$this->db_model->get_mat_id($code);
        $this->db_model->get_score($score,$pseudo,$id_mat->id);
    }
}

?>