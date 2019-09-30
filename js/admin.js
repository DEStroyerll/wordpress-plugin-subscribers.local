jQuery(document).ready(function ($) {
  $('#btn').on('click', function () {
    var text = $.trim($('#dn_textarea').val());
    if (text == '') {
      alert("Введите тект для рассылки!");
      return;
    }
    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        text: text,
        action: "dn_subscriber_admin",
      },
      beforeSend: function () {
        $('#message').empty();
        $('#loader').fadeIn();
      },
      success: function (response) {
        $('#loader').fadeOut(300, function () {
          $('#message').text(response);
          // console.log(response);
        });
      },
      error: function () {
        $('#message').text("Error!");
      }
    });
  });
});