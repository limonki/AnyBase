<!doctype html>
<html lang="{SITE.LANGUAGE}">
<head>
  <meta charset="utf-8">
  <title>{SITE.TITLE}</title>
  <meta name="description" content="{SITE.DESCRIPTION}">
  <meta name="author" content="{SITE.AUTHOR}">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no,,minimum-scale=1.0, maximum-scale=1.0">
  <link rel="icon" href="images/favicon.png">
  <link rel="stylesheet" href="css/grid.css">
  <link rel="stylesheet" href="css/install.css">
  <link rel="stylesheet" href="css/popup.css">
  <link rel="stylesheet" href="css/forms-layout.css">
</head>
<body>
  <div class="blur" id="validation">
    <div class="validation-body">
      <div class="close" id="close"></div>
      <img src="images/icons/validation32x32.png"><span></span><br>
      <div></div>
    </div>
  </div>
  <div class="help-box"></div>
  <div id="main">
    <div class="center">
      <img src="images/icon.png">
    </div>
    {MAIN}
    <div class="center">
      <span class="loader">
        <img src="images/loader4.gif">
      </span>
    </div>
  </div>
  <script src="js/jquery-latest.min.js"></script>
  <script src="js/functions.js"></script>
  <script src="js/install.js"></script>
  <script src="js/resize-load-scroll.js"></script>
</body>
</html>
