var table_name_def, column_name_def, length_def;

var title_val = conv2sp(readCookie("title-val"));
var msg_val = conv2sp(readCookie("msg-val"));
var close_val = conv2sp(readCookie("close-val"));

  checkPermission(6, granted, "401-restricted.php", true);

  function granted()
  {
    table_name_def = $("input[name='table-name']").val();
    column_name_def = $("input[name='column-name[]']").val();
    length_def = $("input[name='length[]']").val();

    $(document).on("click", ".delete-column", function()
    {
      $(this).parents().eq(1).remove();
    });

    $(".append-columns").on("focusin", "input", function()
    {
      if($(this).val() == table_name_def || $(this).val() == column_name_def || $(this).val() == length_def)
      {
        $(this).val('');
      }
    }).on("focusout", "input", function()
    {
      if($(this).val() == "")
      {
        $(this).val($(this).prop("defaultValue"));
      }
    });

    $("input").bind("focusin", function(e)
  	{
      if($(this).val() == table_name_def || $(this).val() == column_name_def || $(this).val() == length_def)
      {
        $(this).val('');
      }
    }).bind("focusout", function(e)
  	{
      if($(this).val() == "")
      {
        $(this).val($(this).prop("defaultValue"));
      }
  	});

    $("#button-create").click(function()
  	{
      $(".background-loader").fadeIn();

  		var table_name_ = $("input[name='table-name']").val();

      var column_name_ = $('input[name^=column-name]').map(function(idx, elem) {
        return $(elem).val();
      }).get();
      var type_ = $('select[name^=type]').map(function(idx, elem) {
        return $(elem).val();
      }).get();
      var length_ = $('input[name^=length]').map(function(idx, elem) {
        return $(elem).val();
      }).get();
      var null_ = $('input[name^=null]:checked').map(function(idx, elem) {
        return $(elem).val();
      }).get();
      var attr_ = $('select[name^=attr]').map(function(idx, elem) {
        return $(elem).val();
      }).get();
      var index_ = $('select[name^=index]').map(function(idx, elem) {
        return $(elem).val();
      }).get();
      var a_i_ = $('input[name^=a_i]:checked').map(function(idx, elem) {
        return $(elem).val();
      }).get();

      var flag = false;

      var title_val = conv2sp(readCookie("title-val"));
      var msg_val = conv2sp(readCookie("msg-val"));
      var close_val = conv2sp(readCookie("close-val"));

      $('#select-type option').filter(':selected').each(function(i)
      {
        if($(this).text().indexOf("CHAR") > 0 || $(this).text() == "CHAR")
        {
          if($(this).parents().eq(2).find("input[name^=column-name]").val() !== column_name_def)
          {
            if(!$.isNumeric($(this).parents().eq(2).find("input[name^=length]").val())) flag = true;
          }
        }
      });

      if(flag)
      {
        $(".background-loader").fadeOut("fast", function()
        {
          if(detectMobile())
          {
            miniTopPopupIcon(4);
            miniTopPopupShow(msg_val, $(window).scrollTop());

            miniTopPopupClose($("#mini-top-popup #close"), $("#mini-top-popup"), "-100px", 600, false);
          }
          else
          {
            mainPopupShow(title_val, msg_val, close_val, 2);
            popupPosition($("#popup"), "", "");

            mainPopupClose($(" .modal-close"), false);
          }
        });
      }
      else
      {
        $.post("ajax/create-table.php", { table_name: table_name_, column_name: column_name_, type: type_, length: length_, null: null_, attr: attr_, index: index_, a_i: a_i_ },
          function(data)
          {
            //alert(data);
            //console.log(data);

            $(".background-loader").fadeOut("fast", function()
            {
              var title = conv2sp(readCookie("title"));
              var msg = conv2sp(readCookie("msg"));
              var close = conv2sp(readCookie("close"));

              if(data.indexOf("Succedx01") > -1)
              {
                activity("succed", "TABLE_ADDED");

                if(detectMobile())
                {
                  miniTopPopupIcon(2);
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
                msg = msg + " " + data.replace("Errorx01", " ");
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
  }
