--Activité 4

--VUE AVEC QUESTION REPONSE
CREATE VIEW question_reponse AS (select * from t_question_qst join t_reponse_rep using (qst_id));
--VUE AVEC COMPTE ET PROFIL
CREATE VIEW CPT_PFL AS (select * from t_compte_cpt join t_profil_pfl using (cpt_id));
--VUE AVEC MATCH ET JOUEUR
CREATE VIEW MATCH_JOUEUR AS (select * from t_match_mat join t_joueur_jou using (mat_id));
--FONCTION QUI RETOURNE LE NOMBRE DE QUESTION DANS UN QUIZ
DELIMITER //
CREATE FUNCTION nb_question(ID_QUI INT) RETURNS INT
BEGIN
select count(qst_id) into @nb_qst from t_question_qst where qui_id = ID_QUI;
RETURN @nb_qst; 
END;
//
DELIMITER ;
--PROCEDURE QUI COMPTE LE NOMBRE DE JOUEUR QUI PARTICIPE A UN MATCH
DELIMITER //
CREATE PROCEDURE nb_joueur(IN ID_MAT INT,OUT nb INT)
BEGIN
    select count(jou_id) into nb from t_joueur_jou where mat_id = ID_MAT;
END
//
DELIMITER ;

--Preocedure qui update le score du match
DELIMITER //
CREATE PROCEDURE nb_joueur(IN ID_MAT INT)
BEGIN
	set @nb = 0;
    set @sc = 0;
    call nb_joueur(ID_MAT,@nb);
    SELECT sum(score) into @sc from t_joueur_jou where mat_id = ID_MAT;
    UPDATE t_match_mat SET mat_score = @sc/@nb where mat_id = ID_MAT;
END
//
DELIMITER ;
--FAIRE UN TRIGGER QUI APPLIQUE LE SEL AUTOMATIQUEMENT QUAND ON AJOUTE UN COMPTE
DELIMITER //
CREATE TRIGGER sel
AFTER INSERT ON t_compte_cpt
FOR EACH ROW
BEGIN
select CONCAT('JeSuiSlesEletjEraLLongElEMoTdEpAsse',cpt_mdp) into @tmp;
select SHA2(@tmp,256) into @mdp_sel;
UPDATE t_compte_cpt SET cpt_mdp = @mdp_sel WHERE t_compte_cpt.cpt_id=NEW.cpt_id;
END;
//
DELIMITER ;

--FAIRE UN TRIGGER QUI À LA SUPPRESSION D'UN QUIZ SUPPRIME TOUTES LES DONNÉES QUI LUI SONT LIÉES (QUESTIONS, RÉPONSES, MATCHS)
DELIMITER //
CREATE TRIGGER supprQuiz
BEFORE DELETE ON t_quiz_qui
FOR EACH ROW
BEGIN
DELETE from t_reponse_rep where qst_id in (select qst_id from t_question_qst where t_question_qst.qui_id = OLD.qui_id);
DELETE from t_question_qst where t_question_qst.qui_id = OLD.qui_id;
DELETE from t_joueur_jou where mat_id in (select mat_id from t_match_mat where t_match_mat.qui_id = OLD.qui_id);
DELETE from t_match_mat where t_match_mat.qui_id = OLD.qui_id;
END;
//
DELIMITER ;
--trigger qui met dans le score du match le score calculer
DELIMITER //
CREATE TRIGGER score_mat AFTER UPDATE ON t_match_mat
 FOR EACH ROW 
 BEGIN
    IF NEW.mat_date_debut is not null and NEW.mat_date_fin is not null and NEW.mat_score is null THEN
    	set @nb = 0;
        set @sc = 0;
        call nb_joueur(NEW.mat_id,@nb);
        SELECT sum(score) into @sc from t_joueur_jou where mat_id = NEW.mat_id;
		UPDATE t_match_mat SET NEW.mat_score = @sc/@nb where mat_id = NEW.mat_id;
    END IF;
END;
//
DELIMITER ;







