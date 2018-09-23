  $("#button-send").click(function()
	{
    $(".background-loader").fadeIn();

		var avatar_high_ = $("input[name='avatar_high']").val();
    var avatar_low_ = $("input[name='avatar_low']").val();
    var max_size_ = $("input[name='max_size']").val();
    var create_miniature_ = $("input[name='create_miniature']").val();
    var file_ = $('#avatar-filename-2').prop('files')[0];

    var form_data = new FormData();
    form_data.append('avatar_high', avatar_high_);
    form_data.append('avatar_low', avatar_low_);
    form_data.append('max_size', max_size_);
    form_data.append('create_miniature', create_miniature_);
    form_data.append('file', file_);

		$.ajax({url: 'ajax/avatar-update.php', dataType: 'text', cache: false, contentType: false, processData: false, data: form_data, type: 'post', success: function(data)
  	{
      $(".background-loader").fadeOut("fast", function()
      {
        var title = conv2sp(readCookie("title"));
        var msg = conv2sp(readCookie("msg"));
        var close = conv2sp(readCookie("close"));

      	if(data.indexOf("Succedx01") > -1)
      	{
          activity("succed", "EDITED_PROFILE");

          if(detectMobile())
          {
            miniTopPopupIcon(1);
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
  	 }
    });
  });
