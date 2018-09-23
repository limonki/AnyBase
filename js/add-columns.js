var appended = 0;
var append = $(".take-column").html();

function add(num)
{
  for(var i = 0; i < num; i++)
  {
    $(".append-columns").append('<div id="appended-' + appended + '">' + append + '</div>').find(".delete-column").css("visibility", "visible");
    appended++;
  }
}

  $("#button-add").click(function()
	{
		var num = $("input[name='num']").val();

    if(num != "")
    {
      $(".background-loader").fadeIn();

      if($(".append-columns").html().length > 0)
      {
        $(".append-columns").fadeOut(500, function() { add(num); $(".background-loader").fadeOut(); });
      }
      else $(".append-columns").fadeOut(0, function() { add(num); $(".background-loader").fadeOut(); });

      $(".append-columns").fadeIn(500);

      $("input[name='num']").val("");
    }
	});
