<?php if(!($this->session->has_userdata('username'))){
    redirect('compte_connexion');
} ?>
<header class="masthead">
</br>
</br>
</br>
<h2>Espace d'administration</h2>
<br />
<h2>Session ouverte ! Bienvenue
<?php
echo $this->session->userdata('username');
?> !
</h2>
</header>