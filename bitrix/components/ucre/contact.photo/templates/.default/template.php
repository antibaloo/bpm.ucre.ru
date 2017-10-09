<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<table class="crm-offer-info-table crm-offer-main-info-text">
  <tbody>
    <tr>
      <td>
        <div class="crm-offer-title">
          <span class="crm-offer-drg-btn"></span>
          <span class="crm-offer-title-text"><a id="takephoto" href="#">Сфотографировать клиента</a>
            <input id="ajaxpath" type="hidden" value="<?=$arResult['COMPONENT_PATH']?>/save.php">
            <input id="contactid" type="hidden" value="<?=$arResult['CONTACT_ID']?>">
          </span>
          <span class="crm-offer-title-set-wrap">
            <span class="crm-offer-title-del"></span>
          </span>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<div id="photoblock" class="booth">
  <div class="buttons">
    <select id="deviceId">    
    </select>&nbsp;
    <a id="capture" href="#">Сфотографировать</a>&nbsp;
    <a id="save" href="#" class="disabled">Сохранить</a>&nbsp;
    <a id="close" href="#">Закрыть</a>
  </div>
  <div class="left">
    <video id="video" width="400" height="300" autoplay></video>
  </div>
  <div class="right">
    <canvas id="canvas" width="400" height="300"></canvas>
    <img src="/include/nophoto.jpg" id="photo" alt="Ваша фотография">
  </div>
</div>