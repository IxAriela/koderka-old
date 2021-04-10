<?php
$pageIndex = true;
include("layout/header.php");
?>


<div class="intro clearfix">
    <img class="imgLeft" alt="Iveta Nešpor Levová, fotil Marek Mantič - www.mantic.cz" title="Iveta Nešpor Levová, fotil Marek Mantič - www.mantic.cz" src="img/ivet2.jpg">
    <div class="textRight">
        <h1>Tvořím web</h1>
        <p>
            Jmenuji se Iveta Nešpor Levová a dělám weby. Že chci dělat něco s weby jsem věděla už na vysoké škole, kde jsem se poprvé 
            více začala dozvídat o internetu, webech a různých webových technologiích. Jen tehdy jsem ještě nevěděla přesně co, protože 
            to nebyl IT obor a HTML jsme probírali pouze okrajově, CSS vůbec. A přitom až CSS je ta pravá zábava, dodává tomu ten vzhled :) 
        </p> 
        <p> 
            Protože s IT jsem začala až relativně pozdě, troufám si říct, že jsem neztratila nic
            z "běžného" jazyka a dokážu i techničtější webové věci vysvětlit laickým způsobem.
        </p>
    </div>
</div>

<div class="services">
    <h2>Moje nabídka</h2>
    <ul>
        <li><a href="<?php echo(PREFIX); ?>wordpress.html"><i class="icofont-brand-wordpress icofont-3x"></i><br />Weby na systému Wordpress</a></li>
        <li><a href="<?php echo(PREFIX); ?>staticke-weby.html"><i class="icofont-web icofont-3x"></i><br />Statické weby (bez&nbsp;administrace)</a></li>
        <li><a href="<?php echo(PREFIX); ?>ostatni-prace.html"><i class="icofont-file-html5 icofont-3x"></i><br />Ostatní HTML/CSS práce</a></li>
    </ul>
</div>


<?php
include("layout/footer.php");
?>