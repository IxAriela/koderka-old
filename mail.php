<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Odesílání emailu</title>
</head>
<body>
<h1>Odesílání emailu</h1>

<?
$jmeno = $_POST['jmeno'];
$email = $_POST['email'];
$predmet = $_POST['predmet'];
//$spam = $_POST['spam'];
$zprava = $_POST['zprava'];

if ($predmet!="" and $email!="" and $zprava!="")
{
Mail("ivet.lev@gmail.com", $predmet, $zprava, "From: " . $email);
echo "<p><strong>Váš e-mail byl úspěšně odeslán</strong>.</p>";
}
else
{
echo "<p>Váš e-mail se <strong>nepodařilo odeslat</strong> pravděpodobně jste nevyplnili všechny údaje.</p>";
} 
?>

</body>
</html>
