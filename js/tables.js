var elem;
var additional;
var num;
var count = 1;
var columns;
var table;

  checkPermission(2, granted, "401-restricted.php", true);

  function granted()
  {
    var restricted = conv2sp(readCookie("restricted"));
    var record_not_added = conv2sp(readCookie("record-not-added"));
    var record_not_updated = conv2sp(readCookie("record-not-updated"));
    var record_not_deleted = conv2sp(readCookie("record-not-deleted"));
    var record_success = conv2sp(readCookie("record-success"));

    $(".close").click(function()
    {
      $("#validation").fadeOut("fast");
    });

    $(".edit-link").on("click", function()
    {
      $(".background-loader").fadeIn();

      var append;
      table = $(this).parent().text().split(". ")[1];
      var elem_fade = $(".edit-link").closest("table");

      $.post("ajax/edit-table-records.php", { table: table },
      function(data)
      {
        append = data.replace('Succedx01', '');

        elem_fade.fadeOut(500, function()
        {
          $(".edit").append(append);
          columns = $("#edit-table tr:first").html();
          $("#key input").prop('disabled', true);
          $("#key input").val($("#edit-table tr").length - 1);
          additional = $("#edit-table tr:last-child").html();
          $("#edit-table tr:last-child").find("td:last-child img").css({display: "none"});
          num = $("input[name!='edit']").length;

          ifNotPermission(3, restrict, "");

          function restrict()
          {
            $(".restricted").text(restricted);
          }

          $(".background-loader").fadeOut();
        });
      });

      $(document).on("focusout", "input[name='edit']", function()
      {
        var content = $(this).val();

        $(this).parent().html(content);

        if(elem.parent().attr("id") != "newest")
        {
          checkPermission(4, update, "401-restricted.php", true);

          function update()
          {
            $(".background-loader").fadeIn();

            var update = elem.parent().html();

            $.post("ajax/update-table-records.php", { table: table, columns: columns, update: update },
            function(data)
            {
              $(".background-loader").fadeOut("fast", function()
              {
                if(data.indexOf("Succedx01") > -1)
                {
                  $("#reload").fadeIn();
                  $("#reload span").html(record_success);

                  activity("alert", "TABLE_CONTENT");

                  setTimeout(function(){ $("#reload").fadeOut("fast"); }, 500);
                }
                else if(data.indexOf("Errorx01") > -1)
                {
                  $("#validation").fadeIn();
                  $(".validation-body span:first").html(record_not_updated);
                }
              });
            });
          }
        }
      });

      $(document).on("focusout", "input[name!='edit']", function()
      {
        var inputs = $("input[name!='edit']");
        var flag = false;

        $.each(inputs, function()
        {
          if($(this).val() != "" || $(this).parent().attr("id") == "key")
          {
            var content = $(this).val();
            $(this).parent().html(content);
            flag = true;
          }
          else flag = false;
        });

        if(count >= num - 1 && flag)
        {
          checkPermission(5, add, "401-restricted.php", true);
          
          count = 0;

          function add()
          {
            $(".background-loader").fadeIn();

            var update = $("#newest").html();

            $.post("ajax/add-table-records.php", { table: table, columns: columns, update: update },
            function(data)
            {
              $(".background-loader").fadeOut("fast", function()
              {
                if(data.indexOf("Succedx01") > -1)
                {
                  $("#newest").removeAttr("id");

                  $("#edit-table tr:last-child").find("td:last-child img").css({display: "initial"});
                  $("#edit-table").append('<tr id="newest">' + additional + '</tr>');
                  $("#edit-table tr:last-child").find("td:last-child img").css({display: "none"});
                  $("#key input").val($("#edit-table tr").length - 1);

                  $("#reload").fadeIn();
                  $("#reload span").html(record_success);

                  activity("alert", "TABLE_CONTENT");

                  setTimeout(function(){ $("#reload").fadeOut("fast"); }, 500);
                }
                else if(data.indexOf("Errorx01") > -1)
                {
                  $("#validation").fadeIn();
                  $(".validation-body span:first").html(record_not_added);
                  $("#newest").html(additional);
                  $("#edit-table tr:last-child").find("td:last-child img").css({display: "none"});
                  $("#key input").val($("#edit-table tr").length - 1);
                }
              });
            });
          }
        }

        count++;
      });

      $(document).on("click", "#edit-table td[id!='key'][class!='delete']", function()
      {
        ifNotPermission(5, restrictedUpdate, update);

        var num = $("input[name='edit']").length;

        elem = $(this);

        function exec()
        {
          if(elem.html().indexOf("input") == -1 && elem.text().indexOf(restricted) == -1)
          {
            if(!(num < 1))
            {
              var content = $("input[name='edit']").val();
              $("input[name='edit']").parent().html(content);
            }

            var content = elem.text();
            elem.html('<input class="add-table-input col-18" name="edit" value="' + content + '">');

            var this_ = $("input[name='edit']");
            this_.trigger("focus");
            this_.val("");
            this_.val(content);
          }
        }

        function restrictedUpdate()
        {
          if(elem.attr("class") != "restricted")
          {
            exec();
          }
        }

        function update()
        {
          exec();
        }
      });

      $(document).on("mouseover", "#edit-table td[class='delete']", function()
      {
        $(this).css({cursor: "pointer"});
      });

      $(document).on("click", "img#del-record", function()
      {
        checkPermission(6, delete_, "401-restricted.php", true);

        var this_ = $(this).parents().eq(1);

        function delete_()
        {
          var update = this_.html();

          $(".background-loader").fadeIn();

          $.post("ajax/delete-table-records.php", { table: table, columns: columns, update: update },
          function(data)
          {
            $(".background-loader").fadeOut("fast", function()
            {
              if(data.indexOf("Succedx01") > -1)
              {
                this_.remove();
                $("input:disabled").val($("input:disabled").val()-1);

                $("#reload").fadeIn();
                $("#reload span").html(record_success);

                activity("alert", "TABLE_CONTENT");

                setTimeout(function(){ $("#reload").fadeOut("fast"); }, 500);
              }
              else if(data.indexOf("Errorx01") > -1)
              {
                $("#validation").fadeIn();
                $(".validation-body span:first").html(record_not_deleted);
              }
            });
          });
        }
      });
    });
  }
