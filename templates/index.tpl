<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{SITE.TITLE}</title>
  <meta name="description" content="{SITE.DESCRIPTION}">
  <meta name="author" content="{SITE.AUTHOR}">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no,,minimum-scale=1.0, maximum-scale=1.0">
  <link rel="icon" href="images/favicon.png">
  <link rel="stylesheet" href="css/loader.css">
  <link rel="stylesheet" href="css/grid.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/popup.css">
</head>
<body>
  <div class="loader"></div>
  <div id="top-bar">
    <span class="elem"><a href="http://www.anybase.cba.pl/terms-and-conditions.php">{TERMS.AND.CONDITIONS}</a></span>
    <span class="elem right"><a href="http://www.anybase.cba.pl/privacy-policy.php">{PRIVACY.POLICY}</a></span>
  </div>
  <div id="cookies">
		<div class="close" id="close"></div>
    <div class="cookies-title">{COOKIES.TITLE}</div>
		<div>{COOKIES.TEXT}</div>
    <div class="center margin-8">
      <button type="submit" class="cookies-button active agree"><i class="fa fa-check"></i> {COOKIES.AGREE}</button>
      <button type="submit" class="cookies-button more-info"><i class="fa fa-lightbulb-o"></i> {COOKIES.MORE.INFO}</button>
    </div>
	</div>
  <div id="mini-top-popup">
		<div class="close" id="close"></div>
		<div id="icon"></div>
		<span></span>
	</div>
  <div class="col-18" style="margin: 0 auto">
    <div id="login-form">
      <img src="images/icon.png" class="center">
      <div class="title">{IDENTIFY.MSG}</div>
      {LOGIN.FORM}
      <div class="center"><img class="no-margin-padding" src="images/icons/lock19x16.png"> <a href="register.php">{REGISTER.NEW.USER}</a></div>
      <div class="center"><img class="no-margin-padding" src="images/icons/forgotten16x10.png"> <a href="forgotten.php">{FORGOTTEN}</a></div>
      <div class="center"><img class="no-margin-padding" src="images/icons/get-help16x16.png"> <a href="http://www.anybase.cba.pl/help.php">{HELP}</a></div>
      <br>
      <div class="center">{SUPPORT} <a href="mailto:{SUPPORT.EMAIL}">{SUPPORT.EMAIL}</a></div>
    </div>
  </div>
  <script src="js/jquery-latest.min.js"></script>
  <script src="js/functions.js"></script>
  <script src="js/index.js"></script>
  <script src="js/url-get.js"></script>
  <script src="js/loader.js"></script>
  {JS.SCRIPTS}
</body>
</html>
