var video = document.getElementById('video'),
    canvas = document.getElementById('canvas'),
    context = canvas.getContext('2d'),
    photo = document.getElementById('photo');
$("#capture").click(function(){
  context.drawImage(video, 0, 0, 400, 300);
  photo.setAttribute('src', canvas.toDataURL('image/png'));
  $("#save").removeClass("disabled");
  return false;
});
$("#takephoto").click(function(){
  $("#photoblock").show();
  if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
    console.log("enumerateDevices() not supported.");
    return;
  }
  // Список источников видеосигнала
  var count = 0;
  navigator.mediaDevices.enumerateDevices().then(function(devices) {
    devices.forEach(function(device) {
      if (device.kind == 'videoinput'){
        $('#deviceId').append($("<option></option>").attr("value",device.deviceId).attr("selected", !count? true: false).text(device.label));
        count = count+1;
      }
      
      //console.log(device.kind + ": " + device.label + " id = " + device.deviceId);
    });
  }).catch(function(err) {
    console.log(err.name + ": " + err.message);
  });
  // Запустить трансляцию видео с первого добавленного источника
  
  navigator.mediaDevices.getUserMedia({ audio: false, video: { deviceId: $( "#deviceId option:selected" ).text()} })
    .then(function(mediaStream) {
    var video = document.querySelector('video');
    video.srcObject = mediaStream;
    video.onloadedmetadata = function(e) {
      video.play();
    };
  })
    .catch(function(err) { 
    console.log(err.name + ": " + err.message); 
  }); // always check for errors at the end.
  return  false;
});
$("#close").click(function(){
  $("#photoblock").hide();
  return  false;
});
$("#deviceId").change(function(){
  navigator.mediaDevices.getUserMedia({ audio: false, video: { deviceId: $( "#deviceId option:selected" ).val()} })
    .then(function(mediaStream) {
    var video = document.querySelector('video');
    video.srcObject = mediaStream;
    video.onloadedmetadata = function(e) {
      video.play();
    };
  })
    .catch(function(err) { console.log(err.name + ": " + err.message); }); // always check for errors at the end.
});
$("#save").click(function (){
   $.ajax({
      url:$("#ajaxpath").val(),
      type: "POST",
      dataType: "text",
      data: {
        id: $("#contactid").val(),
        photo: $("#photo").attr("src")
      },
      success: function (result) {
        $("#photoblock").hide();
        location.reload();
        console.log(result);
      },
      error: function () {
        console.log("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  return false;
});
