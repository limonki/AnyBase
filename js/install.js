var step = 0;
var all = $(".all, .submit");
var loader = $(".loader");

function position()
{
  var left = ($(document).outerWidth()/2) - ($("#main").outerWidth()/2);
  var top = ($(document).outerHeight()/2) - ($("#main").outerHeight()/2);

  $("#main").css({top: top, left: left});
}

function validate()
{
  var flag = true;

  $("input:visible").each(function()
  {
    if($(this).val() == "")
    {
      $("#validation").fadeIn();
      $(".validation-body span:first").html(fill_empty_fields);
      flag = false;
      $(this).addClass("error");
    }
    else $(this).removeClass("error");
  });

  return flag;
}

  all.hide();
  loader.hide();
  position();

  var installation_msg = conv2sp(readCookie("installation-msg"));
  var installation_succ = conv2sp(readCookie("installation-succ"));
  var installation_err = conv2sp(readCookie("installation-err"));
  var fill_empty_fields = conv2sp(readCookie("fill-empty-fields"));
  var db_server_help = conv2sp(readCookie("db-server-help"));
  var db_username_help = conv2sp(readCookie("db-username-help"));
  var db_password_help = conv2sp(readCookie("db-password-help"));
  var db_database_help = conv2sp(readCookie("db-database-help"));
  var db_database_prfx_help = conv2sp(readCookie("db-database-prfx-help"));
  var session_expires_help = conv2sp(readCookie("session-expires-help"));
  var support_contact_email_help = conv2sp(readCookie("support-contact-email-help"));

  var db_server = $(".db_server").parent();
  var db_username = $(".db_username").parent();
  var db_password = $(".db_password").parent();
  var db_database = $(".db_database").parent();
  var db_database_prfx = $(".db_database_prfx").parent();
  var session_expires = $(".session_expires").parent();
  var support_contact_email = $(".support_contact_email").parent();

  $(".close").click(function()
  {
    $("#validation").fadeOut("fast");
    $("#reload").fadeOut("fast");
  });

  $(".help").hover(function()
  {
    var pos = $(this).offset();
    $(".help-box").css({top: pos.top+32, left: pos.left+24});
    $(".help-box").show();
  }, function()
  {
    $(".help-box").hide();
  });

  $(".db_server").hover(function() { $(".help-box").text(db_server_help); });
  $(".db_username").hover(function() { $(".help-box").text(db_username_help); });
  $(".db_password").hover(function() { $(".help-box").text(db_password_help); });
  $(".db_database").hover(function() { $(".help-box").text(db_database_help); });
  $(".db_database_prfx").hover(function() { $(".help-box").text(db_database_prfx_help); });
  $(".session_expires").hover(function() { $(".help-box").text(session_expires_help); });
  $(".support_contact_email").hover(function() { $(".help-box").text(support_contact_email_help); });

  function steps()
  {
    if(step == 1)
    {
      $("#button-install").hide();
      $(".start").fadeOut(function()
      {
        all.fadeOut(function()
        {
          $("#button-back").hide();
          db_server.show();
          db_username.hide();
          db_password.hide();
          db_database.show();
          db_database_prfx.show();
          session_expires.hide();
          support_contact_email.hide();
          all.fadeIn();
          position();
        });
      });
    }
    else if(step == 2)
    {
      all.fadeOut(function()
      {
        $("#button-install").hide();
        $("#button-next").show();
        $("#button-back").show();
        db_server.hide();
        db_database.hide();
        db_database_prfx.hide();
        session_expires.hide();
        support_contact_email.hide();
        db_username.show();
        db_password.show();
        all.fadeIn();
        position();
      });
    }
    else if(step == 3)
    {
      all.fadeOut(function()
      {
        $("#button-next").hide();
        $("#button-install").show();
        db_server.hide();
        db_username.hide();
        db_password.hide();
        db_database.hide();
        db_database_prfx.hide();
        session_expires.show();
        support_contact_email.show();
        all.fadeIn();
        position();
      });
    }
    else if(step == 4)
    {
      all.fadeOut(function()
      {
        $("#button-next").hide();
        $("#button-install").hide();
        $("#button-back").hide();
        $("h2.center").text(installation_msg);
        loader.show();
        db_server.hide();
        db_username.hide();
        db_password.hide();
        db_database.hide();
        db_database_prfx.hide();
        session_expires.hide();
        support_contact_email.hide();
        all.fadeIn();
        position();
      });
    }
  }

  $("#button-next, #button-start, #button-install").click(function()
	{
    var flag = validate();
    if(flag)
    {
      step++;
      steps();
    }
	});

  $("#button-install").click(function()
  {
    window.onbeforeunload = function(event)
    {
      return "Confirm refresh";
    };

    var db_server = $("input[name='db_server']").val();
    var db_username = $("input[name='db_username']").val();
    var db_password = $("input[name='db_password']").val();
    var db_database = $("input[name='db_database']").val();
    var db_database_prfx = $("input[name='db_database_prfx']").val();
    var session_expires = $("input[name='session_expires']").val();
    var support_contact_email = $("input[name='support_contact_email']").val();

    $.post("ajax/install.php", { db_server: db_server, db_username: db_username, db_password: db_password, db_database: db_database, db_database_prfx: db_database_prfx, session_expires: session_expires, support_contact_email: support_contact_email },
    function(data)
    {
      var flag = true;

      if(data.indexOf("Succedx01") > -1) flag = true; // skonfigurowano
      if(data.indexOf("Succedx02") > -1) flag = true; // stworzono tabele danych
      if(data.indexOf("Errorx01") > -1) flag = false; // nie skonfigurowano
      if(data.indexOf("Errorx02") > -1) flag = false; // nie stworzono tabeli danych
      if(data.indexOf("Errorx03") > -1) flag = false; // nie stworzono linku rejestracji
      //if(data.indexOf("Errorx04") > -1) flag = false; // nie stworzono linku rejestracji

      if(flag)
      {
        var link = data.split("Succedx01")[1].split("Succedx02")[0];
        //alert(link);
        //activity("info", "ANYBASE_INSTALLED");

        $("h2.center").text(installation_succ);
        $(".all").after('<div class="register">Register now as administrator or copy link to register later (remember to save this link - otherwsie it will be impossible to register as administrator without building link manually using informations from database):</div><br><div class="center"><a href="' + link + '">Register now</a></div>')

        loader.fadeOut("slow", function()
        {
          setTimeout(function()
          {
            loader.find("img").attr("src", "images/icons/succed110x110.png");
            loader.fadeIn("slow");
          }, 500);
        });
      }
      else
      {
        $("h2.center").text(installation_err);

        $(".all").after('<div class="register">Some data must be invalid. Verify informations recived from database administrator. If problem will appear later contact with support.</div>')

        loader.fadeOut("slow", function()
        {
          setTimeout(function()
          {
            loader.find("img").attr("src", "images/icons/error110x110.png");
            loader.fadeIn("slow");
          }, 500)
        });
      }
    });
  });

  $("#button-back").click(function()
	{
    step--;
    if(step < 1) step = 1;
    else if(step > 4) step = 4;
    else steps();
  });

  $("#button-index").click(function()
	{
    window.location = "index.php";
  });
