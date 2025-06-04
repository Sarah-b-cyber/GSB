<?php
$motDePasseClair = '19122004Sb!';
$hash = password_hash($motDePasseClair, PASSWORD_DEFAULT);
echo $hash;
