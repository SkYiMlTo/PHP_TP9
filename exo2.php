<?php
$dsn = 'pgsql:dbname=graphenotes;host=127.0.0.1;port=5432';
$user = 'postgres';
$password = 'root';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}


header("Content-type: image/png");
$image = imagecreate(550, 550);
$lightgray = imagecolorallocate($image, 128, 128,128);
$green = imagecolorallocate($image, 0, 200, 0);
$red = imagecolorallocate($image, 200, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);

$moyE1 = $moyE2 = 0;
$x0 = 0;
$y0 = 560;
$widthLine = 50;
$notePrecedente = -1;
$coeff = 7;

$reponse1 = $dbh->prepare('SELECT valeur FROM statistique where action = ?');
$reponse1->execute(array("Als"));
$data = $reponse1->fetchAll();
foreach ($data as $valeur){
    if ($notePrecedente == -1) {
        $notePrecedente = $coeff*$valeur['valeur'];
        continue;
    }
    imageline($image, $x0, $y0-$notePrecedente, $x0 + $widthLine, $y0-($coeff*$valeur['valeur']), $white);
    $notePrecedente = $coeff*$valeur['valeur'];
    $x0 += 50;
}

$x0 = 0;
$notePrecedente = -1;

$reponse2 = $dbh->prepare('SELECT valeur FROM statistique where action = ?');
$reponse2->execute(array("For"));
$data = $reponse2->fetchAll();
foreach ($data as $valeur){

    if ($notePrecedente == -1) {
        $notePrecedente = $coeff*$valeur['valeur'];
        continue;
    }
    imageline($image, $x0, $y0-$notePrecedente, $x0 + $widthLine, $y0-($coeff*$valeur['valeur']), $red);
    $notePrecedente = $coeff*$valeur['valeur'];
    $x0 += 50;
}

imagestring($image, 20, 50, 10, "Cours des actions Als", $green);
imagestring($image, 20, 50, 30, "et for en 2010", $green);
imagestring($image, 20, 75, 500, "Als", $white);
imagestring($image, 20, 25, 500, "For", $red);


imagepng($image);

?>

<!--$reponse2 = $dbh->prepare('SELECT * FROM notes where etudiant = ?');-->
<!--$reponse2->execute(array("E2"));-->
<!--$array = [];-->
<!--while ($data = $reponse2->fetch()) {-->
<!--array_push($array, $data['note']);-->
<!--}-->
<!--foreach ($array as $note ){-->
<!---->
<!--}-->
<!--$i++;-->
<!--$moyE2 += $data['note'];-->
<!--imageline($image, $x0, $y0-$data['note'], $x0 + $widthLine, $y0-$data['note'], $bleuclair);-->
<!--$x0 += 100;-->
<!--$moyE2 = $moyE2 / $i;-->
