<?
function xmlToJSON($xmlString){
  $reader = new XMLReader();
  $reader->XML($xmlString);
  $jsonString = "{";
  while ($reader->read()) {
    switch ($reader->nodeType){
      case 1:
        $jsonString .= '"'.$reader->localName.'": {';
        if ($reader->hasAttributes){
          while($reader->moveToNextAttribute()){
            $jsonString .= '"'.$reader->localName.'": "'.$reader->value.'",';
          }
        }
        break;
      case 3:
        $jsonString .= '"'.value.'": "'.$reader->value.'"';
        break;
      case 15:
        if ($lastType == 15) $jsonString = mb_substr($jsonString, 0, -1);
        $jsonString .= '},';
        break;
    }
    $lastType = $reader->nodeType;
  }
  $reader->close();
  $jsonString = mb_substr($jsonString, 0, -1);
  $jsonString .= "}";
  return $jsonString;
}
?>