<?php
$tokenPath = 'token.json';
unlink($tokenPath);
header("Location: http://localhost/cc2018/");  
?>