<?
$aMenuLinks = array(
	array(
		"Истекающие договора застройщиков", 
		"expire.php", 
		array(), 
		array(), 
	),
	array(
		"Действующие договора застройщиков", 
		"working.php", 
		array(), 
		array(), 
	),
	array(
		"Отчет по подразделению", 
		"group.php", 
		array(), 
		array(),
		'in_array($USER->GetID(),array(1,24))'
	),
	array(
		"Встречные заявки", 
		"relevant.php", 
		array(), 
		array(),
		'in_array($USER->GetID(),array(1,24))'
	),
	array(
		"Очередь на Авито", 
		"avito_stack.php", 
		array(), 
		array(),
		''
	),
	array(
		"Назад к отчетам", 
		"/crm/reports/", 
		array(), 
		array(),
	)
);
?>