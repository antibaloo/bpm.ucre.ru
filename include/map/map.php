<?
function minCoord($a,$b){
  return ($a<$b)?$a:$b;
}
function maxCoord($a,$b){
  return ($a>$b)?$a:$b;
}
function isInPoly($polygon,$point){
  $i=1;
  $N=count($polygon);
  $isIn=false;
  $p1=$polygon[0];
  $p2;
  for(;$i<=$N;$i++)	{
    $p2 = $polygon[$i % $N];
    if ($point['lon'] > minCoord($p1['lon'],$p2['lon'])){
      if ($point['lon'] <= maxCoord($p1['lon'],$p2['lon'])){
        if ($point['lat'] <= maxCoord($p1['lat'],$p2['lat'])){
          if ($p1['lon'] != $p2['lon']){
            $xinters = ($point['lon']-$p1['lon'])*($p2['lat']-$p1['lat'])/($p2['lon']-$p1['lon'])+$p1['lat'];
            if ($p1['lat'] == $p2['lat'] || $point['lat'] <= $xinters) $isIn=!$isIn;
          }
        }
      }
    }
    $p1 = $p2;
  }
  return $isIn;
}

function makePolyArray($polystring){
  $polystring = str_replace("[[","",$polystring);
  $polystring = str_replace("]]","",$polystring);
  $polyArrayTemp = explode("],[",$polystring);
  $polygonTemp = array();
  foreach ($polyArrayTemp as $point){
    $tempPoint = explode(",",$point);
    $polygonTemp[] = array(
      "lat" => $tempPoint[0],
      "lon" => $tempPoint[1]
    );
  }
  return $polygonTemp;
}
?>