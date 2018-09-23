var w_w = $(window).width();
var w_h = $(window).height();

function detectMobile()
{
  if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)))
  {
    return true;
  }
  return false;
}

function redirect(param)
{
  window.location = param;
}

function checkPermission(permission, func, param, exec)
{
  $.post("ajax/permission.php", { },
	function(data)
	{
    if(parseInt(data) >= permission) func();
    else if(exec) redirect(param);
	});
}

function ifNotPermission(permission, func, func2)
{
  $.post("ajax/permission.php", { },
	function(data)
	{
    if(parseInt(data) < permission) func();
    else func2();
	});
}

function checkArray(array, val)
{
  for(var i = 0; i < array.length; i++)
  {
    if(array[i] == "" || array[i] == val) return true;
  }

  return false;
}

function conv2sp(msg)
{
  return msg.replace(/-/g, ' ');
}

var getUrlParameter = function getUrlParameter(sParam)
{
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for(i = 0; i < sURLVariables.length; i++)
    {
      sParameterName = sURLVariables[i].split('=');
      if(sParameterName[0] === sParam) return sParameterName[1] === undefined ? true : sParameterName[1];
    }
};

function activity(icon, key)
{
  $.post("ajax/activity.php", { icon: icon, key: key }, function(data) {});
}

function slideFromTop(element, from, to, fade, time)
{
  if(fade != 2) element.css({display: "block", opacity: "0.0", top: from});
  else element.css({display: "block", opacity: "1.0", top: from});

  element.animate({
      position: "absolute",
			top: to,
			opacity: "1.0"
		}, time);
}

function slideToTop(element, to, time)
{
  element.animate({
      position: "absolute",
			top: to,
			opacity: "0.0"
		}, time);
}

function popupCalcNewPosition(element)
{
  var w_w = $(window).width();
  var w_h = $(window).height();

  var left = (w_w - element.width())/2;
  var top = (w_h - element.height())/2;

  element.css({position: "absolute", left: left, top: top});
}

function popupPosition(element, left, top)
{
  if(!(left || top))
  {
    left = (w_w - element.width())/2;
    top = (w_h - element.height())/2;

    element.css({position: "absolute", left: left, top: top});
  }
  else if(!(left))
  {
  	element.css({position: "absolute", top: top});
  }
  else if(!(top))
  {
  	element.css({position: "absolute", left: left});
  }
  else
  {
  	element.css({position: "absolute", left: left, top: top});
  }
}

function popupByElemHorizontalCenter(elementBy, popup)
{
  var left_ = elementBy.width()/2 - popup.width()/2;

  popup.css({position: "absolute", left: left_});
}

function popupByElemVerticalCenter(elementBy, popup)
{
  var top_ = elementBy.height()/2 - popup.height()/2;

  popup.css({position: "absolute", top: top_});
}

var stop_timer_1;

function miniTopPopupClose(element, elementSilde, to, time, refresh)
{
  element.click(function()
	{
		slideToTop(elementSilde, to, time);
    clearInterval(stop_timer_1);
    if(refresh) window.location.reload();
	});
}

function miniTopPopupShow(msg, top)
{
  clearInterval(stop_timer_1);
  $("#mini-top-popup span").text(msg);
  if(!detectMobile())
  {
    $("#mini-top-popup").css({width: msg.length * 10 + $("#mini-top-popup #icon").outerWidth()});
    popupByElemHorizontalCenter($("body"), $("#mini-top-popup"));
  }
  eraseCookie("msg");
  eraseCookie("reloaded");
  slideFromTop($("#mini-top-popup"), "-100px", top, 0.0, 600);
  stop_timer_1 = setTimeout(function() {
    slideToTop($("#mini-top-popup"), "-100px", 600);
  }, 5000);
}

