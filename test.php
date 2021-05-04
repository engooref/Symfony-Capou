<?php
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if(!$socket){
        die("Erreur socket");
    }
    
    if(!socket_connect($socket, 'localhost', '8765')){
        die('Connexion impossible');
    }
    
    $r = 0;
    while(true){
        $r += 1;
        $len = strlen(strval($r));
        socket_write($socket, strval($r), $len);
        
        $test = socket_read($socket, 1024);
        
        echo $test."\n";
    }
    
?>