--Activité 5
--Trigger 1
DELIMITER //
CREATE TRIGGER suppr_qst
AFTER DELETE ON t_question_qst
FOR EACH ROW
BEGIN
        DELETE from t_actualite_act where t_actualite_act.act_titre LIKE (select CONCAT('Modification du quiz ',OLD.qui_id));
        set @listmat =(SELECT GROUP_CONCAT(DISTINCT mat_code) from t_question_qst left outer join t_match_mat using (qui_id) where qui_id = OLD.qui_id);
        SELECT count(DISTINCT qst_id) into @nb_question from t_question_qst where qui_id = OLD.qui_id;
        set @act_cont="Aucun match associé à ce quiz pour l’instant !";
        IF @listmat IS NOT NULL THEN
            set @act_cont = CONCAT("match liée au quiz : ",@listmat);
        END IF;
        IF @nb_question >= 2 THEN
            INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
            VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('Suppression d’une question : il reste ',@nb_question,' questions dans le quiz. ',@act_cont),'1',NOW());
        END IF;
        IF @nb_question = 1 THEN
            INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
            VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('ATTENTION, plus qu’une question ! ',@act_cont),'1',NOW());
        END IF;
        IF @nb_question = 0 THEN
            INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
            VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('QUIZ VIDE ! Et ',@act_cont),'1',NOW());
        END IF;
END;
//
DELIMITER ;

--Trigger 2

DELIMITER //
CREATE TRIGGER raz_match
AFTER UPDATE ON t_match_mat
FOR EACH ROW
BEGIN
    IF OLD.mat_date_debut != NEW.mat_date_debut and  NEW.mat_date_debut >= NOW() and NEW.mat_date_fin IS NULL THEN
        DELETE from t_joueur_jou where t_joueur_jou.mat_id = NEW.mat_id
    END IF;

END;
//
DELIMITER ;






--Sprint 1
--En tant que visiteur
--Actualitées
--1)
select act_titre, act_contenu from t_actualite_act
join t_compte_cpt using (cpt_id);
--2)
select * from t_actualite_act where act_id = 1;
--3)
select * from t_actualite_act
limit 5
order by act_date desc
--4)
select * from t_actualite_act where act_contenu like '%un mot particulier%'
--5)
select * from t_actualite_act
join t_compte_cpt using (cpt_id)
where act_date like NOW();

--En tant que joueur
--Matchs
--1)
set @matchvalide = (select mat_id from t_match_mat where mat_code = 12345678);
select @matchvalide;
--2)
INSERT INTO `t_joueur_jou`(`jou_pseudo`, `mat_id`, `score`) VALUES ('zayreus','1','0');
--3)
set @pseudovalide = (select mat_id from t_joueur_jou where jou_pseudo = 'zayreus');
select @pseudovalide
--4)
select qst_intitule_question, rep_texte_reponse from question_reponse where qui_id = (select qui_id from t_match_mat where mat_id = 12345678);

--En tant que formateur/admin
--Actualitées
--1)
select * from t_actualite_act where cpt_id = (select cpt_id from t_compte_cpt where cpt_pseudo = 'responsable');

--Profils
--1)
select * from t_profil_pfl
--2)
select * from t_profil_pfl where pfl_role = 'F';
select * from t_profil_pfl where pfl_role = 'A';
--3)
select cpt_pseudo, cpt_mdp from t_compte_cpt;
--4)
select * from t_profil_pfl where pfl_id = 1;
--5)
select cpt_pseudo, cpt_etat from t_compte_cpt;

--Quiz
--1)
select * from question_reponse where qui_id = 1;
--2)
select count(qui_id) from t_question_qst where qui_id = 1;

--Match
--1)
select qst_intitule_question,rep_texte_reponse from question_reponse join t_match_mat using (qui_id) where mat_code = 12345678;
--2)
select count(jou_id) from t_joueur_jou where mat_id = 1;
--3)
select sum(score)/count(jou_id) as score_match from t_joueur_jou where mat_id = 1;
--4)
select jou_pseudo, score from t_joueur_jou where mat_id = 1;
--5)
select mat_code, mat_date_debut, mat_date_fin, mat_corrige from t_match_mat where cpt_id = 3;
--6)
select mat_id from t_match_mat where qui_id = 1;