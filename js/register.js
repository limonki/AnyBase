function position()
{
  var top = ($(document).outerHeight()/2) - ($("#main").outerHeight()/2);
  var left = ($(document).outerWidth()/2) - ($("#main").outerWidth()/2);

  $("#main").css({top: top, left: left});
}

position();

  $(".close").click(function()
  {
    $("#validation").fadeOut("fast");
    $("#reload").fadeOut("fast");
  });

	$("#button-register").click(function()
	{
    var firstname = $("input[name='firstname']").val();
    var lastname = $("input[name='lastname']").val();
    var username = $("input[name='username']").val();
    var password = $("input[name='password']").val();
    var email = $("input[name='email']").val();
    var bio = $("textarea[name='bio']").val();
    var v = getUrlParameter("v");

    var validation_errors = conv2sp(readCookie("validation-errors"));
    var username_too_short = conv2sp(readCookie("username-too-short"));
    var password_too_short = conv2sp(readCookie("password-too-short"));
    var firstname_is_empty = conv2sp(readCookie("firstname-is-empty"));
    var lastname_is_empty = conv2sp(readCookie("lastname-is-empty"));
    var password_is_empty = conv2sp(readCookie("password-is-empty"));
    var username_is_empty = conv2sp(readCookie("username-is-empty"));
    var email_is_empty = conv2sp(readCookie("email-is-empty"));
    var user_taken = conv2sp(readCookie("user-taken"));
    var email_taken = conv2sp(readCookie("email-taken"));

    var errors = "";

    if(username.length < 4)
    {
      errors = errors + " - " + username_too_short + "<br>";
      $("input[name='firstname']").addClass("error");
    }
    else $("input[name='firstname']").removeClass("error");
    if(password.length < 8)
    {
      errors = errors + " - " + password_too_short + "<br>";
      $("input[name='password']").addClass("error");
    }
    else $("input[name='password']").removeClass("error");
    if(firstname == "")
    {
      errors = errors + " - " + firstname_is_empty + "<br>";
      $("input[name='firstname']").addClass("error");
    }
    else $("input[name='firstname']").removeClass("error");
    if(lastname == "")
    {
      errors = errors + " - " + lastname_is_empty + "<br>";
      $("input[name='lastname']").addClass("error");
    }
    else $("input[name='lastname']").removeClass("error");
    if(username == "")
    {
      errors = errors + " - " + username_is_empty + "<br>";
      $("input[name='username']").addClass("error");
    }
    else $("input[name='username']").removeClass("error");
    if(password == "")
    {
      errors = errors + " - " + password_is_empty + "<br>";
      $("input[name='password']").addClass("error");
    }
    else $("input[name='password']").removeClass("error");
    if(email == "")
    {
      errors = errors + " - " + email_is_empty + "<br>";
      $("input[name='email']").addClass("error");
    }
    else $("input[name='email']").removeClass("error");

		if(errors != "")
		{
      $("#validation").fadeIn();
      $(".validation-body span:first").html(validation_errors);
      $(".validation-body div:last-child").html(errors);
		}
		else
		{
      $(".background-loader").fadeIn();

			$.post("ajax/register.php", { v: v, firstname: firstname, lastname: lastname, username: username, password: password, email: email, bio: bio },
				function(data)
				{
          $(".background-loader").fadeOut("fast", function()
          {
    				if(data.indexOf("Succedx01") > -1)
    				{
              var title = conv2sp(readCookie("title"));
              var msg = conv2sp(readCookie("msg"));
              var close = conv2sp(readCookie("close"));

              if(detectMobile())
              {
                miniTopPopupIcon(1);
                miniTopPopupShow(msg, $(window).scrollTop());

                miniTopPopupClose($("#mini-top-popup #close"), $("#mini-top-popup"), "-100px", 600, false);
              }
              else
              {
                mainPopupShow(title, msg, close, 0);
                popupPosition($("#popup"), "", "");

                mainPopupClose($(" .modal-close"), false);
              }
              $("input").each(function() { $(this).val(""); });

              var reload_secs = 5;

              $("#reload").fadeIn();
              $("#reload .reload-body div:last-child").html(reload_secs);
              setInterval(function()
              {
                reload_secs--;
                $("#reload .reload-body div:last-child").html(reload_secs);
                if(reload_secs == 0) window.location = "index.php";
              }, 1000);
    				}
            else if(data.indexOf("Errorx01") > -1)
            {
              var title = conv2sp(readCookie("title"));
              var msg = conv2sp(readCookie("msg"));
              var close = conv2sp(readCookie("close"));

              if(detectMobile())
              {
                miniTopPopupIcon(5);
                miniTopPopupShow(msg, $(window).scrollTop());

                miniTopPopupClose($("#mini-top-popup #close"), $("#mini-top-popup"), "-100px", 600, false);
              }
              else
              {
                mainPopupShow(title, msg, close, 1);
                popupPosition($("#popup"), "", "");

                mainPopupClose($(" .modal-close"), false);
              }
            }

            if(data.indexOf("Errorx02") > -1)
            {
              errors = errors + " - " + user_taken + "<br>";
              $("input[name='username']").addClass("error");
            }
            else $("input[name='username']").removeClass("error");
            if(data.indexOf("Errorx03") > -1)
            {
              errors = errors + " - " + email_taken + "<br>";
              $("input[name='email']").addClass("error");
            }
            else $("input[name='email']").removeClass("error");

            if(errors != "")
        		{
              $("#validation").fadeIn();
              $(".validation-body span:first").html(validation_errors);
              $(".validation-body div:last-child").html(errors);
        		}
          });
				});
		  }
	});

  $("#button-back").click(function()
	{
    window.location = "index.php";
  });
