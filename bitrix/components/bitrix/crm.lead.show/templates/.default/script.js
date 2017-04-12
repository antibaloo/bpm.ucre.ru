$(document).ready(function() {
		$("a.fancybox").fancybox();
	});
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
				if (html.indexOf("Ошибка:") ==-1) location.reload(true);
      },
      error: function (html) {
        $("#result").html("Что-то пошло не так!");
      },
    });
  });
});