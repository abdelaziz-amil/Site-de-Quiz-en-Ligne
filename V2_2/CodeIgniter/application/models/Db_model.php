<?php
    class Db_model extends CI_Model {
        public function __construct()
        {
            $this->load->database();
        }
        /**********************************************COMPTE**********************************************/
        //récupération de tous les comptes de la base
        public function get_all_compte()
        {
            $query = $this->db->query("SELECT * FROM t_compte_cpt;");
            return $query->result_array();
        }
        //récupération des infos d'un compte
        public function get_info_compte($pseudo){
            $query=$this->db->query("SELECT * from t_compte_cpt join t_profil_pfl using (cpt_id) where cpt_pseudo = '".$pseudo."';");
            return $query->row();
        }
        //UPDATE du mot de passe d'un compte avec sel
        public function set_new_mdp($pseudo){
            $this->load->helper('url');
            $mdp=$this->input->post('mdp');
            $salt="JeSuiSlesEletjEraLLongElEMoTdEpAsse";
            $password = hash('sha256', $salt.$mdp);
            $query=$this->db->query("UPDATE t_compte_cpt SET cpt_mdp = '".$password."' where cpt_pseudo ='".$pseudo."';");
            return ($query);
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
            $nom=$this->input->post('nom');
            $prenom=$this->input->post('prenom');
            $mail=$this->input->post('mail');
            $id=$this->input->post('id');
            $mdp=$this->input->post('mdp');
            $salt="JeSuiSlesEletjEraLLongElEMoTdEpAsse";
            $password = hash('sha256', $salt.$mdp);
            $req="INSERT INTO `t_compte_cpt`( `cpt_pseudo`, `cpt_mdp`, `cpt_role`, `cpt_etat`) VALUES ('".$id."','".$password."','F','A');";
            $query = $this->db->query($req);
            $cpt_id=$this->db->query("SELECT cpt_id as id from t_compte_cpt where cpt_pseudo ='".$id."';");
            $cpt_id=$cpt_id->row();
            $req2="INSERT INTO `t_profil_pfl`(`pfl_nom`, `pfl_prenom`, `pfl_mail`, `cpt_id`) VALUES ('".$nom."','".$prenom."','".$mail."','".$cpt_id->id."');";
            $query2 = $this->db->query($req2);
            return ($query);
        }
        //verification du compte si il existe
        public function verif_compte(){
            $this->load->helper('url');
            $id=$this->input->post('id');
            $mdp=$this->input->post('mdp');
            $salt="JeSuiSlesEletjEraLLongElEMoTdEpAsse";
            $password = hash('sha256', $salt.$mdp);
            $req="SELECT count(*) as nb, cpt_etat, cpt_role from t_compte_cpt where cpt_pseudo='".$id."' and cpt_mdp='".$password."' and cpt_etat = 'A';";
            $req=$this->db->query($req);
            return ($req->row());
        }
        //desactivation ou activation de l'etat du compte
        public function A_D_compte($etat,$id){
            $query=$this->db->query("UPDATE t_compte_cpt SET cpt_etat = '".$etat."' where cpt_id = ".$id.";");
            return ($query);
        }
        //Récupération des info du compte formateur(match quiz question reponse)
        public function info_formateur(){
            $pseudo = $this->session->userdata['username'];
            $query=$this->db->query("SELECT * from formateur_quiz join t_match_mat using (qui_id) join t_question_qst using (qui_id) join t_reponse_rep using (qst_id) ;");
            return $query->result_array();
        }
        /**********************************************MATCH**********************************************/
        //remise à zéro d'un match
        public function activer_match($code){
            $query=$this->db->query("UPDATE t_match_mat SET mat_date_debut = now(), mat_date_fin = NULL, mat_score = NULL where mat_code = '".$code."';");
            return ($query);
        }
        //arrete du match en mettant la date de fin a tout de suite
        public function desactiver_match($code){
            $query=$this->db->query("UPDATE t_match_mat SET mat_date_fin = now() where mat_code = '".$code."';");
            return ($query);
        }
        //désactivation de l'etat d'un match
        public function A_match($code){
            $query=$this->db->query("UPDATE t_match_mat SET mat_etat = 'D' where mat_code = '".$code."';");
            return ($query);
        }
        //activation de l'etat d'un match
        public function D_match($code){
            $query=$this->db->query("UPDATE t_match_mat SET mat_etat = 'A' where mat_code = '".$code."';");
            return ($query);
        }
        //supprimer un match
        public function delete_match($code){
            $query=$this->db->query("DELETE from t_match_mat where mat_code = '".$code."';");
            return ($query);
        }
        //check si le match existe dans la base
        public function match_exist()
        {
            $this->load->helper('url');
            $code=$this->input->post('code_match');
            $query=$this->db->query("SELECT count(*) as nb_mat from t_match_mat where mat_code ='".$code."';");
            return $query->row();
        }
        //récupération de l'état d'un match 
        public function get_mat_etat()
        {
            $this->load->helper('url');
            $code=$this->input->post('code_match');
            $query=$this->db->query("SELECT mat_etat as etat, mat_date_debut as debut, mat_date_fin as fin from t_match_mat where mat_code ='".$code."';");
            return $query->row();
        }
        //récupération des infos d'un match(+question réponse du quiz liée au match)
        public function get_info_match($code)
        {   
            $query = $this->db->query("SELECT * from t_match_mat join question_reponse using (qui_id) join t_quiz_qui using (qui_id) where mat_code ='".$code."';");
            return $query->result_array();
        }
        //recupération de l'id d'un match avec son code
        public function get_mat_id($code){
            $query=$this->db->query("SELECT mat_id as id from t_match_mat where mat_code='".$code."';");
            return $query->row();
        }
        //création d'un match
        public function set_match()
        {   
            //génération du code du match
            $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = '';
            for($i=0; $i<8; $i++){
                $code .= $char[rand(0, strlen($char)-1)];
            }
            $id=$this->session->userdata('id');
            $this->load->helper('url');
            $intitule=$this->input->post('intitule');
            $debut=$this->input->post('debut');
            $AouD=$this->input->post('AouD');
            $AouD=strtoupper($AouD);
            $quiz=$this->input->post('quiz');
            $req="INSERT INTO `t_match_mat`(`mat_date_debut`, `mat_date_fin`, `mat_code`, `qui_id`, `cpt_id`, `mat_corrige`, `mat_intitule`, `mat_etat`) 
            VALUES ('".$debut."',null,'".$code."',".$quiz.",".$id.",'".$AouD."','".$intitule."','A')";
            $query = $this->db->query($req);
            return ($query);
        }
        //verifie si la réponse est bonne
        public function bonne_reponse($reponse){
            $query=$this->db->query("SELECT rep_bonne_reponse as v from t_reponse_rep where rep_id =".$reponse.";");
            return $query->row();
        }
        public function match_c($code){
            $query=$this->db->query("SELECT mat_corrige as c from t_match_mat where mat_code =".$code.";");
            return $query->row();
        }
        public function get_score_match($id){
            $req="call score_match(".$id.");";
            $query = $this->db->query($req);
            return ($query);
        }
        /**********************************************QUIZ**********************************************/
        //récupération de l'état d'un quiz 
        public function get_quiz_etat()
        {
            $this->load->helper('url');
            $code=$this->input->post('code_match');
            $query=$this->db->query("SELECT qui_etat as etat from t_quiz_qui join t_match_mat using (qui_id) where mat_code ='".$code."';");
            return $query->row();
        }
        //récuperation des quiz d'un formateur
        public function get_quiz(){
            $id = $this->session->userdata('id');
            $query=$this->db->query("SELECT * from formateur_quiz where cpt_id='".$id."';");
            return $query->result_array();
        }
        public function get_liste_quiz(){ //liste des quiz qui ont plus d'une question et plus d'une réponse
            $id = $this->session->userdata('id');
            $query = $this->db->query("SELECT DISTINCT qui_intitule,qui_id from formateur_quiz join t_question_qst using (qui_id) where (select count(qui_id) > 0 from t_question_qst);");

            return $query->result_array();
        }
        /**********************************************ACTUALITÉ**********************************************/
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
        /**********************************************JOUEUR**********************************************/
        //suppression des joueurs d'un match
        public function delete_joueur($id_mat){
            $query=$this->db->query("DELETE from t_joueur_jou where mat_id='".$id_mat."';");
            return ($query);
        }
        //verifie si le pseudo du joueur est déjà existant pour un match spécifique
        public function verif_joueur($code){
            $this->load->helper('url');
            $pseudo=$this->input->post('pseudo');
            $res=$this->db->query("SELECT count(*) as nb from t_joueur_jou join t_match_mat using (mat_id) where jou_pseudo = '".$pseudo."' and mat_code = '".$code."';");
            return $res->row();
        }
        //insert un joueur dans la base
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
        //ajouter le score au joueur
        public function get_score($score,$pseudo,$id_mat){
            $query=$this->db->query("UPDATE t_joueur_jou set score=".$score." where jou_pseudo='".$pseudo."' and mat_id =".$id_mat.";");
            return ($query);
        }
        
    }
    ?>