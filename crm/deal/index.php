<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/deal/index.php");
$APPLICATION->SetTitle(GetMessage("CRM_TITLE"));
?>
<!--
	Код взят из блога Антона Долганина
  http://blog.d-it.ru/crm/add-the-tab-to-the-box-bitrix24-crm/
-->
<script type="text/javascript">
	$(document).ready(function() {
		if (pagetitle.innerHTML.indexOf("Поиск покупателя: ",0)==-1 && pagetitle.innerHTML.indexOf("Новостройки: ",0)==-1){
			if (document.getElementById("CRM_DEAL_SHOW_V12_tab_tab_208445")) CRM_DEAL_SHOW_V12_tab_tab_208445.remove();
		}
		if (pagetitle.innerHTML.indexOf("Поиск покупателя: ",0)==-1 && pagetitle.innerHTML.indexOf("Подбор объекта: ",0)==-1 && pagetitle.innerHTML.indexOf("Новостройки: ",0)==-1){
			if (document.getElementById("CRM_DEAL_SHOW_V12_tab_tab_583919")) CRM_DEAL_SHOW_V12_tab_tab_583919.remove();
		}
		if (pagetitle.innerHTML.indexOf("Поиск покупателя: ",0)==-1 && pagetitle.innerHTML.indexOf("Подбор объекта: ",0)==-1 && pagetitle.innerHTML.indexOf("Новостройки: ",0)==-1){
			if (document.getElementById("CRM_DEAL_SHOW_V12_tab_tab_791211")) CRM_DEAL_SHOW_V12_tab_tab_791211.remove();
		}
	});
	var arTabLoading = []; 
	BX.ready(function(){
		//обработка открытия вкладки 
		BX.addCustomEvent('BX_CRM_INTERFACE_FORM_TAB_SELECTED', BX.delegate(function(self, name, tab_id){
				//вкладка "Лог авито"
				if (!arTabLoading[tab_id] && self.oTabsMeta[tab_id].name.toLowerCase().indexOf('авито') !== -1) { 
					var innerTab = BX('inner_tab_'+tab_id), 
							dealId = 0, matches = null, 
							waiter = BX.showWait(innerTab); 
					if (matches = window.location.href.match(/\/crm\/deal\/show\/([\d]+)\//i)) { 
						var dealId = parseInt(matches[1]); 
					} 
					if (dealId > 0) { 
						//чтобы не грузить повторно 
						arTabLoading[tab_id] = true; 
						BX.ajax({ 
							url: '/ajax/upload_avito_log.php', 
							method: 'POST', 
							dataType: 'html', 
							data: { 
								id: dealId 
							}, 
							onsuccess: function(data) 
							{ 
								innerTab.innerHTML = data; 
								BX.closeWait(innerTab, waiter); //$this <-> innerTab. в противном случае вызывал ошибку дебагера Битрикс
							}, 
							onfailure: function(data) 
							{ 
								BX.closeWait(innerTab, waiter); 
							} 
						}); 
					} 
				}
			//Вкладка "Лид"
			if (!arTabLoading[tab_id] && self.oTabsMeta[tab_id].name.toLowerCase().indexOf('лид') !== -1) {
				var innerTab = BX('inner_tab_'+tab_id), 
							dealId = 0, matches = null, 
							waiter = BX.showWait(innerTab); 
					if (matches = window.location.href.match(/\/crm\/deal\/show\/([\d]+)\//i)) { 
						var dealId = parseInt(matches[1]); 
					} 
					if (dealId > 0) { 
						//чтобы не грузить повторно 
						arTabLoading[tab_id] = true; 
						BX.ajax({ 
							url: '/ajax/lead_find.php', 
							method: 'POST', 
							dataType: 'html', 
							data: { 
								id: dealId 
							}, 
							onsuccess: function(data) 
							{ 
								innerTab.innerHTML = data; 
								BX.closeWait(innerTab, waiter); //$this <-> innerTab. в противном случае вызывал ошибку дебагера Битрикс
							}, 
							onfailure: function(data) 
							{ 
								BX.closeWait(innerTab, waiter); 
							} 
						}); 
					}
			}
			//Вкладка "Встречные заявки"
				if (!arTabLoading[tab_id] && self.oTabsMeta[tab_id].name.toLowerCase().indexOf('встречные') !== -1) { 
					var innerTab = BX('inner_tab_'+tab_id), 
							dealId = 0, matches = null, 
							waiter = BX.showWait(innerTab); 
					if (matches = window.location.href.match(/\/crm\/deal\/show\/([\d]+)\//i)) { 
						var dealId = parseInt(matches[1]); 
					}
					if (pagetitle.innerHTML.indexOf("Поиск покупателя: ",0)!=-1){ var categoryId = 0;}
					if (pagetitle.innerHTML.indexOf("Подбор объекта: ",0)!=-1){ var categoryId = 2;}
					if (pagetitle.innerHTML.indexOf("Новостройки: ",0)!=-1){ var categoryId = 4;}
					if (dealId > 0) { 
						//чтобы не грузить повторно 
						arTabLoading[tab_id] = true; 
						BX.ajax({ 
							url: '/ajax/selection.php?id='+dealId+'&category='+categoryId, 
							method: 'POST', 
							dataType: 'html', 
							data: { 
								id: dealId,
								category: categoryId
							}, 
							onsuccess: function(data) 
							{ 
								innerTab.innerHTML = data; 
								BX.closeWait(innerTab, waiter); //$this <-> innerTab. в противном случае вызывал ошибку дебагера Битрикс
							}, 
							onfailure: function(data) 
							{ 
								BX.closeWait(innerTab, waiter); 
							} 
						}); 
					}
				}
			//Вкладка "Потенциальные сделки"
				if (!arTabLoading[tab_id] && self.oTabsMeta[tab_id].name.toLowerCase().indexOf('потенциальные') !== -1) { 
					var innerTab = BX('inner_tab_'+tab_id), 
							dealId = 0, matches = null, 
							waiter = BX.showWait(innerTab); 
					if (matches = window.location.href.match(/\/crm\/deal\/show\/([\d]+)\//i)) { 
						var dealId = parseInt(matches[1]); 
					}
					if (pagetitle.innerHTML.indexOf("Поиск покупателя: ",0)!=-1){ var categoryId = 0;}
					if (pagetitle.innerHTML.indexOf("Подбор объекта: ",0)!=-1){ var categoryId = 2;}
					if (pagetitle.innerHTML.indexOf("Новостройки: ",0)!=-1){ var categoryId = 4;}
					if (dealId > 0) { 
						//чтобы не грузить повторно 
						arTabLoading[tab_id] = true; 
						BX.ajax({ 
							url: '/ajax/potentials.php', 
							method: 'POST', 
							dataType: 'html', 
							data: { 
								id: dealId,
								category: categoryId,
								tabid: 'inner_tab_'+tab_id
							}, 
							onsuccess: function(data) 
							{ 
								innerTab.innerHTML = data; 
								BX.closeWait(innerTab, waiter); //$this <-> innerTab. в противном случае вызывал ошибку дебагера Битрикс
							}, 
							onfailure: function(data) 
							{ 
								BX.closeWait(innerTab, waiter); 
							} 
						}); 
					}
				}
		})); 
	});
</script>
<?$APPLICATION->IncludeComponent(
	"bitrix:crm.deal",
	"",
	array(
		"SEF_MODE" => "Y",
		"PATH_TO_CONTACT_SHOW" => "/crm/contact/show/#contact_id#/",
		"PATH_TO_CONTACT_EDIT" => "/crm/contact/edit/#contact_id#/",
		"PATH_TO_COMPANY_SHOW" => "/crm/company/show/#company_id#/",
		"PATH_TO_COMPANY_EDIT" => "/crm/company/edit/#company_id#/",
		"PATH_TO_INVOICE_SHOW" => "/crm/invoice/show/#invoice_id#/",
		"PATH_TO_INVOICE_EDIT" => "/crm/invoice/edit/#invoice_id#/",
		"PATH_TO_LEAD_SHOW" => "/crm/lead/show/#lead_id#/",
		"PATH_TO_LEAD_EDIT" => "/crm/lead/edit/#lead_id#/",
		"PATH_TO_LEAD_CONVERT" => "/crm/lead/convert/#lead_id#/",
		"PATH_TO_USER_PROFILE" => "/company/personal/user/#user_id#/",
		"PATH_TO_PRODUCT_EDIT" => "/crm/product/edit/#product_id#/",
		"PATH_TO_PRODUCT_SHOW" => "/crm/product/show/#product_id#/",
		"ELEMENT_ID" => $_REQUEST["deal_id"],
		"SEF_FOLDER" => "/crm/deal/",
		"SEF_URL_TEMPLATES" => Array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#deal_id#/",
			"show" => "show/#deal_id#/"
		),
		"VARIABLE_ALIASES" => Array(
			"index" => Array(),
			"list" => Array(),
			"edit" => Array(),
			"show" => Array(),
		)
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>