function miniTopPopupIcon(icon)
{
  $("#mini-top-popup").removeClass();
  $("#mini-top-popup #icon").removeClass();
  if(icon == 0)
  {
    $("#mini-top-popup").addClass("orange");
    $("#mini-top-popup #icon").addClass("session-expired-icon");
  }
  else if(icon == 1)
  {
    $("#mini-top-popup").addClass("green");
    $("#mini-top-popup #icon").addClass("session-logout-icon");
  }
  else if(icon == 2)
  {
    $("#mini-top-popup").addClass("orange");
    $("#mini-top-popup #icon").addClass("session-unauthorized-icon");
  }
  else if(icon == 3)
  {
    $("#mini-top-popup").addClass("green");
    $("#mini-top-popup #icon").addClass("succed-icon");
  }
  else if(icon == 4)
  {
    $("#mini-top-popup").addClass("orange");
    $("#mini-top-popup #icon").addClass("alert-icon");
  }
  else if(icon == 5)
  {
    $("#mini-top-popup").addClass("red");
    $("#mini-top-popup #icon").addClass("error-icon");
  }
  else if(icon == 6)
  {
    $("#mini-top-popup").addClass("blue");
    $("#mini-top-popup #icon").addClass("info-icon");
  }
}

function mainPopupClose(element, refresh)
{
  element.click(function()
	{
		$(".background-modal-box").fadeOut(300, function()
    {
      $("header #icon").removeClass();
      if(refresh) window.location.reload();
    });
    $(".blur").removeClass("blur-filter");
    $("#main-container").removeClass("blur-filter");
	});
}

function mainPopupShow(title, msg, button, icon)
{
  $("header h3").text(title);
  $(".modal-body p").text(msg);
  $(".modal-box footer .button").text(button);
  if(icon == 0) $("header #icon").addClass("succed-icon32x32");
  else if(icon == 1) $("header #icon").addClass("error-icon32x32");
  else if(icon == 2) $("header #icon").addClass("alert-icon32x32");
  else if(icon == 3) $("header #icon").addClass("info-icon32x32");
  if(icon != -1)
  {
    $("header #icon").css({width: "32px", height: "32px", marginRight: "10px"});
    $("header h3").css({display: "inline-block", verticalAlign: "middle"});
  }
  $(".background-modal-box").fadeIn(300, function()
  {
    $(".blur").addClass('blur-filter');
    $("#main-container").addClass('blur-filter');
  });
}

function infoPopupClose(element, refresh)
{
  element.click(function()
	{
		$(".background-modal-info").fadeOut(300, function()
    {
      $("header #info-icon").removeClass();
      if(refresh) window.location.reload();
    });
    $(".blur").removeClass("blur-filter");
    $("#main-container").removeClass("blur-filter");
	});
}

function infoPopupForcedClose()
{
  $(".background-modal-info").fadeOut(300, function()
  {
    $("header #info-icon").removeClass();
    $(".blur").removeClass("blur-filter");
    $("#main-container").removeClass("blur-filter");
  });
}

function infoPopupShow(title, msg, button, icon)
{
  $(".modal-info p").text(title);
  $(".modal-info-body p").html(msg);
  if(button === "") $(".modal-info footer").hide();
  else $(".modal-info footer .button").text(button);
  $("header #info-icon").removeClass();
  if(icon == 0) $("header #info-icon").addClass("info-icon110x110");
  if(icon == 1) $("header #info-icon").addClass("loader-icon110x110");
  if(icon == 2) $("header #info-icon").addClass("succed-icon110x110");
  if(icon == 3) $("header #info-icon").addClass("error-icon110x110");
  if(icon != -1)
  {
    $("header #info-icon").css({width: "110px", height: "110px"});
  }
  else $("header #info-icon").css({width: "0px", height: "0px"});
  $(".background-modal-info").fadeIn(300, function()
  {
    $(".blur").addClass('blur-filter');
    $("#main-container").addClass('blur-filter');
  });
}

function createCookie(name, value, days)
{
    var expires;

    if(days)
    {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else expires = "";
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/;";
}

function readCookie(name)
{
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++)
    {
        var c = ca[i];
        while(c.charAt(0) === ' ') c = c.substring(1, c.length);
        if(c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name)
{
    document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/;';
}
