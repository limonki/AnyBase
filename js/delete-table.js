  checkPermission(7, granted, "401-restricted.php", true);

  function granted()
  {
    $(".rmv-link").click(function()
  	{
      var elem = $(this);
  		var table_name_ = elem.data("name");

      var table = $(".rmv-link").closest("table");

      $(".background-loader").fadeIn();

      table.fadeOut(500, function()
      {
        $(".alert.confirm").fadeIn(500, function() {$(".background-loader").fadeOut("fast");});
      });

      $("#discard").click(function()
    	{
        $(".background-loader").fadeIn();

        $(".alert.confirm").fadeOut(500, function()
        {
          table.fadeIn(500, function()
          {
            $(".background-loader").fadeOut("fast");
          });
        });
      });

      $("#confirm").click(function()
    	{
        $(".background-loader").fadeIn();

        $(".alert.confirm").fadeOut(500);

        $.post("ajax/delete-table.php", { table_name: table_name_ },
          function(data)
          {
            elem.parent().remove();

            $(".background-loader").fadeOut("fast");

            if(data.indexOf("Succedx01") > -1)
            {
              $(".alert.success").fadeIn(500);

              activity("alert", "TABLE_DELETED");

              setTimeout(function() {
                $(".alert.success").fadeOut(500, function()
                {
                  table.fadeIn(500);
                });
              }, 2000);
            }
            else if(data.indexOf("Errorx01") > -1)
            {
              $(".alert.error").fadeIn(500);

              setTimeout(function() {
                $(".alert.error").fadeOut(500, function()
                {
                  table.fadeIn(500);
                });
              }, 2000);
            }
          });
        });
  	});
  }
