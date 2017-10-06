<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Фотографирование клиентов");
?>
<style>
  .booth {
    width: 400px;
    background: #ccc;
    border: 10px solid #ddd;
    margin: 0 auto;
  }
  .booth-capture-button {
    display: block;
    margin: 10px 0;
    padding: 10px 20px;
    background: cornflowerblue;
    color: #fff;
    text-align: center;
    text-decoration: none;
  }
  #canvas {
    display: none;
  }
</style>
<div class="booth">
  <select id="deviceId">    
  </select>
</div>
<div class="booth">
  <video id="video" width="400" height="300" autoplay></video>
  <a href="#" id="capture" class="booth-capture-button">Сфотографировать</a>
  <canvas id="canvas" width="400" height="300"></canvas>
  <img src="https://goo.gl/qgUfzX" id="photo" alt="Ваша фотография">
</div>
<script>
  $("#deviceId").change(function(){
    // Запустить трансляцию видео с выбранного источника
    var video = document.getElementById('video'),
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        photo = document.getElementById('photo');
    navigator.mediaDevices.getUserMedia({ audio: false, video: { deviceId: $( "#deviceId option:selected" ).text()} })
      .then(function(mediaStream) {
      var video = document.querySelector('video');
      video.srcObject = mediaStream;
      video.onloadedmetadata = function(e) {
        video.play();
      };
    })
      .catch(function(err) { console.log(err.name + ": " + err.message); }); // always check for errors at the end.
  });
  $(window).load(function () {
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
        
        console.log(device.kind + ": " + device.label + " id = " + device.deviceId);
      });
    }).catch(function(err) {
      console.log(err.name + ": " + err.message);
    });
    // Запустить трансляцию видео с первого добавленного источника
    var video = document.getElementById('video'),
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        photo = document.getElementById('photo');
    navigator.mediaDevices.getUserMedia({ audio: false, video: { deviceId: $( "#deviceId option:selected" ).text()} })
      .then(function(mediaStream) {
        var video = document.querySelector('video');
        video.srcObject = mediaStream;
        video.onloadedmetadata = function(e) {
          video.play();
        };
      })
      .catch(function(err) { console.log(err.name + ": " + err.message); }); // always check for errors at the end.
    document.getElementById('capture').addEventListener('click', function() {
      context.drawImage(video, 0, 0, 400, 300);
      photo.setAttribute('src', canvas.toDataURL('image/png'));
    });
  });
 </script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>