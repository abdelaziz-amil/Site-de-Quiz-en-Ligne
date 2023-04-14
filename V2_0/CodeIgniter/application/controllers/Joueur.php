<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Joueur extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url');
    }
    public function afficher($code)
    {
        $data['code'] = $code;
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pseudo', 'pseudo', 'required|alpha_numeric',array('required'=>
        'Veuillez saisir un pseudo !'));
        if($this->form_validation->run() == TRUE)
        {
            $verif = $this->db_model->verif_joueur($code);

            if ($verif->nb == 0){
                $this->db_model->set_joueur($code);
                redirect('match/afficher/'.$code);
            }else
            {
                echo "pseudo déjà utilisé pour ce match";
            }
        }
        $this->load->view('templates/haut');
        //Chargement de la view du milieu : page_pseudo.php
        $this->load->view('page_pseudo',$data);
        //Chargement de la view bas.php
        $this->load->view('templates/bas');
    }
}

?>