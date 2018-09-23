var user_not_updated = conv2sp(readCookie("user-not-updated"));
var user_success = conv2sp(readCookie("user-success"));

function output(data)
{
  $(".background-loader").fadeOut("fast", function()
  {
    if(data.indexOf("Succedx01") > -1)
    {
      $("#reload").fadeIn();
      $("#reload span").html(user_success);

      activity("succed", "USER_UPDATED");

      setTimeout(function(){ $("#reload").fadeOut("fast"); }, 500);
    }
    else if(data.indexOf("Errorx01") > -1)
    {
      $("#validation").fadeIn();
      $(".validation-body span:first").html(user_not_updated);
    }
  });
}

  $(".close").click(function()
  {
    $("#validation").fadeOut("fast");
    $("#reload").fadeOut("fast");
  });

  $("#button-generate").on("click", function()
  {
    checkPermission(7, generate, "401-restricted.php", true);

    function generate()
    {
      $(".background-loader").fadeIn();

      var level = $("select[name='level'] option:selected").val();

      $.post("ajax/generate-reg-link.php", { level: level },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          var title = conv2sp(readCookie("title"));
          var msg = conv2sp(readCookie("msg"));
          var close = conv2sp(readCookie("close"));

          if(data.indexOf("Succedx01") > -1)
          {
            activity("info", "GENERATE_LINK");

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

            $("#reg-link").val(data.replace("Succedx01", ""));
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
    }
  });

  $("#button-clear").on("click", function()
  {
    checkPermission(7, clear, "401-restricted.php", true);

    function clear()
    {
      $(".background-loader").fadeIn();

      $.post("ajax/clear-reg-links.php", { },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          var title = conv2sp(readCookie("title"));
          var msg = conv2sp(readCookie("msg"));
          var close = conv2sp(readCookie("close"));

          if(data.indexOf("Succedx01") > -1)
          {
            activity("alert", "CLEAR_REGLINKS");

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
    }
  });

  $("select[name='user']").on("change", function()
  {
    var user = $("select[name='user'] option:selected").val();

    checkPermission(5, data, "401-restricted.php", true);

    function data()
    {
      $(".background-loader").fadeIn();

      $.post("ajax/user-data.php", { user: user },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          if(data.indexOf("Location: ") > -1) window.location = "401-restricted.php";
          else
          {
            var elem = $("select[name='user']").parent().find("div[class='row']");
            if(elem.length > 0)
            {
              elem.html('');
              $("select[name='user']").parent().append(data);
            }
            else $("select[name='user']").parent().append(data);
          }
        });
    	});
    }
  });

  $(document).on("change", "select[name='permission']", function()
  {
    var user = $("select[name='user'] option:selected").val();
    var email = $("input[name='email']").val();
    var firstname = $("input[name='firstname']").val();
    var lastname = $("input[name='lastname']").val();
    var bio = $("textarea[name='bio']").val();
    var permission_lvl = $("select[name='permission'] option:selected").val().split(" - ")[0];

    checkPermission(7, permission, "401-restricted.php", true);
    ifNotPermission(7, withoutPermission, function(){});

    function permission()
    {
      $(".background-loader").fadeIn();

      $.post("ajax/update-user-data.php", { user: user, email: email, firstname: firstname, lastname: lastname, bio: bio, permission: permission_lvl },
      function(data)
      {
        output(data);
    	});
    }

    function withoutPermission()
    {
      checkPermission(5, exec, "401-restricted.php", true);

      function exec()
      {
        $(".background-loader").fadeIn();

        $.post("ajax/update-user-data.php", { user: user, email: email, firstname: firstname, lastname: lastname, bio: bio },
        function(data)
        {
          output(data);
      	});
      }
    }
  });

  $(document).on("change", "form[id='user-info']", function()
  {
    var user = $("select[name='user'] option:selected").val();
    var email = $("input[name='email']").val();
    var firstname = $("input[name='firstname']").val();
    var lastname = $("input[name='lastname']").val();
    var bio = $("textarea[name='bio']").val();

    checkPermission(5, lvl5, "401-restricted.php", true);

    function lvl5()
    {
      $(".background-loader").fadeIn();

      $.post("ajax/update-user-data.php", { user: user, email: email, firstname: firstname, lastname: lastname, bio: bio },
      function(data)
      {
        output(data);
    	});
    }
  });
