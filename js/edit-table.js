var appended = 0;
var after_array_ = [];
var delete_array_ = [];

  checkPermission(5, granted, "401-restricted.php", true);

  function granted()
  {
    var append;

    $.post("ajax/basic-column.php", { },
    function(data)
    {
      append = data.replace('Succedx01', '');
    });

    $(document).on("click", ".delete-column", function()
    {
      var name = $(this).parents().eq(1).find("input[name^='column-name']").val();

      $("select[name='add-after'] option").each(function()
      {
        if($(this).text() === name) $(this).remove();
      });
      delete_array_.push(name);
      $(this).parents().eq(1).remove();
    });

    $(document).on("click", ".primary", function()
    {
      if($(this).find("select[name^='index']").val() == "PRIMARY")
      {
        $(this).find("select[name^='type'] option:first").prop("selected", true);
        $(this).find("select[name^='type']").prop("disabled", true);
      }
      else $(this).find("select[name^='type']").prop("disabled", false);
    });

    $(".edit-link").click(function()
  	{
      var elem = $(this);
  		var table_name_ = elem.data("name");

      $(".background-loader").fadeIn();

      var table = $(".edit-link").closest("table");

      $(".edit").fadeOut(500, function()
      {
        table.fadeOut(0);
        setTimeout(function() {
          $(".edit").fadeIn(500);
          $(".background-loader").fadeOut("fast");
        }, 100);

      //$("#confirm").click(function()
    	//{
        $.post("ajax/edit-table-form.php", { table_name: table_name_ },
          function(data)
          {
            var tmp = data.split('/s/');

            $(".edit-link").closest(".edit").append(tmp[0]);
            $(".foreign").find("select[name^='index']").append('<option id="foreign" selected>FOREIGN KEY<option>');
            $(".foreign").find("input, select").each(function()
            {
              $(this).prop("disabled", true);
            })
            $(".foreign").find("input:first").prop("disabled", false);
            $(".foreign").find(".delete-column").hide();

            if($(".primary").find("select[name^='index']").val() == "PRIMARY")
            {
              $(".primary").find("select[name^='type']").prop("disabled", true);
            }
            $(".primary").find(".delete-column").hide();

            //$(".edit-link").closest("table").html(data);

            if(data.indexOf("Succedx01") > -1)
            {
              var column_name_def = "";
              var length_def = "";

              activity("alert", "TABLE_EDITED");

              $(document).on("focusin", "input", function()
              {
                if($(this).val() == column_name_def || $(this).val() == length_def)
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

              $(document).on('click', '#button-add', function()
              {
                var after = $("select[name='add-after'] option").filter(':selected').data("index");
                var num = $("input[name='num']").val();

                if(num != "")
                {
                  $(".background-loader").fadeIn();

                  $(".edit").fadeOut(500, function()
                  {
                    for(var i = 0; i < num; i++)
                    {
                      $("#" + after).after('<div id="appended-' + appended + '">' + append + '</div>');
                      appended++;
                      after_array_.push({appended: after});
                    }

                    column_name_def = $("#appended-0 input[name='column-name[]']").val();
                    length_def = $("#appended-0 input[name='length[]']").val();

                    $(".edit").fadeIn(500, function() { $(".background-loader").fadeOut("fast"); });
                  });
                }
              });

              $(document).on('click', '#button-edit', function()
              {
                $(".background-loader").fadeIn();

                $(".foreign").find("select[name^='index'] option:first").prop("selected", true);

                var table_name_def_ = $("input[name='table-name']").prop("defaultValue");

                var column_name_def_ = $('input[name^=column-name]').map(function(idx, elem) {
                  return $(elem).prop("defaultValue");
                }).get();
                var length_def_ = $('input[name^=length]').map(function(idx, elem) {
                  return $(elem).prop("defaultValue");
                }).get();

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

                $(".foreign").find("select[name^='index'] option[id='foreign']").prop("selected", true);

                var flag = false;

                var title_val = conv2sp(readCookie("title-val"));
                var msg_val = conv2sp(readCookie("msg-val"));
                var close_val = conv2sp(readCookie("close-val"));

                $('#select-type option').filter(':selected').each(function(i)
                {
                  if(($(this).text().indexOf("CHAR") > 0 || $(this).text() == "CHAR"))
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
                  $.post("ajax/edit-table.php", { delete_array: delete_array_, after_array: after_array_, table_name_def: table_name_def_, column_name_def: column_name_def_, length_def: length_def_, table_name: table_name_, column_name: column_name_, type: type_, length: length_, null: null_, attr: attr_, index: index_, a_i: a_i_ },
                    function(data)
                    {
                      //alert(data);
                      //console.log(data);

                      $(".background-loader").fadeOut("fast", function()
                      {
                        var tmp = data.replace("Errorx01", " ");
                        tmp = tmp.replace("Succedx01", " ");

                        var title = conv2sp(readCookie("title"));
                        var msg = conv2sp(readCookie("msg"))+tmp;
                        var close = conv2sp(readCookie("close"));

                        if(data.indexOf("Succedx01") > -1)
                        {
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
            else if(data.indexOf("Errorx01") > -1)
            {
              $(".alert.error").fadeIn(500);

              setTimeout(function() {
                $(".alert.error").fadeOut(500, function()
                {
                  table.fadeIn(500);
                });
              }, 5000);
            }
          });
        //});
      });
  	});
  }
