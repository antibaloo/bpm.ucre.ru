$(document).ready(function() {
  $('#sync').on('click', function () {
    var data = $('#avito').serialize();
    $.ajax({
      type: "POST",
      url: "/ajax/avito_sync.php",
      dataType: "text",
      data: data,
      success: function (html) {
        $("#result").html(html);
        //location.reload(true);
      },
      error: function (html) {
        $("#result").html("Zhopa!");
      },
    });
  });
});