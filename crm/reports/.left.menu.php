<?
$aMenuLinks = array(
	array(
		"Отчет по истекающим договорам застройщиков", 
		"expire.php", 
		array(), 
		array(), 
	),
	array(
		"Отчет по действующим договорам застройщиков", 
		"working.php", 
		array(), 
		array(), 
	),
	array(
		"Отчет по группе", 
		"group.php", 
		array(), 
		array(),
		'in_array($USER->GetID(),array(1,24))'
	),
	array(
		"Назад к отчетам", 
		"/crm/reports/", 
		array(), 
		array(),
	)
);
?>