  var session_expired = getUrlParameter("session_expired");
	var session_logout = getUrlParameter("session_logout");
	var session_unauthorized = getUrlParameter("session_unauthorized");
	var session_key_not_string = getUrlParameter("session_key_not_string");

	miniTopPopupClose($("#mini-top-popup #close"), $("#mini-top-popup"), "-100px", 600);

  var msg = readCookie('msg');
  if(msg) msg = conv2sp(msg);

  if(session_expired) miniTopPopupIcon(0);
  else if(session_logout) miniTopPopupIcon(1);
  else if(session_unauthorized) miniTopPopupIcon(2);
  else if(session_key_not_string) miniTopPopupIcon(2);

  if(msg && (session_expired || session_logout || session_unauthorized || session_key_not_string)) miniTopPopupShow(msg, $(window).scrollTop());
