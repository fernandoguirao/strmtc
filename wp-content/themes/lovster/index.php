<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo $templatePath; ?>assets/css/app.css">
  </head>
  <body><!--[if lt IE 8]>
    <p class="browsehappy">
      You are using an <strong>outdated</strong> browser.
      Please <a href="http://browsehappy.com/">upgrade your browser</a>
      to improve your experience.
    </p><![endif]-->
    <p>hola</p>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo $templatePath; ?>assets/js/jquery.min.js"><\/script>')</script>
    <script src="<?php echo $templatePath; ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $templatePath; ?>assets/js/app.js"></script>
    <script src="<?php echo $templatePath; ?>assets/js/retina.min.js"></script>
    <script>
      //<![CDATA[
      document.write("<script async src='//HOST:3000/browser-sync-client.1.3.7.js'><\/script>".replace(/HOST/g, location.hostname));
      //]]>
    </script>
  </body>
</html>