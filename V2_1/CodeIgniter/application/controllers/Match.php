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
        //Chargement de la view haut.php
        $this->load->view('templates/menu_visiteur');
        //Chargement de la view du milieu : page_quiz.php
        $this->load->view('page_match',$data);
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
        $data['quiz']=$this->db_model->get_quiz();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('intitule', 'intitule', 'required',array('required'=>
        'Veuillez saisir un intitulé !'));
        $this->form_validation->set_rules('debut', 'debut', 'required',array('required'=>
        'Veuillez saisir une date de début !'));
        $this->form_validation->set_rules('AouD', 'AouD', 'required|exact_length[1]',array('required'=>
        'Veuillez entrer une lettre pour savoir si le match est activer ou pas à sa création!', 'exact_length'=>'A ou D'));
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('templates/menu_accueil');
            $this->load->view('match_creer',$data);
        }else{
            $this->db_model->set_match();
            redirect('compte_connexion');
        }
        
    }
}
?>