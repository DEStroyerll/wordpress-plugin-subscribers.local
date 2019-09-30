jQuery(document).ready(function ($) {
  $('#dn_form_subscriber').submit(function (event) {
    event.preventDefault();
    var data = $(this).serialize();

    $.ajax({
      type: "POST",
      url: dnajax.url,
      data: {
        formData: data,
        security: dnajax.nonce,
        action: 'dn_subscriber'
      },
      beforeSend: function () {
        $('#message').empty();
        $('#loader').fadeIn();
      },
      success: function (response) {
        // console.log(response);
        $('#loader').fadeOut(300, function () {
          $('#message').text(response);
          $('#dn_form_subscriber').find('input:not(#dn_submit)').val('');
        })
      },
      error: function () {
        $('#message').text("Error!");
      }
    });
  });
});