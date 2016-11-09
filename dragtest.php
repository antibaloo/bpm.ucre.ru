<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тестирование компонента для сортировки картинок для выгрузки");
?>
<link rel="stylesheet" href="/bitrix/js/crm/css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/bitrix/js/crm/jquery.fancybox.pack.js?v=2.1.5"></script>
<style>
/* Prevent the text contents of draggable elements from being selectable. */
[draggable] {
  -moz-user-select: none;
  -khtml-user-select: none;
  -webkit-user-select: none;
  user-select: none;
  /* Required to make elements draggable in old WebKit */
  -khtml-user-drag: element;
  -webkit-user-drag: element;
}
.column {
  height: 150px;
  width: 150px;
  float: left;
  border: 2px solid #666666;
  background-color: #ccc;
  margin-right: 5px;
  -webkit-border-radius: 10px;
  -ms-border-radius: 10px;
  -moz-border-radius: 10px;
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 3px #000;
  -ms-box-shadow: inset 0 0 3px #000;
  box-shadow: inset 0 0 3px #000;
  text-align: center;
  cursor: move;
}
.column header {
  color: #fff;
  text-shadow: #000 0 1px;
  box-shadow: 5px;
  padding: 5px;
  background: -moz-linear-gradient(left center, rgb(0,0,0), rgb(79,79,79), rgb(21,21,21));
  background: -webkit-gradient(linear, left top, right top,
                               color-stop(0, rgb(0,0,0)),
                               color-stop(0.50, rgb(79,79,79)),
                               color-stop(1, rgb(21,21,21)));
  background: -webkit-linear-gradient(left center, rgb(0,0,0), rgb(79,79,79), rgb(21,21,21));
  background: -ms-linear-gradient(left center, rgb(0,0,0), rgb(79,79,79), rgb(21,21,21));
  border-bottom: 1px solid #ddd;
  -webkit-border-top-left-radius: 10px;
  -moz-border-radius-topleft: 10px;
  -ms-border-radius-topleft: 10px;
  border-top-left-radius: 10px;
  -webkit-border-top-right-radius: 10px;
  -ms-border-top-right-radius: 10px;
  -moz-border-radius-topright: 10px;
  border-top-right-radius: 10px;
}
  .column.over {
    border: 2px dashed #000;
  }
</style>
<?
CModule::IncludeModule('crm');
CModule::IncludeModule('iblock');
$arSelect = array("ID", "IBLOCK_ID", "PROPERTY_237", "PROPERTY_236");
$arFilter = array('ID' => 6692);
$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
$ob = $res->GetNext();
$pic_id_array = $ob['PROPERTY_237_VALUE'];
$pic_id_text = implode("#", $pic_id_array);

if (isset($_POST['string']) && $_POST['string']!=''){
  $pic_id_text = $_POST['string'];
  $pic_id_array = explode('#', $pic_id_text);
} else {
  $pic_id_array = $ob['PROPERTY_237_VALUE'];
  $pic_id_text = implode("#", $pic_id_array);
}

?>
<div id="columns">
  <? foreach($pic_id_array as $id) {
      $fileArray = CFile::GetFileArray($id);
  ?>
  <div class="column" draggable="true"><header id="file_id"><?=$id?></header>
    <a class="fancybox" rel="gallery1" href="https://bpm.ucre.ru<?=$fileArray['SRC']?>"><img src="https://bpm.ucre.ru/<?=$fileArray['SRC']?>" width="140px" height="120px" alt=""/></a>
  </div>
  <?}?>
</div>
<div>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" id="string" name="string" value="<?=$pic_id_text?>">
    <input type="submit" name="save" value="Сохранить" title="Сохранить и вернуться">
  </form>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $("a.fancybox").fancybox({
      helpers	: {
        title	: {
          type: 'inside'
        }
      }
    });
  });
 
  function handleDragOver(e) {
    if (e.preventDefault) {
      e.preventDefault(); // Necessary. Allows us to drop.
    }    
    e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
    return false;
  }
  function handleDragEnter(e) {
    // this / e.target is the current hover target.
    this.classList.add('over');
  }
  
  function handleDragLeave(e) {
    this.classList.remove('over');  // this / e.target is previous target element.
  }
  function handleDragEnd(e) {
    // this/e.target is the source node.
    [].forEach.call(cols, function (col) {
      col.classList.remove('over');
    });
  }
  var cols = document.querySelectorAll('#columns .column');
  [].forEach.call(cols, function(col) {
    col.addEventListener('dragstart', handleDragStart, false);
    col.addEventListener('dragenter', handleDragEnter, false)
    col.addEventListener('dragover', handleDragOver, false);
    col.addEventListener('dragleave', handleDragLeave, false);
    col.addEventListener('drop', handleDrop, false);
    col.addEventListener('dragend', handleDragEnd, false);
  });
  var dragSrcEl = null;
  function handleDragStart(e) {
    // Target (this) element is the source node.
    //this.style.opacity = '0.4';
    dragSrcEl = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
  }
  function handleDrop(e) {
    // this/e.target is current target element.
    if (e.stopPropagation) {
      e.stopPropagation(); // Stops some browsers from redirecting.
    }
    // Don't do anything if dropping the same column we're dragging.
    if (dragSrcEl != this) {
      // Set the source column's HTML to the HTML of the columnwe dropped on.
      dragSrcEl.innerHTML = this.innerHTML;
      this.innerHTML = e.dataTransfer.getData('text/html');
      var list = document.querySelectorAll('#file_id');
      var string_list = "";
      for (var i = 0; i < list.length; i++) {
        string_list = string_list + list[i].innerHTML;
        if (i != list.length - 1){
          string_list = string_list + "#";
        }
      }
      document.getElementById('string').value = string_list;
    }
    return false;
  }
</script>
<?
var_dump($_POST);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>