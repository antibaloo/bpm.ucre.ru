<?

define("PULL_AJAX_INIT", true);
define("PUBLIC_AJAX_MODE", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("NO_AGENT_CHECK", true);
define("NOT_CHECK_PERMISSIONS", true);
define("DisableEventsCheck", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

header('Content-Type: application/x-javascript; charset='.LANG_CHARSET);

if (!CModule::IncludeModule("pull"))
{
	echo CUtil::PhpToJsObject(Array('ERROR' => 'PULL_MODULE_IS_NOT_INSTALLED'));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
	die();
}
if (intval($USER->GetID()) <= 0)
{
	echo CUtil::PhpToJsObject(Array('ERROR' => 'AUTHORIZE_ERROR'));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
	die();
}
if (check_bitrix_sessid())
{
	if ($_POST['SEND'] == 'Y' && $_POST['command'] !="")
	{
		CPullWatch::AddToStack('PULL_TEST',
			array(
				'module_id' => 'ucre',
				'command' => $_POST['command'],
				'params' => array(
					"USER" => $_POST['user'],
					"PHONE" => $_POST['phone'],
					"TYPE" => $_POST['type'],
					"SOURCE" => $_POST['source'],
					"CALLID" => $_POST['callid'],
					"CRM_ENTITY_TYPE" => $_POST['crm_enrity_type'],
					"CRM_ENTITY_ID" => $_POST['crm_entity_id']
				)
			)
		);
		echo CUtil::PhpToJsObject(Array('ERROR' => ''));
	}
	else
	{
		echo CUtil::PhpToJsObject(Array('ERROR' => 'UNKNOWN_ERROR'));
	}
}
else
{
	echo CUtil::PhpToJsObject(Array(
		'BITRIX_SESSID' => bitrix_sessid(),
		'ERROR' => 'SESSION_ERROR'
	));
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>