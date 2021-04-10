<?php
include("layout/header.php");
?>


<h1>Kontakt</h1>

<form action="mail.php" method="post" align="center">
    <div class="row col-sm-12"><input type="text" name="jmeno" maxlength="25" placeholder="Jméno"></div>
    <div class="row col-sm-12"><input type="email" name="email" placeholder="E-mail"></div>
    <div class="row col-sm-12"><input type="subject" name="predmet" placeholder="Předmět"></div>
    <div class="row col-sm-12"><input class="text" type="text" name="zprava" placeholder="Vaše zpráva"></div>
    <div class="row col-sm-12"><input type="submit"></div>
</form>

<?php
include("layout/footer.php");
?>
