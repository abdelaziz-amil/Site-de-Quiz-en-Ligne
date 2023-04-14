<?php
    class Db_model extends CI_Model {
        public function __construct()
        {
            $this->load->database();
        }
        //récupération de tous les comptes de la base
        public function get_all_compte()
        {
            $query = $this->db->query("SELECT cpt_pseudo FROM t_compte_cpt;");
            return $query->result_array();
        }
        //récupére nombre de compte
        public function get_nb_compte()
        {
            $query = $this->db->query("SELECT count(cpt_pseudo) as nbr FROM t_compte_cpt;");
            return $query->row();
        }
        // Fonction qui insère une ligne dans la table des comptes
        public function set_compte()
        {
            $this->load->helper('url');
            $id=$this->input->post('id');
            $mdp=$this->input->post('mdp');
            $req="INSERT INTO `t_compte_cpt`( `cpt_pseudo`, `cpt_mdp`, `cpt_role`, `cpt_etat`) VALUES ('".$id."','".$mdp."','F','D');";
            $query = $this->db->query($req);
            return ($query);
        }
        public function verif_compte(){
            $this->load->helper('url');
            $id=$this->input->post('id');
            $mdp=$this->input->post('mdp');
            $req="SELECT count(*) as nb from t_compte_cpt where cpt_pseudo='".$id."' and cpt_mdp='".$mdp."' and cpt_etat = 'A';";
            $req=$this->db->query($req);
            return ($req->row());
        }
        //récupération de toutes les actualités de la base
        public function get_all_actu()
        {
            $query = $this->db->query("SELECT * from t_actualite_act join t_compte_cpt using (cpt_id);");
            return $query->result_array();
        }
        //récupération d'une actualité avec son id
        public function get_actualite($numero)
        {
            $query = $this->db->query("SELECT act_id, act_titre, act_contenu FROM t_actualite_act WHERE
            act_id=".$numero.";");
            return $query->row();
        }
        //récupération des infos d'un match(+question réponse du quiz liée au match)
        public function get_info_match($code)
        {   
            $query = $this->db->query("SELECT * from t_match_mat join question_reponse using (qui_id) join t_quiz_qui using (qui_id) where mat_code ='".$code."';");
            return $query->result_array();
        }
        public function verif_joueur($code){
            $this->load->helper('url');
            $pseudo=$this->input->post('pseudo');
            $res=$this->db->query("SELECT count(*) as nb from t_joueur_jou join t_match_mat using (mat_id) where jou_pseudo = '".$pseudo."' and mat_code = '".$code."';");
            return $res->row();
        }
        public function set_joueur($param)
        {
            $this->load->helper('url');
            $pseudo=$this->input->post('pseudo');
            $id_mat=$this->db->query("SELECT mat_id as id from t_match_mat where mat_code='".$param."';");
            $id_mat= $id_mat->row();
            $req="INSERT INTO `t_joueur_jou`( `jou_pseudo`, `mat_id`, `score`) VALUES ('".$pseudo."',".$id_mat->id.",0);";
            $query = $this->db->query($req);
            return ($query);
        }
        public function match_exist()
        {
            $this->load->helper('url');
            $code=$this->input->post('code_match');
            $query=$this->db->query("SELECT count(*) as nb_mat from t_match_mat where mat_code ='".$code."';");
            return $query->row();
        }
        public function get_mat_etat()
        {
            $this->load->helper('url');
            $code=$this->input->post('code_match');
            $query=$this->db->query("SELECT mat_etat as etat, mat_date_debut as debut, mat_date_fin as fin from t_match_mat where mat_code ='".$code."';");
            return $query->row();
        }
        public function get_quiz_etat()
        {
            $this->load->helper('url');
            $code=$this->input->post('code_match');
            $query=$this->db->query("SELECT qui_etat as etat from t_quiz_qui join t_match_mat using (qui_id) where mat_code ='".$code."';");
            return $query->row();
        }
    }
    ?>