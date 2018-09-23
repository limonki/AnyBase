var i = 0;
var move = false;
var added = [];
var click_num = 0;
var pos_x;
var pos_y;
var clicked_pos = [2];
var additional = [2];
var class_ = [2];
var arr = [2];
var d_w;
var d_h;
var relation = 0;
var move_x = true;
var move_y = true;
var tr = [];
var relations = [];
var tables = [];
var relations_elem_id = [];
var a;
var b;
var relations_to_del = [];

var prefix = conv2sp(readCookie("prefix"));

function createRelationSVG(a, b)
{
  pos_x = $("#menu").width();
  pos_y = $("#menu-bar").height();

  d_w = $("#relations").width();
  d_h = $("#relations").height();

  var top_0 = a.offset().top - $("#menu-bar").height() + a.height() / 2;
  var left_0 = a.offset().left - $("#menu").width() + a.width() / 2;

  var top_1 = b.offset().top - $("#menu-bar").height() + b.height() / 2;
  var left_1 = b.offset().left - $("#menu").width() + b.width() / 2;

  additional[0] = a.parents().eq(2).width() / 2 + 15;
  class_[0] = a.parents().eq(3).attr("id");

  additional[1] = b.parents().eq(2).width() / 2 + 15;
  class_[1] = b.parents().eq(3).attr("id");

  if(left_0 > left_1) additional[0] = -additional[0];
  if(left_1 > left_0) additional[1] = -additional[1];

  var tab_1 = a.parents().eq(2).find("div[class='table-name']").text();
  var tab_2 = b.parents().eq(2).find("div[class='table-name']").text();

  var tabs = tab_1 + ":" + tab_2;

  var rel_1 = a.find("td").first().next().text() + "<->" + b.find("td").first().next().text();
  var rel_2 = b.find("td").first().next().text() + "<->" + a.find("td").first().next().text();
  if($.inArray(rel_1, relations) == -1 && $.inArray(rel_2, relations) == -1)
  {
    $("#relations").append('<div id="relation-' + relation + '"></div>');

    var this_ = $("div[id^='relation-" + relation + "']");

    if(left_0 > left_1) this_.append('<a href="return: false;" class="del" style="margin-left: -10px; position: absolute; left: ' + ($("#menu").width() + left_0+additional[0]) + '; top: ' + ($("#menu-bar").height() + top_0) + ';"><img src="images/icons/error32x32.png" style="width: 16px; height: 16px;"></a>');
    if(left_1 > left_0) this_.append('<a href="return: false;" class="del" style="margin-left: -10px; position: absolute; left: ' + ($("#menu").width() + left_0+additional[0]) + '; top: ' + ($("#menu-bar").height() + top_0) + ';"><img src="images/icons/error32x32.png" style="width: 16px; height: 16px;"></a>');
    this_.append('<svg width="' + d_w + '" height="' + d_h + '" style="position: absolute; z-index: -100; left: ' + pos_x + '; top: ' + pos_y + ';"><line class="' + class_[0] +'" x1="' + left_0 + '" y1="' + top_0 + '" x2="' + (left_0+additional[0]) + '" y2="' + top_0 + '" stroke="black"/></svg>');
    this_.append('<svg width="' + d_w + '" height="' + d_h + '" style="position: absolute; z-index: -100; left: ' + pos_x + '; top: ' + pos_y + ';"><line class="middle" x1="' + (left_0+additional[0]) + '" y1="' + top_0 + '" x2="' + (left_1+additional[1]) + '" y2="' + top_1 + '" stroke="black"/></svg>');
    this_.append('<svg width="' + d_w + '" height="' + d_h + '" style="position: absolute; z-index: -100; left: ' + pos_x + '; top: ' + pos_y + ';"><line class="' + class_[1] +'" x1="' + (left_1+additional[1]) + '" y1="' + top_1 + '" x2="' + left_1 + '" y2="' + top_1 + '" stroke="black"/></svg>');

    this_.css({visibility: "initial"});

    tr.push(a, b);
    relations.push(rel_1);
    tables.push(tabs);
    relations_to_del.push("false");

    relation++;
  }
}

  checkPermission(5, granted, "401-restricted.php", true);

  function granted()
  {
    var append;

    $.post("ajax/tables.php", { },
    function(data)
    {
      append = data.replace('Succedx01', '');
    });

    $(document).on("click", "td", function(event)
    {
      arr[click_num] = $(event.target).parent();

      if(click_num == 1)
      {
        if(arr[0].parents().eq(3).attr("id") !== arr[1].parents().eq(3).attr("id"))
        {
          if(arr[1].find("td:first-child").text() === "PRI")
          {
            var rel_1 = arr[0].find("td:last-child").text() + "<->" + arr[1].find("td:last-child").text();
            var rel_2 = arr[1].find("td:last-child").text() + "<->" + arr[0].find("td:last-child").text();
            var index = $.inArray(rel_1, relations);
            var this_ = $("#relation-" + index);
            if($.inArray(rel_1, relations) == -1 && $.inArray(rel_2, relations) == -1)
            {
              arr[0].find("td:first-child").html('<span style="display: none;">MUL</span><img style="margin: 0;" src="images/icons/for16x16.png">');
              activity("succed", "RELATION_ADDED");
              createRelationSVG(arr[0], arr[1]);
            }
            else if(this_.css("visibility") === "hidden")
            {
              this_.css({visibility: "initial"});
              relations_to_del[index] = "false";
              arr[0].find("td:first-child").html('<span style="display: none;">MUL</span><img style="margin: 0;" src="images/icons/for16x16.png">');
            }
            else alert("Relacja: " + rel_1 + " lub " + rel_2 + " już istnieje!");
          }
          else alert("Klucz obcy musi być kluczem głównym!");
        }
        click_num = 0;
      }
      else click_num++;
    });

    $(document).on("click", "div[class^=table-name]", function()
    {
      var elem = $(this).parents().eq(1);
      var table_id = elem.attr("id");

      move = !move;
      if(move)
      {
        $(document).on("mousemove", function(event)
        {
          var max_h = $("#relations").height() + $("#menu-bar").height() - elem.height();
          var min_h = $("#menu-bar").height();
          var max_x = $("#relations").width() + $("#menu").width() - elem.width();
          var min_x = $("#menu").width();

          $("div[id^='relation-']").each(function()
          {
            $(this).css({visibility: "hidden"});
          });

          elem.css({left: event.pageX, top: event.pageY});

          if(event.pageY > max_h)
          {
            elem.css({top: max_h});
            move_y = false;
          }
          else if(event.pageY < min_h)
          {
            elem.css({top: min_h});
            move_y = false;
          }
          else move_y = true;
          if(event.pageX > max_x)
          {
            elem.css({left: max_x});
            move_x = false;
          }
          else if(event.pageX < min_x)
          {
            elem.css({left: min_x});
            move_x = false;
          }
          else move_x = true;
        });
      }
      else
      {
        $(document).off("mousemove");

        var i = 0;
        var j = 0;
        $.each(tr, function(index)
        {
          if(index % 2 == 0)
          {
            var this_ = $("div[id^='relation-" + j + "']");

            clicked_pos[0] = tr[i].offset();
            clicked_pos[0].top = tr[i].offset().top - $("#menu-bar").height() + tr[i].height() / 2;
            clicked_pos[0].left = tr[i].offset().left - $("#menu").width() + tr[i].width() / 2;

            clicked_pos[1] = tr[i].offset();
            clicked_pos[1].top = tr[i+1].offset().top - $("#menu-bar").height() + tr[i+1].height() / 2;
            clicked_pos[1].left = tr[i+1].offset().left - $("#menu").width() + tr[i+1].width() / 2;

            additional[0] = tr[i].parents().eq(2).width() / 2 + 15;
            class_[0] = tr[i].parents().eq(4).attr("id");

            additional[1] = tr[i+1].parents().eq(2).width() / 2 + 15;
            class_[1] = tr[i+1].parents().eq(4).attr("id");

            if(clicked_pos[0].left > clicked_pos[1].left) additional[0] = -additional[0];
            if(clicked_pos[1].left > clicked_pos[0].left) additional[1] = -additional[1];

            if(clicked_pos[0].left > clicked_pos[1].left) this_.html('<a href="return: false;" class="del" style="margin-left: -10px; position: absolute; left: ' + ($("#menu").width() + clicked_pos[0].left+additional[0]) + '; top: ' + ($("#menu-bar").height() + clicked_pos[0].top) + ';"><img src="images/icons/error32x32.png" style="width: 16px; height: 16px;"></a>');
            if(clicked_pos[1].left > clicked_pos[0].left) this_.html('<a href="return: false;" class="del" style="margin-left: -10px; position: absolute; left: ' + ($("#menu").width() + clicked_pos[0].left+additional[0]) + '; top: ' + ($("#menu-bar").height() + clicked_pos[0].top) + ';"><img src="images/icons/error32x32.png" style="width: 16px; height: 16px;"></a>');
            this_.append('<svg width="' + d_w + '" height="' + d_h + '" style="position: absolute; z-index: -100; left: ' + pos_x + '; top: ' + pos_y + ';"><line class="' + class_[0] +'" x1="' + clicked_pos[0].left + '" y1="' + clicked_pos[0].top + '" x2="' + (clicked_pos[0].left+additional[0]) + '" y2="' + clicked_pos[0].top + '" stroke="black"/></svg>');
            this_.append('<svg width="' + d_w + '" height="' + d_h + '" style="position: absolute; z-index: -100; left: ' + pos_x + '; top: ' + pos_y + ';"><line class="middle" x1="' + (clicked_pos[0].left+additional[0]) + '" y1="' + clicked_pos[0].top + '" x2="' + (clicked_pos[1].left+additional[1]) + '" y2="' + clicked_pos[1].top + '" stroke="black"/></svg>');
            this_.append('<svg width="' + d_w + '" height="' + d_h + '" style="position: absolute; z-index: -100; left: ' + pos_x + '; top: ' + pos_y + ';"><line class="' + class_[1] +'" x1="' + (clicked_pos[1].left+additional[1]) + '" y1="' + clicked_pos[1].top + '" x2="' + clicked_pos[1].left + '" y2="' + clicked_pos[1].top + '" stroke="black"/></svg>');

            if(relations_to_del[j] != "true") this_.css({visibility: "initial"});

            i+=2;
          }
          else j++;
        });
      }
    });

    $(".modal-info-close.button").on("click", function(event)
    {
      var table;
      var selected_ = $("select option").filter(":selected").text();

      if($.inArray(selected_, added) == -1)
      {
        $(".background-loader").fadeIn();
        
        $.post("ajax/table.php", { selected: selected_ },
        function(data)
        {
          table = data.replace('Succedx01', '').split('FOREIGN KEY')[0];
          var foreign_keys = jQuery.parseJSON(data.match(/FOREIGN KEY:(.*?)END+/i)[1]);
          var references = jQuery.parseJSON(data.match(/REFERENCES:(.*?)END+/i)[1]);
          $("#relations").append("<div id='table-" + i + "'>" + table + "</div>");
          var s_w = $("#relations").find("#table-" + i).width() / 2;
          var s_h = $("#menu-bar").height() + ($("#relations").outerHeight() / 2 - $("#relations").find("#table-" + i).find("table").outerHeight() / 2);
          $("#table-" + i).css({position: "absolute", left: s_w, top: s_h});
          $.each(references[2], function(i)
          {
            a = $("td[id='f-" + foreign_keys[i] + "']").parent();
            b = $("#" + references[1][i].replace(prefix, '') + " td[id='p-" + references[2][i] + "']").parent();
            relations_elem_id.push(references[1][i], "f-" + foreign_keys[i], "p-" + references[2][i]);
            if(a.length != 0 && b.length != 0) createRelationSVG(a, b);
          });
          var index_ = 0;
          $.each(relations_elem_id, function()
          {
            a = $("td[id='" + relations_elem_id[index_+1] + "']").parent();
            b = $("#" + relations_elem_id[index_] + " td[id='" + relations_elem_id[index_+2] + "']").parent();
            if(a.length != 0 && b.length != 0) createRelationSVG(a, b);
            index_+=3;
          });
          added.push(selected_);
          i++;
          $(".background-loader").fadeOut("fast");
        });
      }
    });

    $("#button-add").on("click", function()
    {
      infoPopupShow("Select table to add:", append, "Select", -1);
      popupCalcNewPosition($("#info"));
      infoPopupClose($(".modal-info-close"), false);
    });

    $(document).on("click", ".del", function()
    {
      checkPermission(6, granted, "401-restricted.php", true);

      var this_ = $(this);

      function granted()
      {
        var index = this_.parent().attr("id").split("-")[1];
        relations_to_del[index] = "true";
        var elem = tr[index*2];
        elem.find("td:first-child").html("");
        this_.parent().css({visibility: "hidden"});
        activity("alert", "RELATION_DELETED");
      }
    });

    $("#button-save").on("click", function()
    {
      $(".background-loader").fadeIn();

      $.post("ajax/relations.php", { relations: relations, tables: tables, relations_to_del: relations_to_del },
      function(data)
      {
        var status;
        if(data == "") status = 0;
        else
        {
          if(data.replace(/<(.*?)>/ig, "").indexOf("relations") > 0) data = conv2sp(readCookie("msg-err"));
          status = 1;
        }

        var title_succ = conv2sp(readCookie("title-succ"));
        var msg_succ = conv2sp(readCookie("msg-succ"));
        var title_err = conv2sp(readCookie("title-err"));
        var close = conv2sp(readCookie("close"));
        var msg;

        $(".background-loader").fadeOut("fast", function()
        {
          if(detectMobile())
          {
            if(status == 0)
            {
              miniTopPopupIcon(3);
              msg = msg_succ;
            }
            else if(status == 1)
            {
              miniTopPopupIcon(5);
              msg = data;
            }
            miniTopPopupShow(msg, $(window).scrollTop());

            miniTopPopupClose($("#mini-top-popup #close"), $("#mini-top-popup"), "-100px", 600, false);
          }
          else
          {
            if(status == 0) mainPopupShow(title_succ, msg_succ, close, 0);
            else if(status == 1) mainPopupShow(title_err, data, close, 1);
            popupPosition($("#popup"), "", "");

            mainPopupClose($(" .modal-close"), false);
          }
        });
      });
    });
  }
