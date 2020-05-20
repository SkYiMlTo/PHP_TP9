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
    $image = imagecreate(900, 200);
    $lightgray = imagecolorallocate($image, 128, 128,128);
    $black = imagecolorallocate($image, 0, 0, 0);
    $blue = imagecolorallocate($image, 0, 0, 254);
    $white = imagecolorallocate($image, 255, 255, 255);

    $moyE1 = $moyE2 = 0;
    $x0 = 0;
    $y0 = 100;
    $widthLine = 100;
    $notePrecedente = -1;

    $reponse1 = $dbh->prepare('SELECT note FROM notes where etudiant = ?');
    $reponse1->execute(array("E1"));
    $data = $reponse1->fetchAll();
    foreach ($data as $note){
        $i++;
        $moyE1 += $note['note'];
        if ($notePrecedente == -1) {
            $notePrecedente = $note['note'];
            continue;
        }
        imageline($image, $x0, $y0-$notePrecedente, $x0 + $widthLine, $y0-$note['note'], $white);
        $notePrecedente = $note['note'];
        $x0 += 100;
    }
    $moyE1 = $moyE1 / $i;

    $x0 = $i = 0;
    $notePrecedente = -1;

    $reponse2 = $dbh->prepare('SELECT note FROM notes where etudiant = ?');
    $reponse2->execute(array("E2"));
    $data = $reponse2->fetchAll();
    foreach ($data as $note){
        $i++;
        $moyE2 += $note['note'];
        if ($notePrecedente == -1) {
            $notePrecedente = $note['note'];
            continue;
        }
        imageline($image, $x0, $y0-$notePrecedente, $x0 + $widthLine, $y0-$note['note'], $blue);
        $notePrecedente = $note['note'];
        $x0 += 100;
    }
    $moyE2 = $moyE2 / $i;


    imagestring($image, 20, 300, 10, "Notes des etudiants E1 et E2 !", $black);
    imagestring($image, 20, 10, 120, "E1", $white);
    imagestring($image, 20, 60, 120, "E2", $blue);
    imagestring($image, 20, 600, 160, "Moyenne des notes de E1 : ".$moyE1, $black);
    imagestring($image, 20, 600, 175, "Moyenne des notes de E2 : ".$moyE2, $black);


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
