  checkPermission(7, granted, "401-restricted.php", true);

  function granted()
  {
    $("#button-restrict").click(function()
  	{
      $(".background-loader").fadeIn();

  		var restrict = $("select[name='restrict'] option:selected").val();

      $.post("ajax/restrict.php", { restrict: restrict },
  			function(data)
  			{
          activity("succed", "ACCESS_RESTRICTED");

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

              $("select[name='restrict'] option:selected").remove();
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

    $("#button-free").click(function()
  	{
      $(".background-loader").fadeIn();

  		var free = $("select[name='free'] option:selected").val();

      $.post("ajax/restrict.php", { free: free },
  			function(data)
  			{
          activity("alert", "ACCESS_FREED");

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

                $("select[name='free'] option:selected").remove();
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
