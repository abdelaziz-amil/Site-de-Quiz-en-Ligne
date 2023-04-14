<h1><?php echo $titre;
if(isset($actu)) {
echo $actu->act_id;
}
echo " :"
?></h1>
<br />
    <?php
    if(isset($actu)) {
        echo "<h3>";
        echo $actu->act_titre;
        echo "</h3>";
        echo "</br>";
        echo $actu->act_contenu;
    }
    else {echo "<br />";
        echo "pas d’actualité !";
    }
    ?>
