<!DOCTYPE html>
<html lang="cs">
    <head>
        <?php
        define("REFERER", "?v0-0");
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Iveta Nešpor Levová</title>

        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/reset.css">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/fonts.css">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/general.css<?php echo(REFERER); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/main.css<?php echo(REFERER); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/responsive.css<?php echo(REFERER); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/jquery.fancybox.css" />
        
        <link rel="shortcut icon" href="images/favicon.ico">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="script.js"></script> 
        
        <script type="text/javascript">
	    //<![CDATA[
	    var stavZprava = null;
	    var stavTyp = null;
	    //]]>
	</script>
    </head>

    <body>
        <header class="header">
            <div class="container clearfix">
                <div class="logo">
                    <a class="signature" href="index.html">&nbsp;Iveta Nešpor Levová</a>
                </div> <!-- logo -->

                <!-- Main menu -->
                <nav class="menu">
                    <ul class="ul-menu">
                        <li><a<?php if($page == $pageIndex) echo(' class="active"'); ?> href="index.html">Home</a></li>
                        <li><a<?php if(($page == "sluzby") || ($page == "weby") || ($page == "html-css-prace") || ($page == "ux-konzultace")) echo(' class="active"'); ?> href="sluzby.html">Služby</a>
                          <ul>
                            <li><a<?php if($page == "weby") echo(' class="active"'); ?> href="weby.html">Webové stránky</a></li>
                            <li><a<?php if($page == "html-css-prace") echo(' class="active"'); ?> href="html-css-prace.html">Ostatní HTML/CSS práce</a></li>
                            <!--<li><a<?php if($page == "ux-konzultace") echo(' class="active"'); ?> href="ux-konzultace.html">UX konzultace</a></li>-->
                          </ul>
                        </li>
                        <!--<li><a<?php if($page == "o-mne") echo(' class="active"'); ?> href="o-mne.html">O mně</a></li>-->
                        <li><a<?php if($page == "kontakty") echo(' class="active"'); ?> href="kontakty.html">Kontakt</a></li>
                    </ul>
                </nav><!-- /.menu -->
            </div><!-- /.container -->
        </header>
        <section>
            <div class="container">
<?php
echo ZobrazStav();
?>