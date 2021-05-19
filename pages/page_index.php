<?php
$pageIndex = true;
include("design/layout/header.php");
?>


<div class="intro clearfix">
    <img class="imgLeft" alt="Iveta Nešpor Levová, fotil Marek Mantič - www.mantic.cz" title="Iveta Nešpor Levová, fotil Marek Mantič - www.mantic.cz" src="design/img/ivet2.jpg">
    <div class="textRight">
        <h1>Tvořím web</h1>
        <p class="big secondFont"> 
            Potřebujete malý web ze šablony s jednoduchou administrací, či web úplně bez administrace, ale zato originální,
            podle grafického návrhu? <br /><b>To jste tady správně.</b>
        </p>
        <p>
            Jmenuji se Iveta Nešpor Levová, jsem geekgirl a vytvářím weby. Specializuji se na <strong>malé weby</strong> a drobné zakázky. Tím zaručuji,
            že i té nejmenší zakázce se budu věnovat na 100%, protože ji žádná velká "nepřebije".
        </p> 
        <p>
            Spolupracuji také s grafiky, kteří umí návrhy webů a tak toto můžu 
            zprostředkovat a vy se, krom vyjádření svých požadavků, nemusíte o nic starat.
        </p>
    </div>
</div>

<div class="services">
    <h2>Moje nabídka</h2>
    <ul>
        <li><a href="<?php echo(PREFIX); ?>sluzby/weby.html"><i class="icofont-brand-wordpress icofont-3x"></i><br />Tvorba webů</a></li>
        <li><a href="<?php echo(PREFIX); ?>sluzby/html-css-prace.html"><i class="icofont-code icofont-3x"></i><br />Ostatní HTML/CSS práce</a></li>
        <!--<li><a href="<?php echo(PREFIX); ?>sluzby/ux-konzultace.html"><i class="icofont-architecture-alt icofont-3x"></i><br />UX konzultace</a></li>-->
    </ul>
</div>


<?php
include("design/layout/footer.php");
?>