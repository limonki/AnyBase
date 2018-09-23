var username, firstname, lastname, email, bio, username_, firstname_, lastname_, email_, bio_;

  username = $("input[name='username']").val();
  firstname = $("input[name='firstname']").val();
  lastname = $("input[name='lastname']").val();
  email = $("input[name='email']").val();
  bio = $("textarea[name='bio']").val();

	$("#basic input").bind("focusout", function(e)
	{
		if($(this).val() == "")
		{
			$(this).val($(this).prop("defaultValue"));
		}
	});

  $(".close").click(function()
  {
    $("#validation").fadeOut("fast");
  });

	$("#button-update").click(function()
	{
		username_ = $("input[name='username']").val();
  	firstname_ = $("input[name='firstname']").val();
    lastname_ = $("input[name='lastname']").val();
    email_ = $("input[name='email']").val();
    bio_ = $("textarea[name='bio']").val();

    var validation_errors = conv2sp(readCookie("validation-errors"));
    var username_empty = conv2sp(readCookie("username-is-empty"));
    var username_too_short = conv2sp(readCookie("username-too-short"));
    var email_empty = conv2sp(readCookie("email-is-empty"));
    var firstname_empty = conv2sp(readCookie("firstname-is-empty"));
    var lastname_empty = conv2sp(readCookie("lastname-is-empty"));

    var errors = "";

    if(username_ == "")
    {
      errors = errors + " - " + username_empty + "<br>";
      $("input[name='username']").addClass("error");
    }
    else $("input[name='username']").removeClass("error");
    if(username_.length < 4)
    {
      errors = errors + " - " + username_too_short + "<br>";
      $("input[name='username']").addClass("error");
    }
    else $("input[name='username']").removeClass("error");
    if(firstname_ == "")
    {
      errors = errors + " - " + firstname_empty + "<br>";
      $("input[name='firstname']").addClass("error");
    }
    else $("input[name='firstname']").removeClass("error");
    if(lastname_ == "")
    {
      errors = errors + " - " + lastname_empty + "<br>";
      $("input[name='lastname']").addClass("error");
    }
    else $("input[name='lastname']").removeClass("error");
    if(email_ == "")
    {
      errors = errors + " - " + email_empty + "<br>";
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
      
			$.post("ajax/profile-update.php", { default: username, username: username_, firstname: firstname_, lastname: lastname_, email: email_, bio: bio_ },
				function(data)
				{
          activity("succed", "EDITED_PROFILE");

          $(".background-loader").fadeOut("fast", function()
          {
            var title = conv2sp(readCookie("title"));
            var msg = conv2sp(readCookie("msg"));
            var close = conv2sp(readCookie("close"));

    				if(data.indexOf("Succedx01") > -1)
    				{
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
    				}
            else if(data.indexOf("Errorx01") > -1)
            {
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
            else if(data.indexOf("Errorx02") > -1)
            {
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
          });
				});
		  }
	});
