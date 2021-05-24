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
        <script src="<?php echo(PREFIX); ?>system/js/jquery.fancybox.js"></script>
        <script src="script.js"></script> 

        <script type="text/javascript">
            //<![CDATA[
            var stavZprava = null;
            var stavTyp = null;
            //]]>
        </script>

        <?php
        if ($test != TRUE) {
            ?>
            <!-- Google Tag Manager -->
            <script type="text/plain" cookie-consent="tracking">
                (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({'gtm.start':
                new Date().getTime(), event: 'gtm.js'});
                var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', 'GTM-PD3PG2F');
            </script>
            <!-- End Google Tag Manager -->
            <?php
        }
        ?>
    </head>

    <body>
        <?php
        if ($test != TRUE) {
            ?>

            <!-- Cookie Consent by https://www.CookieConsent.com -->
            <script type="text/javascript" src="//www.cookieconsent.com/releases/3.1.0/cookie-consent.js"></script>
            <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                cookieconsent.run({"notice_banner_type": "simple", "consent_type": "express", "palette": "light", "language": "cs", "website_name": "koderka.net"});
            });
            </script>

            <noscript>ePrivacy and GPDR Cookie Consent by <a href="https://www.CookieConsent.com/" rel="nofollow noopener">Cookie Consent</a></noscript>
            <!-- End Cookie Consent by https://www.CookieConsent.com -->

            <!-- Google Tag Manager (noscript) -->
            <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PD3PG2F"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
            </noscript>
            <!-- End Google Tag Manager (noscript) -->
            <?php
        }
        ?>
        <header class="header">
            <div class="container clearfix">
                <div class="logo">
                    <a class="signature" href="<?php echo(PREFIX); ?>">&nbsp;Iveta Nešpor Levová</a>
                </div> <!-- logo -->

                <!-- Main menu -->
                <nav class="menu">
                    <ul class="ul-menu">
                        <li class="home"><a<?php if ($page == $pageIndex) echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>">Home</a></li>
                        <li><a<?php if (($page == "sluzby") || ($page == "weby") || ($page == "html-css-prace") || ($page == "ux-konzultace")) echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>sluzby.html">Služby</a>
                            <ul>
                                <li><a<?php if ($page == "weby") echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>sluzby/weby.html">Webové stránky</a></li>
                                <li><a<?php if ($page == "html-css-prace") echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>sluzby/html-css-prace.html">Ostatní HTML/CSS práce</a></li>
                                <!--<li><a<?php if ($page == "ux-konzultace") echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>sluzby/ux-konzultace.html">UX konzultace</a></li>-->
                            </ul>
                        </li>
                        <li><a<?php if ($page == "o-mne") echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>o-mne.html">O&nbsp;mně</a></li>
                        <li><a<?php if ($page == "kontakty") echo(' class="active"'); ?> href="<?php echo(PREFIX); ?>kontakty.html">Kontakt</a></li>
                    </ul>
                </nav><!-- /.menu -->
            </div><!-- /.container -->
        </header>
        <section>
            <div class="container">
                <?php
                echo ZobrazStav();
                ?>
