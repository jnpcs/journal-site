<?php
$settings = array(
    'PDO'   => array(
        'user'          => 'root',
        'pass'          => '',
        'connection'    => 'mysql:host=localhost;dbname=jnpcs',
    ),
    'uploaddir' => "../uploads/"
);
class PaperStatus {
    const new=1;
    const in_reviewing=2;
    const reviewed=3;
    const in_modification=4;
    const accepted=5;
    const rejected=6;
    const published=7;
    const done=8;
}
?>