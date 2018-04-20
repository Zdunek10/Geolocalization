<html lang="pl">
<head>
<meta http-equiv="Refresh" content="100" />
<title>Zdunowski</title>
</head>


<body>
<?php 

$czas1 = time ();
$czas2 = date ("r", $czas1);
echo 'Czas uniksowy: ' . $czas1 . "<BR />";
echo 'Czytelny format: ' . $czas2;

?>
</body>
</html>