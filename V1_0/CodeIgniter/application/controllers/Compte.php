<?php
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
                echo "les identifiants sont good ";
            }else if($data->cpt_etat='D'){
                echo "tu est desactiver mon frero";
                $this->load->view('templates/haut');
                $this->load->view('compte_connexion');
                $this->load->view('templates/bas');
            }else{
                echo "Le mot de passe ou l'identifiant est pas bon du tout la OH";
                $this->load->view('templates/haut');
                $this->load->view('compte_connexion');
                $this->load->view('templates/bas');
            }
        }
    }
}
?>
