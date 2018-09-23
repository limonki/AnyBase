var pass_old, pass_new, pass_confirm, username_, pass_old_, pass_new_, pass_confirm_;

  pass_old = $("input[name='old']").val();
  pass_new = $("input[name='new']").val();
  pass_confirm = $("input[name='confirm']").val();

	$("#password input").bind("focusin", function(e)
	{
		if($(this).hasClass("password") && ($(this).val() != pass_old || $(this).val() != pass_new || $(this).val() != pass_confirm))
    {
      $(this).get(0).type = "password";
    }

    if($(this).val() == pass_old || $(this).val() == pass_new || $(this).val() == pass_confirm)
    {
      $(this).val('');
    }
  }).bind("focusout", function(e)
	{
    if($(this).val() == "" || ($(this).hasClass("password") && ($(this).val() == pass_old || $(this).val() == pass_new || $(this).val() == pass_confirm)))
    {
      $(this).get(0).type = "text";
      $(this).val($(this).prop("defaultValue"));
    }
	});

  $(".close").click(function()
  {
    $("#validation").fadeOut("fast");
  });

	$("#button-password").click(function()
	{
		username_ = $("input[name='username']").val();
  	pass_old_ = $("input[name='old']").val();
    pass_new_ = $("input[name='new']").val();
    pass_confirm_ = $("input[name='confirm']").val();

    var validation_errors = conv2sp(readCookie("validation-errors"));
    var password_too_short = conv2sp(readCookie("password-too-short"));
    var password_not_same = conv2sp(readCookie("password-not-same"));

    var errors = "";

    if(pass_new_ != pass_confirm_)
    {
      errors = errors + " - " + password_not_same + "<br>";
      $("input[name='new']").addClass("error");
      $("input[name='confirm']").addClass("error");
    }
    else if(pass_new_.length < 8)
    {
      errors = errors + " - " + password_too_short + "<br>";
      $("input[name='new']").addClass("error");
      $("input[name='confirm']").addClass("error");
    }
    else
    {
      $("input[name='new']").removeClass("error");
      $("input[name='confirm']").removeClass("error");
    }

		if(errors != "")
		{
      $("#validation").fadeIn();
      $(".validation-body span:first").html(validation_errors);
      $(".validation-body div:last-child").html(errors);
		}
		else
		{
      $(".background-loader").fadeIn();

			$.post("ajax/pass-update.php", { username: username_, pass_old: pass_old_, pass_new: pass_new_, pass_confirm: pass_confirm_ },
				function(data)
				{
          $(".background-loader").fadeOut("fast", function()
          {
            var title = conv2sp(readCookie("title"));
            var msg = conv2sp(readCookie("msg"));
            var close = conv2sp(readCookie("close"));

    				if(data.indexOf("Succedx01") > -1)
    				{
              activity("succed", "PASS_CHANGE_PROFILE");

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
            else if(data.indexOf("Errorx03") > -1)
            {
              if(detectMobile())
              {
                miniTopPopupIcon(4);
                miniTopPopupShow(msg, $(window).scrollTop());

                miniTopPopupClose($("#mini-top-popup #close"), $("#mini-top-popup"), "-100px", 600, false);
              }
              else
              {
                mainPopupShow(title, msg, close, 2);
                popupPosition($("#popup"), "", "");

                mainPopupClose($(" .modal-close"), false);
              }
            }
            else if(data.indexOf("Errorx04") > -1)
            {
              if(detectMobile())
              {
                miniTopPopupIcon(4);
                miniTopPopupShow(msg, $(window).scrollTop());

                miniTopPopupClose($("#mini-top-popup #close"), $("#mini-top-popup"), "-100px", 600, false);
              }
              else
              {
                mainPopupShow(title, msg, close, 2);
                popupPosition($("#popup"), "", "");

                mainPopupClose($(" .modal-close"), false);
              }
            }
          });
			});
		}
	});
