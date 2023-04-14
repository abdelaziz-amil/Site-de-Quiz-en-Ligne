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
}
?>