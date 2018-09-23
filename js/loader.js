$(window).on("load", function()
{
	var refreshed = readCookie('refreshed');

	if(refreshed > 1)
	{
		$("#login-form").css({position: "relative", opacity: "1.0"});

		if(window.innerHeight > window.innerWidth) $("#login-form").css({top: "50px"});
		else
		{
			if(detectMobile()) $("#login-form").css({top: "-50px"});
			else $("#login-form").css({top: "50px"});
		}

		$(".loader").fadeOut("slow");
	}
	else
	{
		$(".loader").fadeOut("slow", function()
		{
			if(window.innerHeight > window.innerWidth)
			{
				$( "#login-form").animate({
			    opacity: 1.0,
			    top: "50px"
			  }, 500);
			}
			else
			{
				if(detectMobile())
				{
					$( "#login-form").animate({
				    opacity: 1.0,
				    top: "-50px"
				  }, 500);
				}
				else
				{
					$( "#login-form").animate({
				    opacity: 1.0,
				    top: "50px"
				  }, 500);
				}
			}
		});
	}
});

$(window).on("orientationchange", function(event)
{
	if(window.innerHeight > window.innerWidth)
	{
		$("#login-form").css({top: "-50px"});
	}
	else
	{
		$( "#login-form").css({top: "50px"});
	}
});
