var start_w = $("#relations").width();
var start_h = $("#relations").height();

$(".background-loader").hide(0);

$(window).resize(function()
{
	//popupPosition($("#popup"), "", "");
	popupCalcNewPosition($("#popup"));
	popupCalcNewPosition($("#info"));
	popupByElemHorizontalCenter($("body"), $("#mini-top-popup"));
	popupByElemHorizontalCenter($("body"), $("#popup"));
	popupByElemHorizontalCenter($("body"), $("#info"));

	var top = ($(document).outerHeight()/2) - ($("#main").outerHeight()/2);
  var left = ($(document).outerWidth()/2) - ($("#main").outerWidth()/2);

  $("#main").css({top: top, left: left});

	$("#user-actions-drop-down").css({left: $(document).width()-$("#user-actions-drop-down").outerWidth(true), top: $("#menu-bar").outerHeight(true)});

	var d_w = $("#relations").width();
	var d_h = $("#relations").height();

	var scale_x = d_w / start_w;
	var scale_y = d_h / start_h;

	$("div[id^='table-']").each(function()
	{
		var pos_x = parseInt($(this).css("left"));
		var pos_y = parseInt($(this).css("top"));

		pos_x = pos_x*scale_x;
		pos_y = pos_y*scale_y;

		$(this).css({left: pos_x, top: pos_y});
		$(this).find("div[class='table-name']").trigger("click");
	});

	start_w = $("#relations").width();
	start_h = $("#relations").height();

	$("svg").each(function()
	{
		$(this).css({width: d_w, height: d_h});
	});
});

$(window).on("orientationchange", function()
{
	$("#user-actions-drop-down").css({left: 0, top: $("#menu-bar").outerHeight(true)});
});

$(window).on('load', function()
{
	$("#user-actions-drop-down").css({left: $(document).width()-$("#user-actions-drop-down").outerWidth(true), top: $("#menu-bar").outerHeight(true)});
});

$(window).scroll(function()
{
	if($(window).scrollTop() == 0) popupPosition($("#mini-top-popup"), "0", "0");
	else popupPosition($("#mini-top-popup"), 0, $(window).scrollTop());
});
