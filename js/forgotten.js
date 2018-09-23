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

	$("#button-reset").click(function()
	{
    var email = $("input[name='email']").val();

    var validation_errors = conv2sp(readCookie("validation-errors"));
    var email_is_empty = conv2sp(readCookie("email-is-empty"));
    var email_not_exist = conv2sp(readCookie("email-not-exist"));

    var errors = "";

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

			$.post("ajax/send-reset-link.php", { email: email },
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
              errors = errors + " - " + email_not_exist + "<br>";
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
