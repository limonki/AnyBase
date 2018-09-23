<html>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>{SITE.TITLE}</title>
	<meta name="description" content="{SITE.DESCRIPTION}">
	<meta name="author" content="{SITE.AUTHOR}">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no,,minimum-scale=1.0, maximum-scale=1.0">
	<link rel="icon" href="images/favicon.png" type="image/png">
	<link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/forms-layout.css">
  <link rel="stylesheet" href="css/loader.css">
  <link rel="stylesheet" href="css/grid.css">
  <link rel="stylesheet" href="css/popup.css">
  <link rel="stylesheet" href="css/alert.css">
</head>
<body>
	<div class="loader"></div>
	<div class="background-loader">
		<div class="data-loader"></div>
	</div>
	<div class="background-modal-info">
		<div class="modal-info" id="info">
			<header>
				<span class="modal-info-close close">×</span>
				<div id="info-icon"></div>
			</header>
			<p></p>
			<div class="modal-info-body">
				<p></p>
			</div>
			<footer>
				<span class="modal-info-close button"></span>
			</footer>
		</div>
	</div>
	<div class="background-modal-box">
		<div class="modal-box" id="popup">
			<header>
				<span class="modal-close close">×</span>
				<div id="icon"></div>
				<h3></h3>
			</header>
			<div class="modal-body">
				<p></p>
			</div>
			<footer>
				<span class="modal-close button"></span>
			</footer>
		</div>
	</div>
	<div class="blur" id="mini-top-popup">
		<div class="close" id="close"></div>
		<div id="icon"></div>
		<span></span>
	</div>
  <div class="blur" id="validation">
    <div class="validation-body">
      <div class="close" id="close"></div>
		  <img src="images/icons/validation32x32.png"><span></span><br>
      <div></div>
    </div>
	</div>
	<div class="blur" id="reload">
    <div class="reload-body">
      <div class="close" id="close"></div>
		  <span>{RELOAD.PAGE}</span><br>
      <div></div>
    </div>
	</div>
	<div class="blur" id="menu-container">
		<div id="menu-bar">
			<img src="images/icon.png">
		</div>
		{SITE.MENU}
	</div>
	<div id="main-container">
		<div class="row">
			<div class="col-18">
				<div class="shadow" id="main-bar">
				<div id="user">
					<div id="user-image">
						<img src="{USER.IMAGE.LOW}">
					</div>
					<div id="user-info">
						{USER.INFO}
						<div id="user-permission">
							{USER.PERMISSION}
						</div>
					</div>
						<div id="user-actions">
							<img src="images/down24x8.png">
							<div class="shadow-b-l" id="user-actions-drop-down">
								<div>
									<img src="{USER.IMAGE.HIGH}">
									<div id="user-actions-links">
										<a href="profile.php">Edit profile</a><br>
										<a href="{LOGOUT.LINK}">Log out</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-18">
				{PANEL.ELEMENTS}
				<div class="col-18 panel-foot">
					{PANEL.FOOT.TEXT} <div class="heart"></div>.
				</div>
			</div>
		</div>
	</div>
  <script src="js/jquery-latest.min.js"></script>
  <script src="js/functions.js"></script>
	<script src="js/resize-load-scroll.js"></script>
	<script src="js/file-button.js"></script>
	<script src="js/menu.js"></script>
	<script src="js/loader.js"></script>
	{JS.SCRIPTS}
</body>
</html>
