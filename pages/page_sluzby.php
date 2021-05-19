<?php
include("design/layout/header.php");
?>

<h1>Služby</h1>

<p>
    Nabídka mých služeb se týká hlavně webů, HTML a CSS. Zaměřuji se na menší webové stránky, tedy pro osobní potřebu,
    živnostníky nebo malé firmy.
</p>
<!--<p>
    Nedávno jsem rozšířila své dovednosti o UX design, využívám tyto znalosti při tvorbě webů, ale nabízím také konzultaci zvlášť.
</p>-->

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