<?php
 $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
 echo "Hash:".$random_salt."<br>";
 $password = hash('sha512', 'asd8as987324' . $random_salt);
 echo "Pass:".$password;
?>