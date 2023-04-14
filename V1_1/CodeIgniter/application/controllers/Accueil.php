<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Accueil extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url');
    }
    public function afficher()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('code_match', 'code_match', 'required|exact_length[8]|alpha_numeric',array('required'=>
        'Veuillez saisir un code de match !','exact_length'=>'Le code de match doit être de 8 caractères','alpha_numeric'=>'Le code du match ne doit pas contenir de caractères spéciaux'));
        //$this->form_validation->set_message('exact_length[8]','Le code de match doit être de 8 caractères');
        $activer = 'A';
        $desactiver = 'D';
        $now= @date('Y-m-d H:i:s');
        if ($this->form_validation->run() == TRUE)
        {
            $mat_valide = $this->db_model->match_exist();
            if($mat_valide->nb_mat!=0){
                //le match est valide
                $quiz_etat = $this->db_model->get_quiz_etat();
                if($quiz_etat->etat == $activer){
                    //le quiz est activer
                    $mat_etat = $this->db_model->get_mat_etat();
                    if($mat_etat->etat == $activer && $mat_etat->debut < $now){
                        //le match est activer et a commencé
                        if($mat_etat->fin == NULL || $mat_etat->fin > $now){
                            //si toutes les conditions sont bonnes on redirige vers la page pour entrer le pseudo
                            $this->load->helper('url');
                            $match_code['code'] = $this->input->post('code_match');
                            redirect('/joueur/afficher/'.$match_code['code']);
                        }else{
                            //match fini + charger page accueil
                            echo "Le Match est Fini";
                        }
                    }else{
                        //match désactiver + charger page accueil
                        echo "Match désactivé ou non démarré";
                    }
                }else{
                    echo "Quiz du match désactivé";
                }
            }else{
                //code invalide car match existe pas + charger page d'accueil
                echo "code de match non existant";
            }
        }
        $data['actualite'] = $this->db_model->get_all_actu();
        //Chargement de la view haut.php
        $this->load->view('templates/haut');
        //Chargement de la view du milieu : page_accueil.php
        $this->load->view('page_accueil',$data);
        //Chargement de la view bas.php
        $this->load->view('templates/bas');

    }
    /*public function pseudo()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pseudo', 'pseudo', 'required',array('required'=>
        'Veuillez saisir un pseudo !'));
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('templates/haut');
            //Chargement de la view du milieu : page_succes.php
            $this->load->view('page_succes');
            //Chargement de la view bas.php
            $this->load->view('templates/bas');
        }else{
            $this->load->view('templates/haut');
            //Chargement de la view du milieu : page_accueil.php
            $this->load->view('page_match',$data);
            //Chargement de la view bas.php
            $this->load->view('templates/bas');
        }
    }*/
}
?>