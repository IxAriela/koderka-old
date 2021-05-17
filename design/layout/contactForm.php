<h2 class="paddingB0">Zaujala Vás moje nabídka? Nebo máte dotaz?</h2>
<p class="marginT0">Neváhejte mne kontaktovat!</p>

<form id="writeMe"  method="post" action="<?php echo(PREFIX); ?>pages/action/writeMe.php" onsubmit="return confirm('Opravdu chcete zprávu odeslat?');">
    <div title="SPM LEST" class="trick">
        <label>JMÉNO: <input type="text" id="name" name="name" value="" maxlength="50" /></label><br />
        <label>E-MAIL: <input type="text" id="email" name="email" value="" maxlength="100" /></label><br />
        <label>WWW: <input type="text" id="web" name="web" value="" maxlength="100" /></label><br />
        <label>TEXT: <textarea cols="10" rows="2" id="text" name="text"></textarea></label>
    </div>
    <div class="field half first">
        <input type="text" name="txtName" id="txtName" placeholder="Jméno" value="<?php html_entities(isset($_SESSION["WRITE_ME"]["Name"]) ? $_SESSION["WRITE_ME"]["Name"] : ""); ?>">
        <label for="txtName">Jméno:</label>
    </div>
    <div class="field half">
        <input type="email" name="txtEmail" id="txtEmail" placeholder="E-mail" value="<?php html_entities(isset($_SESSION["WRITE_ME"]["Email"]) ? $_SESSION["WRITE_ME"]["Email"] : ""); ?>">
        <label for="txtEmail">E-mail:</label>
    </div>
    <div class="field full">
        <textarea type="text" name="txtMessage" id="txtMessage" rows="3" placeholder="Vaše zpráva"><?php html_entities(isset($_SESSION["WRITE_ME"]["Message"]) ? $_SESSION["WRITE_ME"]["Message"] : ""); ?></textarea>
        <label for="txtMessage">Vaše zpráva:</label>
    </div>
    <?php $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
    <input name="url" type="hidden" value="<?php echo $url; ?>">
    <input name="btnSend" type="submit">
    <br class="clear" />
</form>