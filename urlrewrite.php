<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/docs/pub/(?<hash>[0-9a-f]{32})/(?<action>[0-9a-zA-Z]+)/\\?#",
		"RULE" => "hash=\$1&action=\$2&",
		"ID" => "bitrix:disk.external.link",
		"PATH" => "/docs/pub/index.php",
	),
	array(
		"CONDITION" => "#^/disk/(?<action>[0-9a-zA-Z]+)/(?<fileId>[0-9]+)/\\?#",
		"RULE" => "action=\$1&fileId=\$2&",
		"ID" => "bitrix:disk.services",
		"PATH" => "/bitrix/services/disk/index.php",
	),
	array(
		"CONDITION" => "#^/pub/pay/([\\w\\W]+)/([0-9a-zA-Z]+)/([^/]*)#",
		"RULE" => "account_number=\$1&hash=\$2",
		"ID" => "",
		"PATH" => "/pub/payment.php",
	),
	array(
		"CONDITION" => "#^/pub/form/([0-9a-z_]+?)/([0-9a-z]+?)/.*#",
		"RULE" => "form_code=\$1&sec=\$2",
		"ID" => "bitrix:crm.webform.fill",
		"PATH" => "/pub/form.php",
	),
	array(
		"CONDITION" => "#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#",
		"RULE" => "alias=\$1",
		"ID" => "",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/mobile/disk/(?<hash>[0-9]+)/download#",
		"RULE" => "download=1&objectId=\$1",
		"ID" => "bitrix:mobile.disk.file.detail",
		"PATH" => "/mobile/disk/index.php",
	),
	array(
		"CONDITION" => "#^/tasks/getfile/(\\d+)/(\\d+)/([^/]+)#",
		"RULE" => "taskid=\$1&fileid=\$2&filename=\$3",
		"ID" => "bitrix:tasks_tools_getfile",
		"PATH" => "/tasks/getfile.php",
	),
	array(
		"CONDITION" => "#^/stssync/contacts_extranet_emp/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/contacts_extranet_emp/index.php",
	),
	array(
		"CONDITION" => "#^/settings/configs/userconsent/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/configs/userconsent.php",
	),
	array(
		"CONDITION" => "#^/extranet/workgroups/create/#",
		"RULE" => "",
		"ID" => "bitrix:extranet.group_create",
		"PATH" => "/extranet/workgroups/create/index.php",
	),
	array(
		"CONDITION" => "#^/extranet/contacts/personal/#",
		"RULE" => "",
		"ID" => "bitrix:socialnetwork_user",
		"PATH" => "/extranet/contacts/personal.php",
	),
	array(
		"CONDITION" => "#^/stssync/contacts_extranet/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/contacts_extranet/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/deal_category/#",
		"RULE" => "",
		"ID" => "bitrix:crm.deal_category",
		"PATH" => "/crm/configs/deal_category/index.php",
	),
	array(
		"CONDITION" => "#^/stssync/calendar_extranet/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/calendar_extranet/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/productprops/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.productprops",
		"PATH" => "/crm/configs/productprops/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/mailtemplate/#",
		"RULE" => "",
		"ID" => "bitrix:crm.mail_template",
		"PATH" => "/crm/configs/mailtemplate/index.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/docs/reglamenty-kompanii#",
		"RULE" => "",
		"ID" => "bitrix:disk.common",
		"PATH" => "/docs/reglamenty-kompanii/index.php",
	),
	array(
		"CONDITION" => "#^/stssync/tasks_extranet/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/tasks_extranet/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/automation/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.automation",
		"PATH" => "/crm/configs/automation/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/locations/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.locations",
		"PATH" => "/crm/configs/locations/index.php",
	),
	array(
		"CONDITION" => "#^/company/personal/mail/#",
		"RULE" => "",
		"ID" => "bitrix:intranet.mail.config",
		"PATH" => "/company/personal/mail/index.php",
	),
	array(
		"CONDITION" => "#^/extranet/mobile/webdav#",
		"RULE" => "",
		"ID" => "bitrix:mobile.webdav.file.list",
		"PATH" => "/extranet/mobile/webdav/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/mycompany/#",
		"RULE" => "",
		"ID" => "bitrix:crm.company",
		"PATH" => "/crm/configs/mycompany/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/currency/#",
		"RULE" => "",
		"ID" => "bitrix:crm.currency",
		"PATH" => "/crm/configs/currency/index.php",
	),
	array(
		"CONDITION" => "#^/stssync/contacts_crm/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/contacts_crm/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/measure/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.measure",
		"PATH" => "/crm/configs/measure/index.php",
	),
	array(
		"CONDITION" => "#^/extranet/workgroups/#",
		"RULE" => "",
		"ID" => "bitrix:socialnetwork_group",
		"PATH" => "/extranet/workgroups/index.php",
	),
	array(
		"CONDITION" => "#^/crm/reports/report/#",
		"RULE" => "",
		"ID" => "bitrix:crm.report",
		"PATH" => "/crm/reports/report/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/preset/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.preset",
		"PATH" => "/crm/configs/preset/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/exch1c/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.exch1c",
		"PATH" => "/crm/configs/exch1c/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/fields/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.fields",
		"PATH" => "/crm/configs/fields/index.php",
	),
	array(
		"CONDITION" => "#^/bizproc/processes/#",
		"RULE" => "",
		"ID" => "bitrix:lists",
		"PATH" => "/bizproc/processes/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/perms/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.perms",
		"PATH" => "/crm/configs/perms/index.php",
	),
	array(
		"CONDITION" => "#^/marketplace/local/#",
		"RULE" => "",
		"ID" => "bitrix:rest.marketplace.localapp",
		"PATH" => "/marketplace/local/index.php",
	),
	array(
		"CONDITION" => "#^/online/(/?)([^/]*)#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/townbase/building/#",
		"RULE" => "",
		"ID" => "ucre:building",
		"PATH" => "/townbase/building/index.php",
	),
	array(
		"CONDITION" => "#^/marketplace/hook/#",
		"RULE" => "",
		"ID" => "bitrix:rest.hook",
		"PATH" => "/marketplace/hook/index.php",
	),
	array(
		"CONDITION" => "#^/townbase/chess/#		",
		"RULE" => "",
		"ID" => "ucre:chess",
		"PATH" => "/townbase/chess/index.php",
	),
	array(
		"CONDITION" => "#^/stssync/contacts/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/contacts/index.php",
	),
	array(
		"CONDITION" => "#^/company/personal/#",
		"RULE" => "",
		"ID" => "bitrix:socialnetwork_user",
		"PATH" => "/company/personal.php",
	),
	array(
		"CONDITION" => "#^/crm/realtyobject/#",
		"RULE" => "",
		"ID" => "ucre:crm.realtyobject",
		"PATH" => "/crm/stream/index.php",
	),
	array(
		"CONDITION" => "#^/stssync/calendar/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/calendar/index.php",
	),
	array(
		"CONDITION" => "#^/townbase/complex/#",
		"RULE" => "",
		"ID" => "ucre:complex",
		"PATH" => "/townbase/complex/index.php",
	),
	array(
		"CONDITION" => "#^/crm/realtyobject/#",
		"RULE" => "",
		"ID" => "ucre:crm.realtyobject",
		"PATH" => "/crm/realtyobject/index.php",
	),
	array(
		"CONDITION" => "#^/timeman/meeting/#",
		"RULE" => "",
		"ID" => "bitrix:meetings",
		"PATH" => "/timeman/meeting/index.php",
	),
	array(
		"CONDITION" => "#^/marketplace/app/#",
		"RULE" => "",
		"ID" => "bitrix:app.layout",
		"PATH" => "/marketplace/app/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/tax/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.tax",
		"PATH" => "/crm/configs/tax/index.php",
	),
	array(
		"CONDITION" => "#^/company/gallery/#",
		"RULE" => "",
		"ID" => "bitrix:photogallery_user",
		"PATH" => "/company/gallery/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/bp/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.bp",
		"PATH" => "/crm/configs/bp/index.php",
	),
	array(
		"CONDITION" => "#^/services/lists/#",
		"RULE" => "",
		"ID" => "bitrix:lists",
		"PATH" => "/services/lists/index.php",
	),
	array(
		"CONDITION" => "#^/crm/configs/ps/#",
		"RULE" => "",
		"ID" => "bitrix:crm.config.ps",
		"PATH" => "/crm/configs/ps/index.php",
	),
	array(
		"CONDITION" => "#^/services/wiki/#",
		"RULE" => "",
		"ID" => "bitrix:wiki",
		"PATH" => "/services/wiki.php",
	),
	array(
		"CONDITION" => "#^/crm/lead_ucre/#",
		"RULE" => "",
		"ID" => "ucre:crm.lead",
		"PATH" => "/crm/lead_ucre/index.php",
	),
	array(
		"CONDITION" => "#^/services/idea/#",
		"RULE" => "",
		"ID" => "bitrix:idea",
		"PATH" => "/services/idea/index.php",
	),
	array(
		"CONDITION" => "#^/about/gallery/#",
		"RULE" => "",
		"ID" => "bitrix:photogallery",
		"PATH" => "/about/gallery/index.php",
	),
	array(
		"CONDITION" => "#^/stssync/tasks/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/tasks/index.php",
	),
	array(
		"CONDITION" => "#^/crm/invoicing/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/crm/invoicing/index.php",
	),
	array(
		"CONDITION" => "#^/services/faq/#",
		"RULE" => "",
		"ID" => "bitrix:support.faq",
		"PATH" => "/services/faq/index.php",
	),
	array(
		"CONDITION" => "#^/crm/exchange/#",
		"RULE" => "",
		"ID" => "ucre:exchange",
		"PATH" => "/crm/exchange/index.php",
	),
	array(
		"CONDITION" => "#^/crm/activity/#",
		"RULE" => "",
		"ID" => "bitrix:crm.activity",
		"PATH" => "/crm/activity/index.php",
	),
	array(
		"CONDITION" => "#^/mobile/webdav#",
		"RULE" => "",
		"ID" => "bitrix:mobile.webdav.file.list",
		"PATH" => "/mobile/webdav/index.php",
	),
	array(
		"CONDITION" => "#^/marketplace/#",
		"RULE" => "",
		"ID" => "bitrix:rest.marketplace",
		"PATH" => "/marketplace/index.php",
	),
	array(
		"CONDITION" => "#^/crm/webform/#",
		"RULE" => "",
		"ID" => "bitrix:crm.webform",
		"PATH" => "/crm/webform/index.php",
	),
	array(
		"CONDITION" => "#^/crm/product/#",
		"RULE" => "",
		"ID" => "bitrix:crm.product",
		"PATH" => "/crm/product/index.php",
	),
	array(
		"CONDITION" => "#^/crm/invoice/#",
		"RULE" => "",
		"ID" => "bitrix:crm.invoice",
		"PATH" => "/crm/invoice/index.php",
	),
	array(
		"CONDITION" => "#^/\\.well-known#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/groupdav.php",
	),
	array(
		"CONDITION" => "#^/services/bp/#",
		"RULE" => "",
		"ID" => "bitrix:bizproc.wizards",
		"PATH" => "/services/bp/index.php",
	),
	array(
		"CONDITION" => "#^/crm/contact/#",
		"RULE" => "",
		"ID" => "bitrix:crm.contact",
		"PATH" => "/crm/contact/index.php",
	),
	array(
		"CONDITION" => "#^/crm/complex/#",
		"RULE" => "",
		"ID" => "ucre:complex.test",
		"PATH" => "/crm/complex/index.php",
	),
	array(
		"CONDITION" => "#^/crm/company/#",
		"RULE" => "",
		"ID" => "bitrix:crm.company",
		"PATH" => "/crm/company/index.php",
	),
	array(
		"CONDITION" => "#^/docs/manage/#",
		"RULE" => "",
		"ID" => "bitrix:disk.common",
		"PATH" => "/docs/manage/index.php",
	),
	array(
		"CONDITION" => "#^/docs/shared#",
		"RULE" => "",
		"ID" => "bitrix:disk.common",
		"PATH" => "/docs/shared/index.php",
	),
	array(
		"CONDITION" => "#^/beelinePbx/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/beelinePbx/index.php",
	),
	array(
		"CONDITION" => "#^/crm/button/#",
		"RULE" => "",
		"ID" => "bitrix:crm.button",
		"PATH" => "/crm/button/index.php",
	),
	array(
		"CONDITION" => "#^/workgroups/#",
		"RULE" => "",
		"ID" => "bitrix:socialnetwork_group",
		"PATH" => "/workgroups/index.php",
	),
	array(
		"CONDITION" => "#^/docs/sale/#",
		"RULE" => "",
		"ID" => "bitrix:disk.common",
		"PATH" => "/docs/sale/index.php",
	),
	array(
		"CONDITION" => "#^/crm/quote/#",
		"RULE" => "",
		"ID" => "bitrix:crm.quote",
		"PATH" => "/crm/quote/index.php",
	),
	array(
		"CONDITION" => "#^/crm/lead/#",
		"RULE" => "",
		"ID" => "bitrix:crm.lead",
		"PATH" => "/crm/lead/index.php",
	),
	array(
		"CONDITION" => "#^//docs/all#",
		"RULE" => "",
		"ID" => "bitrix:disk.aggregator",
		"PATH" => "/docs/index.php",
	),
	array(
		"CONDITION" => "#^/crm/deal/#",
		"RULE" => "",
		"ID" => "bitrix:crm.deal",
		"PATH" => "/crm/deal/index.php",
	),
	array(
		"CONDITION" => "#^/crm/call/#",
		"RULE" => "",
		"ID" => "kitmedia:callhelper",
		"PATH" => "/crm/call/index.php",
	),
	array(
		"CONDITION" => "#^/docs/pub/#",
		"RULE" => "",
		"ID" => "bitrix:disk.external.link",
		"PATH" => "/docs/pub/extlinks.php",
	),
	array(
		"CONDITION" => "#^/rest/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/rest/index.php",
	),
	array(
		"CONDITION" => "#^/onec/#",
		"RULE" => "",
		"ID" => "bitrix:crm.1C.start",
		"PATH" => "/onec/index.php",
	),
);

?>