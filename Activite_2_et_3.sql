--Activité 2
--FONCTION
DELIMITER //
CREATE FUNCTION liste_joueur(id INT) RETURNS TEXT
BEGIN
    select GROUP_CONCAT(jou_pseudo) into @liste_joeuur from t_joueur_jou where mat_id = id;
    return @liste_joeuur;
END;
//
DELIMITER ;

SELECT liste_joueur(1);
SELECT liste_joueur(2);
SELECT liste_joueur(3);
SELECT liste_joueur(4);
SELECT liste_joueur(5);

--PROCEDURE
DELIMITER //
CREATE PROCEDURE insert_act(IN ID_MAT INT)
BEGIN
    select mat_date_fin into @fin_mat from t_match_mat where mat_id = ID_MAT;
    IF @fin_mat IS NOT NULL OR @fin_mat <= NOW() THEN
        select liste_joueur(ID_MAT) into @liste_jou;
        select mat_date_debut into @debut_mat from t_match_mat where mat_id = ID_MAT;
        select CONCAT('date de début et de fin du match : ',@debut_mat,' ',@fin_mat,' liste des joueurs ayant participé au match : ',@liste_jou,'') into @act_cont;
        select CONCAT('Annonce fin du match : ', ID_MAT) into @titre_act;
        INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) VALUES (@titre_act,@act_cont,'1',NOW());
    END IF;
END
//
DELIMITER ;

call insert_act(1);
call insert_act(2);
call insert_act(3);
call insert_act(4);
call insert_act(5);

--TRIGGER
DELIMITER //
CREATE TRIGGER act_mat AFTER UPDATE ON t_match_mat
 FOR EACH ROW 
 BEGIN
    IF NEW.mat_date_fin is not null THEN
        call insert_act(NEW.mat_id);
    END IF;
END;
//
DELIMITER ;

UPDATE t_match_mat set mat_date_fin=NOW() where mat_id = 2;
UPDATE t_match_mat set mat_date_fin=NULL where mat_id = 1;

--Activité 3
--Procedure
DELIMITER //
CREATE PROCEDURE infodatematch(OUT mat_fin INT,OUT mat_encours INT,OUT mat_avenir INT)
BEGIN
    select count(mat_id) into mat_fin from t_match_mat where mat_date_fin is not NULL;
    select count(mat_id) into mat_avenir from t_match_mat where mat_date_debut is NULL;
    select count(mat_id) into mat_encours from t_match_mat where mat_date_debut < NOW() and mat_date_fin is not null;
END
//
DELIMITER ;

set @fin = 0;
set @encours = 0;
set @avenir = 0;
call infodatematch(@fin,@encours,@avenir);

select @fin, @debut, @avenir;

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
CREATE PROCEDURE nb_joueur(OUT nb INT)
BEGIN
    select count(jou_id) into nb from t_joueur_jou;
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

--Activité 5
--Trigger 1
--DELIMITER //
--CREATE TRIGGER suppr_qst
--AFTER DELETE ON t_question_qst
--FOR EACH ROW
--BEGIN
--        DELETE from t_actualite_act where t_actualite_act.act_titre = (select CONCAT('Modification du quiz ',OLD.qui_id));
--        set @listmat =(SELECT GROUP_CONCAT(DISTINCT mat_code) from t_question_qst left outer join t_match_mat using (qui_id) where qui_id = OLD.qui_id);
--        IF @listmat IS NULL THEN
            set @listmat = "Aucun match associé à ce quiz pour l’instant !";
         END IF;
--        SELECT count(qst_id) into @nb_question from t_question_qst left outer join t_match_mat using (qui_id) where qui_id = OLD.qui_id;
--        IF @nb_question >= 2 THEN
--            INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
--            VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('Suppression d’une question : il reste ',@nb_question,' question dans le quiz et ',@listmat,' sont les matchs liées à ce quiz'),'1',NOW());
--        END IF;
--        IF @nb_question = 1 THEN
--            INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
--            VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('ATTENTION, plus qu’une question ! Et ',@listmat,' sont les matchs liées à ce quiz'),'1',NOW());
--        END IF;
--        IF @nb_question = 0 THEN
--            INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
--            VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('QUIZ VIDE ! Et ',@listmat,' sont les matchs liées à ce quiz'),'1',NOW());
--        END IF;
--END;
--//
--DELIMITER ;


DELIMITER //
CREATE TRIGGER suppr_qst
AFTER DELETE ON t_question_qst
FOR EACH ROW
BEGIN
        DELETE from t_actualite_act where t_actualite_act.act_titre = (select CONCAT('Modification du quiz ',OLD.qui_id));
        set @listmat =(SELECT GROUP_CONCAT(DISTINCT mat_code) from t_question_qst left outer join t_match_mat using (qui_id) where qui_id = OLD.qui_id);
        SELECT count(qst_id) into @nb_question from t_question_qst left outer join t_match_mat using (qui_id) where qui_id = OLD.qui_id;
        IF @listmat IS NOT NULL THEN
            IF @nb_question >= 2 THEN
                INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
                VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('Suppression d’une question : il reste ',@nb_question,' question dans le quiz et ',@listmat,' sont les matchs liées à ce quiz'),'1',NOW());
            END IF;
            IF @nb_question = 1 THEN
                INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
                VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('ATTENTION, plus qu’une question ! Et ',@listmat,' sont les matchs liées à ce quiz'),'1',NOW());
            END IF;
            IF @nb_question = 0 THEN
                INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
                VALUES (CONCAT('Modification du quiz ',OLD.qui_id),CONCAT('QUIZ VIDE ! Et ',@listmat,' sont les matchs liées à ce quiz'),'1',NOW());
            END IF;
        ELSE
            IF @nb_question >= 2 THEN
            INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
                VALUES (CONCAT('Modification du quiz ',OLD.qui_id),'Suppression d’une question : il reste ',@nb_question,' question dans le quiz et aucun match associé à ce quiz pour l’instant !','1',NOW());
            END IF;
            IF @nb_question = 1 THEN
                INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
                VALUES (CONCAT('Modification du quiz ',OLD.qui_id),'ATTENTION, plus qu’une question ! Et aucun match associé à ce quiz pour l’instant !','1',NOW());
            END IF;
            IF @nb_question = 0 THEN
                INSERT INTO `t_actualite_act`(`act_titre`, `act_contenu`, `cpt_id`, `act_date`) 
                VALUES (CONCAT('Modification du quiz ',OLD.qui_id),'QUIZ VIDE ! Et aucun match associé à ce quiz pour l’instant !','1',NOW());
            END IF;
        END IF;
END;
//
DELIMITER ;

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
        DELETE from t_joueur_jou where t_joueur_jou.mat_id = NEW.mat_id;
    END IF;

END;
//
DELIMITER ;

UPDATE `t_match_mat` SET `mat_date_debut` = '2022-11-08 19:23:45' WHERE `t_match_mat`.`mat_id` = 2;