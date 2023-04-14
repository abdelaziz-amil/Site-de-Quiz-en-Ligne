<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Compte extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url');
    }
    public function lister()
    {
        $data['titre'] = 'Liste des pseudos :';
        $data['pseudos'] = $this->db_model->get_all_compte();
        $data['nombre'] = $this->db_model->get_nb_compte();
        
        $this->load->view('templates/haut');
        $this->load->view('compte_liste',$data);
        $this->load->view('templates/bas');
    }

    public function creer()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'id', 'required',array('required'=>
        'Veuillez saisir un identifiant !'));
        $this->form_validation->set_rules('mdp', 'mdp', 'required',array('required'=>
        'Veuillez saisir un mot de passe !'));
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('templates/haut');
            $this->load->view('compte_creer');
            $this->load->view('templates/bas');
        }
        else
        {
            $this->db_model->set_compte();
            $data['message']="Nouveau nombre de comptes : ";
            //appel de la fonction créée dans le précédent tutoriel :
            $data['le_nombre']=$this->db_model->get_nb_compte();
            $this->load->view('templates/haut');
            $this->load->view('compte_succes',$data);
            $this->load->view('templates/bas');
        }
    }
    public function connexion(){
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'id', 'required',array('required'=>
        'Veuillez saisir un identifiant !'));
        $this->form_validation->set_rules('mdp', 'mdp', 'required',array('required'=>
        'Veuillez saisir un mot de passe !'));
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('templates/haut');
            $this->load->view('compte_connexion');
            $this->load->view('templates/bas');
        }
        else
        {
            $data = $this->db_model->verif_compte();
            if($data->nb == 1){
                $id = $this->input->post('id'); 
                $session_data = array('username' => $id );
                $this->session->set_userdata($session_data);
                $this->load->view('templates/menu_administrateur');
                $this->load->view('compte_backoffice');
                $this->load->view('templates/bas');

                //redirect('compte_backoffice');
            }else{
                echo "Le mot de passe ou l'identifiant est pas bon du tout la OH";
                $this->load->view('templates/haut');
                $this->load->view('compte_connexion');
                $this->load->view('templates/bas');
            }
        }
    }
    public function profil(){
        $pseudo = $this->session->userdata('username');
        $data['pseudo'] = $this->db_model->get_info_compte($pseudo);
        $this->load->view('templates/menu_administrateur');
        $this->load->view('compte_profil',$data);
        $this->load->view('templates/bas');
    }
    public function deconnexion(){
        $this->load->view('page_deconnexion');
    }
    public function modifier(){
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nom', 'nom', 'required',array('required'=>
        'Veuillez saisir un identifiant !'));
        $this->form_validation->set_rules('prenom', 'prenom', 'required',array('required'=>
        'Veuillez saisir un mot de passe !'));
        $this->form_validation->set_rules('mdp', 'mdp', 'required',array('required'=>
        'Veuillez saisir un mot de passe !'));
        $this->form_validation->set_rules('conf_mdp', 'conf_mdp', 'required',array('required'=>
        'Veuillez saisir le bon mot de passe !'));
        if($this->form_validation->run()==TRUE){
            $mdp=$this->input->post('mdp');
            $mdp2=$this->input->post('conf_mdp');
            if($mdp == $mdp2){
                $pseudo = $this->session->userdata('username');
                $this->db_model->set_new_mdp($pseudo);
                echo "le mot de passe à été modifié avec succés";
                $pseudo = $this->session->userdata('username');
                $data['info'] = $this->db_model->get_info_compte($pseudo);
                $this->load->view('templates/menu_administrateur',$data);
                $this->load->view('compte_modifier');
            }else{
                echo "Confirmation du mot de passe erronée, veuillez réessayer !";
                $pseudo = $this->session->userdata('username');
                $data['info'] = $this->db_model->get_info_compte($pseudo);
                $this->load->view('templates/menu_administrateur',$data);
                $this->load->view('compte_modifier');
            }
        }else{
            $pseudo = $this->session->userdata('username');
            $data['info'] = $this->db_model->get_info_compte($pseudo);
            $this->load->view('templates/menu_administrateur',$data);
            $this->load->view('compte_modifier');
        }
    }
}
?>
