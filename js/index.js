var checkCloseX = 0;

var username = readCookie('username');
var password = readCookie('password');

var login_permission = false;
var finished = true;

function wrongUserOrPass()
{
  var msg_login_error = readCookie('msg-login-error');
  if(msg_login_error)
  {
    msg_login_error = conv2sp(msg_login_error);

    miniTopPopupIcon(4);
    miniTopPopupShow(msg_login_error, $(window).scrollTop());
  }
}

function acceptCookies()
{
  var msg_accept_cookies = readCookie('msg-accept-cookies');
  if(msg_accept_cookies)
  {
    msg_accept_cookies = conv2sp(msg_accept_cookies);

    miniTopPopupIcon(4);
    miniTopPopupShow(msg_accept_cookies, $(window).scrollTop());
  }

  if(finished)
  {
    finished = false;
    var shake = 10;
    var fade = 1;
    var dir = 1;
    $(".agree").css({position: "relative"});
    var interval = setInterval(function()
    {
      $(".agree").animate({left: shake*dir}, 100);
      shake = shake - fade;
      if(dir == 1) dir = -1;
      else  dir = 1;
      if(shake < 0)
      {
        $(".agree").css({left: 0});
        clearInterval(interval);
        finished = true;
      }
    }, 100);
  }
}

  $("#login-form").css({position: "relative", opacity: "0.0"});

  if(readCookie("cookies-agree") == "true")
  {
    $("#cookies").fadeOut(0);
    login_permission = true;
  }

  $(".agree, #cookies .close").on("click", function()
  {
    $("#cookies").fadeOut("slow");
    createCookie("cookies-agree", "true", "");
    login_permission = true;
  });

  $(".more-info").on("click", function()
  {
    window.open("https://en.wikipedia.org/wiki/HTTP_cookie");
  });

	$("input").bind("focusin", function(e)
	{
		if($(this).attr("name") == "password") $(this).get(0).type = "password";
		if($(this).val() == username || $(this).val() == password) $(this).data("content", $(this).val()).val("");
	}).bind("focusout", function(e)
	{
	  if($(this).attr("name") == "password" && ($(this).val() == password ||  $(this).val() == "")) $(this).get(0).type = "text";
		if ($(this).val() == "")
		{
			$(this).val($(this).data("content"));
		}
	});

	$("#button").click(function()
	{
		var username_ = $("#login-panel [name='username']").val();
		var password_ = $("#login-panel [name='password']").val();

    if(!login_permission)
    {
      acceptCookies();
    }
    else if(username_ == username || password_ == password || username_ == "" || password_ == "")
		{
      wrongUserOrPass();
		}
    else
    {
      $.post("ajax/authorize.php", { username: username_, password: password_ },
			function(data)
			{
				if(data == "Succedx01")
				{
					$("form")[0].reset();
          var v = getUrlParameter("v");
          var r = "main.php";

          if(v) r = r + '?v=' + v;

          window.location.replace(r);
				}
        else if(data == "Errorx01")
        {
          wrongUserOrPass();
        }
			});
    }
  });

  $(document).on("mouseleave", function(e)
  {
    if(e.pageY <= 5) checkCloseX = 1;
    else checkCloseX = 0;
  });

  $(document).ready(function()
  {
		$(".elem").fadeIn(2000);
  });

  window.onbeforeunload = function(event)
  {
    if(event)
    {
      if(checkCloseX == 1) eraseCookie('refreshed');
    }
  };
