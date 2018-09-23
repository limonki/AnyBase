$(window).on('load', function()
{
	ifNotPermission(3, notGranted, "");

	function notGranted()
	{
		$(".close").click(function()
		{
			$("#validation").fadeOut();
		});

		var permission_guest = conv2sp(readCookie("permission-guest"));

		$("#validation").fadeIn();
		$(".validation-body span:first").html(permission_guest);

		setTimeout(function() { $("#validation").fadeOut(); }, 5000);
	}
});
