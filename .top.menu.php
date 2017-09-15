<?
$curUser = $USER->GetID();
$aMenuLinks = array(
	array(
		"Компания", 
		"/about/", 
		Array(), 
		Array(), 
		"" 
	),

	array(
		"Юрист", 
		"/jurist/", 
		Array(), 
		Array(), 
		"in_array($curUser,array(11,24))" 
	),
	array(
		"HR", 
		"/hr/", 
		Array(), 
		Array(), 
		"in_array($curUser,array(1,11,24,26,218))" 
	),
	array(
		"CRM ЕЦН", 
		"/crm_ucre/", 
		Array(), 
		Array(), 
		"in_array($curUser,array(24))" 
	)
);
?>