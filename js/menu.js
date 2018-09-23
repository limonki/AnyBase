function menuElemenCenterVerticaly()
{
	var top_ = ($('#menu #element').parent().height())/2;
	$('#menu #element').css({marginTop: top_});
}

function adminMainContainer()
{
  menu_width = $('#menu-container').width();
  $('#main-container').css({paddingLeft: menu_width});
}

function adminMenu()
{
  var menu_width = $('#menu-container').width();
  var menu_icon_width = $('#menu img').width();
  var height = $('#menu li span').height();
  $('#menu li span').css({width: menu_width});
  $('#menu li li span').css({width: menu_width});
  $("ul[id='menu'] li ul li span").css({height: $("ul[id='menu'] li span").height() + 20});

  adminMainContainer();
}

function menuFolded()
{
	if($('#menu ul').hasClass('folded')) return true;
	return false;
}

function menuFold()
{
  menuFoldIconRotate();
  $('#menu ul').addClass('folded');
  if($('#menu-container').width() < 100) $('#menu ul').removeClass();

	$("ul[id='menu'] li span").animate({width: 'toggle'}, 0);
	$("ul[id='menu'] li ul li span").animate({width: 'toggle'}, 0);
	adminMainContainer();
	scrollTop = 0;
	$('#menu-container').css({top: -scrollTop});
}

function menuFoldIconRotate()
{
		if($('#menu #fold img').hasClass('rotate')) $('#menu #fold img').removeClass();
		else $('#menu #fold img').addClass('rotate');
}

	menuElemenCenterVerticaly();
	adminMenu();
	if(detectMobile())
	{
		menuFold();
		$('#main-container').css({paddingLeft: '52px'});
		$('#menu #fold').css({display: 'none'});
		$('#menu li a').click(function(event)
		{
			if($(event.target).parent().parent().has('li').length)
			{
				return false;
			}
		});
	}
	else
	{
		if(readCookie('menu-folded') != null) menuFold();
	}

	$('#menu #fold').click(function()
	{
		if(readCookie('menu-folded') == null) createCookie('menu-folded', 'true', 1);
		else eraseCookie('menu-folded');

		menuFold();
	});

/*var scrollTop = 0;

$('#menu-container').on('mousewheel DOMMouseScroll', function(e)
{
  var e0 = e.originalEvent,
      delta = e0.wheelDelta || -e0.detail;

  scrollTop += (delta < 0 ? 1 : -1) * 30;
	var diff = $('#menu-container').height() - $(window).height();

	if(scrollTop < 0) scrollTop = 0;
	if(scrollTop >= 0) $('#menu-container').css({top: -scrollTop});

	if(scrollTop > diff) scrollTop = diff;
	if(scrollTop >= diff) $('#menu-container').css({top: -diff});

  e.preventDefault();
});

var last_y;

function checkScroll()
{
	if(scrollTop < 0) scrollTop = 0;
	if(scrollTop >= 0) $('#menu-container').css({top: -scrollTop});

	var diff = $('#menu-container').height() - $(window).height();

	if(scrollTop > diff) scrollTop = diff;
	if(scrollTop >= diff) $('#menu-container').css({top: -diff});
}

$(window).scroll(function()
{
  checkScroll();
});*/

$('#menu-container').on('touchmove', function(e)
{
  var current_y = e.originalEvent.touches[0].clientY;
  if(current_y > last_y) scrollTop -= 8;
	else if(current_y < last_y) scrollTop += 8;

	checkScroll();

	last_y = current_y;

	e.preventDefault();
});
