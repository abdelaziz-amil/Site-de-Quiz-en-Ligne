<?php
$uploaddir = '/var/www/html/difal3/zamilab00/gabarit/documents/';
//$uploaddir = __DIR__. '/documents/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
echo $uploadfile;
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
 echo "Le fichier est valide, et a été téléchargé
 avec succès. Voici plus d'informations :\n";
 } else {
 echo "Le fichier n’a pas été téléchargé. Il y a eu un problème !\n";
}
echo 'Voici quelques informations sur le téléversement :';
print_r($_FILES);



$mysqli = new mysqli('obiwan.univ-brest.fr','zamilab00','y63rwk3u','zal3-zamilab00_2');
if ($mysqli->connect_errno) {
 // Affichage d'un message d'erreur
 echo "Error: Problème de connexion à la BDD \n";
 echo "Errno: " . $mysqli->connect_errno . "\n";
 echo "Error: " . $mysqli->connect_error . "\n";
 // Arrêt du chargement de la page
 exit();
 }
$name = basename($_FILES['userfile']['name']);
$sql = 'UPDATE t_quiz_qui SET qui_illustration = "'.$name.'" where qui_id = 1;';
echo $sql;
$result=$mysqli->query($sql);
if(!$result){
    echo"erreur";
}else{
    echo"ca marche";
}

echo '<img src="documents/'.$name.'" alt="">';
$mysqli->close();
?>