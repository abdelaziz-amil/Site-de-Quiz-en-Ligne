<?php
$this->session->sess_destroy();
session_write_close();
redirect('page_accueil');
?>