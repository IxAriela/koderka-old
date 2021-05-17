<!DOCTYPE html>
<html lang="cs">
    <head>
        <?php
        define("REFERER", "?v0-0");
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Iveta Nešpor Levová</title>


        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#3e1730">
        <meta name="theme-color" content="#ffffff">



        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/reset.css">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/fonts.css">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/general.css<?php echo(REFERER); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/main.css<?php echo(REFERER); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/responsive.css<?php echo(REFERER); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo(PREFIX); ?>design/css/jquery.fancybox.css" />


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="script.js"></script> 

        <script type="text/javascript">
            //<![CDATA[
            var stavZprava = null;
            var stavTyp = null;
            //]]>
        </script>

        <!-- Google Tag Manager -->
<!--        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({'gtm.start':
                            new Date().getTime(), event: 'gtm.js'});
                var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-PD3PG2F');</script>-->
        <!-- End Google Tag Manager -->

    </head>

    <body>
        <!-- Google Tag Manager (noscript) -->
<!--        <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PD3PG2F"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>-->
        <!-- End Google Tag Manager (noscript) -->
        <header class="header">
            <div class="container clearfix">
                <div class="logo">
                    <a class="signature" href="<?php echo(PREFIX); ?>">&nbsp;Iveta Nešpor Levová</a>
                </div> <!-- logo -->

                <!-- Main menu -->
                <nav class="menu">
                    <ul class="ul-menu">
                        <li class="home"><a<?php if ($page == $pageIndex) echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>">Home</a></li>
                        <li><a<?php if (($page == "sluzby") || ($page == "weby") || ($page == "html-css-prace") || ($page == "ux-konzultace")) echo(' class="active"'); ?> href="sluzby.html">Služby</a>
                            <ul>
                                <li><a<?php if ($page == "weby") echo(' class="active"'); ?> href="weby.html">Webové stránky</a></li>
                                <li><a<?php if ($page == "html-css-prace") echo(' class="active"'); ?> href="html-css-prace.html">Ostatní HTML/CSS práce</a></li>
                                <!--<li><a<?php if ($page == "ux-konzultace") echo(' class="active"'); ?> href="ux-konzultace.html">UX konzultace</a></li>-->
                            </ul>
                        </li>
                        <!--<li><a<?php if ($page == "o-mne") echo(' class="active"'); ?> href="o-mne.html">O mně</a></li>-->
                        <li><a<?php if ($page == "kontakty") echo(' class="active"'); ?> href="kontakty.html">Kontakt</a></li>
                    </ul>
                </nav><!-- /.menu -->
            </div><!-- /.container -->
        </header>
        <section>
            <div class="container">
                <?php
                echo ZobrazStav();
                ?>
