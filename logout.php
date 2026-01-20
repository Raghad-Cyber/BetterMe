<?php
require_once __DIR__ . '/../secure_config/config.php';

session_unset();    
session_destroy();  

header("Location: login.php");
exit;