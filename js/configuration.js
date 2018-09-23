function preview(val, elem)
{
  $(".background-loader").fadeIn();

  $.post("ajax/preview-date-time.php", { val: val },
  function(data)
  {
    $(".background-loader").fadeOut("fast");

    elem.text(data);
  });
}

  checkPermission(5, granted, "401-restricted.php", true);

  function granted()
  {
    $("input[name='date']").on("click", function()
    {
      preview($(this).val(), $("#preview-date"));
    });

    $("input[id='own-date']").on("click", function()
    {
      $("input[id='check-own-date']").prop("checked", true);
    });

    $("input[id='own-date']").on("change", function()
    {
      var val = $("input[id='own-date']").val();

      $("input[id='check-own-date']").val(val);

      preview($(this).val(), $("#preview-date"));
    });

    $("input[name='time']").on("click", function()
    {
      preview($(this).val(), $("#preview-time"));
    });

    $("input[id='own-time']").on("click", function()
    {
      $("input[id='check-own-time']").prop("checked", true);
    });

    $("input[id='own-time']").on("change", function()
    {
      var val = $("input[id='own-time']").val();

      $("input[id='check-own-time']").val(val);

      preview($(this).val(), $("#preview-time"));
    });

    $("#button-time-date").on("click", function()
    {
      $(".background-loader").fadeIn();

      var date = $("input[name='date']:checked").val();
      var time = $("input[name='time']:checked").val();

      $.post("ajax/update-configuration.php", { date: date, time: time },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          var title = conv2sp(readCookie("title"));
          var msg = conv2sp(readCookie("msg"));
          var close = conv2sp(readCookie("close"));

          if(data.indexOf("Succedx01") > -1)
          {
            activity("succed", "TIME_DATE_CONFIGURE_SITE");

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
        });
    	});
    });

    $("#button-advanced-settings").on("click", function()
    {
      $(".background-loader").fadeIn();

      var support_contact_email = $("input[name='support-contact-email']").val();
      var language = $("select option:selected").text();

      $.post("ajax/update-configuration.php", { support_contact_email: support_contact_email, language: language },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          var title = conv2sp(readCookie("title"));
          var msg = conv2sp(readCookie("msg"));
          var close = conv2sp(readCookie("close"));

          if(data.indexOf("Succedx01") > -1)
          {
            activity("succed", "ADVANCED_CONFIGURE_SITE");

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
        });
    	});
    });

    $("#button-session-expiration").on("click", function()
    {
      $(".background-loader").fadeIn();

      var session_expires = $("input[name='session-expires']").val();

      $.post("ajax/update-configuration.php", { session_expires: session_expires },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          var title = conv2sp(readCookie("title"));
          var msg = conv2sp(readCookie("msg"));
          var close = conv2sp(readCookie("close"));

          if(data.indexOf("Succedx01") > -1)
          {
            activity("succed", "SESSION_CONFIGURE_SITE");

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
        });
    	});
    });
  }
