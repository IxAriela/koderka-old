<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Iveta Nešpor Levová</title>

        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/fonts.css">
        <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="shortcut icon" href="images/favicon.ico">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="script.js"></script>
    </head>

    <body>
        <header class="header">
            <div class="container clearfix">
                <div class="logo">
                    <a class="signature" href="index.html">Iveta Nešpor Levová</a>
                </div> <!-- logo -->

                <!-- Main menu -->
                <nav class="menu">
                    <ul class="ul-menu">
                        <li><a<?php if($page == $pageIndex) echo(' class="active"'); ?> href="index.html">Home</a></li>
                        <li>
                          <a<?php if($page == "") echo(' class="active"'); ?> href="#ja">Služby</a>
                          <ul>
                            <li><a>Stránky na Wordpressu</a></li>
                              <li><a>Stránky bez administrace</a></li>
                                <li><a>Ostatní služby</a></li>
                          </ul>
                        </li>
                        <li><a<?php if($page == "") echo(' class="active"'); ?> href="#ja">O mně</a></li>
                        <li><a<?php if($page == "") echo(' class="active"'); ?> href="#projekty">Další projekty</a></li>
                        <li><a<?php if($page == "") echo(' class="active"'); ?> href="#kontakt">Kontakt</a></li>
                    </ul>
                </nav><!-- /.menu -->
            </div><!-- /.container -->
        </header>
        <section>
            <div class="container">
