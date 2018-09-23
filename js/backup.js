  checkPermission(5, granted, "401-restricted.php", true);

  function granted()
  {
    var backup_now = getUrlParameter("backup-now");
    var execute = getUrlParameter("execute");

  	if(backup_now && execute)
    {
      $(".background-loader").fadeIn();
      setTimeout(function()
      {
        $(".background-loader").fadeOut();
        $("#backup-now button").trigger("click");
      }, 3000);
    }

    $("#button-configure").on("click", function()
    {
      $(".background-loader").fadeIn();

      var auto_download = $("input[name='auto-download']:checked").val();
      if(typeof auto_download != "undefined") auto_download = 1;
      else auto_download = 0;
      var backup_path = $("input[name='backup-path']").val();

      $.post("ajax/update-configuration.php", { auto_download: auto_download, backup_path: backup_path },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          var title = conv2sp(readCookie("title"));
          var msg = conv2sp(readCookie("msg"));
          var close = conv2sp(readCookie("close"));

          if(data.indexOf("Succedx01") > -1)
          {
            activity("succed", "CONFIGURE_BACKUP");

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

    $("#button-restore").click(function()
  	{
      $(".background-loader").fadeIn();

      var file_ = $('#restore-filename-1').prop('files')[0];

      var form_data = new FormData();
      form_data.append('file', file_);

  		$.ajax({url: 'ajax/backup-restore.php', dataType: 'text', cache: false, contentType: false, processData: false, data: form_data, type: 'post', success: function(data)
    		{
          $(".background-loader").fadeOut("fast", function()
          {
            var title = conv2sp(readCookie("title"));
            var msg = conv2sp(readCookie("msg"));
            var close = conv2sp(readCookie("close"));

        		if(data.indexOf("Succedx01") > -1)
        		{
              activity("succed", "SUCCESSFUL_RESTORE");

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
              activity("error", "ERROR_RESTORE");

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
    		}
      });
  	});

    $("#backup-now button").on("click", function()
    {
      $(".background-loader").fadeIn();

      $.post("ajax/backup-create.php", {  },
      function(data)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          var title = conv2sp(readCookie("title"));
          var msg = conv2sp(readCookie("msg"));
          var close = conv2sp(readCookie("close"));

          if(data.indexOf("Succedx01") > -1)
          {
            activity("succed", "SUCCESFUL_BACKUP");

            var file = data.replace("Succedx01", "");

            window.open(file);

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
            activity("error", "ERROR_BACKUP");

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
