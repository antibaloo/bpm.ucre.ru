$(document).ready(function() {
	$("a.fancybox").fancybox();
});
$(document).ready(function() {
  $('#create').on('click', function () {
    var data = $('#object').serialize();
    $.ajax({
      type: "POST",
      url: "/ajax/create_ro.php",
      dataType: "text",
      data: data,
      success: function (html) {
        $("#result").html(html);
        location.reload(true);
      },
      error: function (html) {
        $("#result").html("Что-то пошло не так!");
      },
    });
  });
});