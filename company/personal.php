<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/company/personal.php");
$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));
?><?$APPLICATION->IncludeComponent(
	"bitrix:socialnetwork_user", 
	".default", 
	array(
		"AJAX_LONG_TIMEOUT" => "60",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_HISTORY" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"ALLOW_POST_MOVE" => "N",
		"ALLOW_RATING_SORT" => "Y",
		"BLOG_ALLOW_POST_CODE" => "Y",
		"BLOG_COMMENT_AJAX_POST" => "Y",
		"BLOG_COMMENT_ALLOW_IMAGE_UPLOAD" => "A",
		"BLOG_COMMENT_ALLOW_VIDEO" => "Y",
		"BLOG_GROUP_ID" => "1",
		"BLOG_IMAGE_MAX_HEIGHT" => "1200",
		"BLOG_IMAGE_MAX_WIDTH" => "800",
		"BLOG_NO_URL_IN_COMMENTS" => "",
		"BLOG_NO_URL_IN_COMMENTS_AUTHORITY" => "",
		"BLOG_SHOW_SPAM" => "N",
		"BLOG_USE_CUT" => "N",
		"BLOG_USE_GOOGLE_CODE" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TIME_LONG" => "604800",
		"CACHE_TYPE" => "A",
		"CALENDAR_ALLOW_RES_MEETING" => "Y",
		"CALENDAR_ALLOW_SUPERPOSE" => "Y",
		"CALENDAR_ALLOW_VIDEO_MEETING" => "Y",
		"CALENDAR_IBLOCK_TYPE" => "events",
		"CALENDAR_PATH_TO_RES_MEETING" => "/services/?page=meeting&meeting_id=#id#",
		"CALENDAR_PATH_TO_VIDEO_MEETING" => "/services/video/",
		"CALENDAR_PATH_TO_VIDEO_MEETING_DETAIL" => "/services/video/detail.php?ID=#ID#",
		"CALENDAR_RES_MEETING_IBLOCK_ID" => "14",
		"CALENDAR_RES_MEETING_USERGROUPS" => "1",
		"CALENDAR_SUPERPOSE_CAL_IDS" => array(
			0 => "#CALENDAR_COMPANY_IBLOCK_ID#",
		),
		"CALENDAR_SUPERPOSE_CUR_USER_CALS" => "Y",
		"CALENDAR_SUPERPOSE_GROUPS_CALS" => "Y",
		"CALENDAR_SUPERPOSE_GROUPS_IBLOCK_ID" => "#CALENDAR_GROUPS_IBLOCK_ID#",
		"CALENDAR_SUPERPOSE_USERS_CALS" => "Y",
		"CALENDAR_USER_IBLOCK_ID" => "0",
		"CALENDAR_VIDEO_MEETING_IBLOCK_ID" => "#CALENDAR_RES_VIDEO_IBLOCK_ID#",
		"CALENDAR_VIDEO_MEETING_USERGROUPS" => "1",
		"CALENDAR_WEEK_HOLIDAYS" => array(
			0 => "5",
			1 => "6",
		),
		"CALENDAR_WORK_TIME_END" => "19",
		"CALENDAR_WORK_TIME_START" => "9",
		"CALENDAR_YEAR_HOLIDAYS" => (LANGUAGE_ID=="en")?"1.01, 25.12":((LANGUAGE_ID=="de")?"1.01, 25.12":"1.01, 2.01, 7.01, 23.02, 8.03, 1.05, 9.05, 12.06, 4.11, 12.12"),
		"CAN_OWNER_EDIT_DESKTOP" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DATE_TIME_FORMAT" => "d.m.Y H:i:s",
		"EDITABLE_FIELDS" => array(
			0 => "LOGIN",
			1 => "NAME",
			2 => "SECOND_NAME",
			3 => "LAST_NAME",
			4 => "EMAIL",
			5 => "TIME_ZONE",
			6 => "PASSWORD",
			7 => "PERSONAL_BIRTHDAY",
			8 => "PERSONAL_GENDER",
			9 => "PERSONAL_PHOTO",
			10 => "PERSONAL_PHONE",
			11 => "PERSONAL_MOBILE",
			12 => "WORK_DEPARTMENT",
			13 => "WORK_POSITION",
			14 => "WORK_PHONE",
			15 => "FORUM_SHOW_NAME",
			16 => "FORUM_DESCRIPTION",
			17 => "FORUM_INTERESTS",
			18 => "FORUM_SIGNATURE",
			19 => "FORUM_AVATAR",
			20 => "FORUM_HIDE_FROM_ONLINE",
			21 => "FORUM_SUBSC_GET_MY_MESSAGE",
			22 => "BLOG_ALIAS",
			23 => "BLOG_DESCRIPTION",
			24 => "BLOG_INTERESTS",
			25 => "BLOG_AVATAR",
			26 => "UF_PHONE_INNER",
			27 => "UF_LINKEDIN",
			28 => "UF_TWITTER",
			29 => "UF_FACEBOOK",
			30 => "UF_XING",
			31 => "UF_WEB_SITES",
			32 => "UF_SKILLS",
			33 => "UF_INTERESTS",
			34 => "UF_MANAGER",
			35 => "UF_NASTAVNIK",
			36 => "UF_CHILDREN",
			37 => "UF_FAMILI",
			38 => "UF_SERT",
			39 => "UF_DATE_CLOS",
		),
		"FILES_FORUM_ID" => "7",
		"FILES_UPLOAD_MAX_FILE" => "4",
		"FILES_UPLOAD_MAX_FILESIZE" => "1024",
		"FILES_USER_IBLOCK_ID" => "0",
		"FILES_USER_IBLOCK_TYPE" => "library",
		"FILES_USE_AUTH" => "Y",
		"FILES_USE_COMMENTS" => "Y",
		"FORUM_AJAX_POST" => "N",
		"FORUM_ID" => "3",
		"FORUM_THEME" => "blue",
		"GROUP_THUMBNAIL_SIZE" => "",
		"GROUP_USE_KEYWORDS" => "Y",
		"HIDE_OWNER_IN_TITLE" => "Y",
		"ITEM_DETAIL_COUNT" => "32",
		"ITEM_MAIN_COUNT" => "6",
		"LOG_AUTH" => "N",
		"LOG_COMMENT_THUMBNAIL_SIZE" => "",
		"LOG_NEW_TEMPLATE" => "Y",
		"LOG_PHOTO_COUNT" => "6",
		"LOG_PHOTO_THUMBNAIL_SIZE" => "48",
		"LOG_THUMBNAIL_SIZE" => "",
		"MAIN_MENU_TYPE" => "left",
		"NAME_FILE_PROPERTY" => "FILE",
		"NAME_TEMPLATE" => "#LAST_NAME# #NAME# #SECOND_NAME#",
		"PATH_TO_BLOG_SMILE" => "/bitrix/images/blog/smile/",
		"PATH_TO_CONPANY_DEPARTMENT" => "/company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#",
		"PATH_TO_FORUM_ICON" => "/bitrix/images/forum/icon/",
		"PATH_TO_FORUM_SMILE" => "/bitrix/images/forum/smile/",
		"PATH_TO_GROUP" => "/workgroups/group/#group_id#/",
		"PATH_TO_GROUP_PHOTO" => "/workgroups/group/#group_id#/photo/",
		"PATH_TO_GROUP_PHOTO_ELEMENT" => "/workgroups/group/#group_id#/photo/#section_id#/#element_id#/",
		"PATH_TO_GROUP_PHOTO_SECTION" => "/workgroups/group/#group_id#/photo/album/#section_id#/",
		"PATH_TO_GROUP_POST" => "/workgroups/group/#group_id#/blog/#post_id#/",
		"PATH_TO_GROUP_SEARCH" => "/workgroups/group/search/",
		"PATH_TO_GROUP_SUBSCRIBE" => "/workgroups/group/#group_id#/subscribe/",
		"PATH_TO_GROUP_TASKS" => "/workgroups/group/#group_id#/tasks/",
		"PATH_TO_GROUP_TASKS_REPORT" => "/workgroups/group/#group_id#/tasks/report/",
		"PATH_TO_GROUP_TASKS_TASK" => "/workgroups/group/#group_id#/tasks/task/#action#/#task_id#/",
		"PATH_TO_GROUP_TASKS_VIEW" => "/workgroups/group/#group_id#/tasks/view/#action#/#view_id#/",
		"PATH_TO_SEARCH_EXTERNAL" => "/company/index.php",
		"PATH_TO_SMILE" => "/bitrix/images/socialnetwork/smile/",
		"PHOTO_ALBUM_PHOTO_SIZE" => "150",
		"PHOTO_ALBUM_PHOTO_THUMBS_SIZE" => "150",
		"PHOTO_COMMENTS_TYPE" => "forum",
		"PHOTO_DISPLAY_AS_RATING" => "vote_avg",
		"PHOTO_ELEMENTS_PAGE_ELEMENTS" => "50",
		"PHOTO_FORUM_ID" => "2",
		"PHOTO_GALLERY_AVATAR_SIZE" => "50",
		"PHOTO_JPEG_QUALITY" => "90",
		"PHOTO_JPEG_QUALITY1" => "95",
		"PHOTO_JPEG_QUALITY2" => "95",
		"PHOTO_MAX_VOTE" => "5",
		"PHOTO_MODERATION" => "Y",
		"PHOTO_ORIGINAL_SIZE" => "1280",
		"PHOTO_PATH_TO_FONT" => "",
		"PHOTO_PREVIEW_SIZE" => "700",
		"PHOTO_SECTION_PAGE_ELEMENTS" => "15",
		"PHOTO_SHOW_WATERMARK" => "Y",
		"PHOTO_THUMBNAIL_SIZE" => "100",
		"PHOTO_THUMBS_SIZE" => "250",
		"PHOTO_UPLOADER_TYPE" => "form",
		"PHOTO_UPLOAD_MAX_FILE" => "4",
		"PHOTO_UPLOAD_MAX_FILESIZE" => "64",
		"PHOTO_USER_IBLOCK_ID" => "16",
		"PHOTO_USER_IBLOCK_TYPE" => "photos",
		"PHOTO_USE_CAPTCHA" => "N",
		"PHOTO_USE_COMMENTS" => "Y",
		"PHOTO_USE_RATING" => "Y",
		"PHOTO_VOTE_NAMES" => array(
			0 => "1",
			1 => "2",
			2 => "3",
			3 => "4",
			4 => "5",
			5 => "",
		),
		"PHOTO_WATERMARK_MIN_PICTURE_SIZE" => "400",
		"PHOTO_WATERMARK_RULES" => "USER",
		"RATING_ID" => array(
		),
		"RATING_TYPE" => "like_graphic",
		"SEARCH_DEFAULT_SORT" => "rank",
		"SEARCH_FILTER_DATE_NAME" => "sonet_search_filter_date",
		"SEARCH_FILTER_NAME" => "sonet_search_filter",
		"SEARCH_PAGE_RESULT_COUNT" => "10",
		"SEARCH_RESTART" => "N",
		"SEARCH_TAGS_COLOR_NEW" => "3E74E6",
		"SEARCH_TAGS_COLOR_OLD" => "C0C0C0",
		"SEARCH_TAGS_FONT_MAX" => "50",
		"SEARCH_TAGS_FONT_MIN" => "10",
		"SEARCH_TAGS_PAGE_ELEMENTS" => "100",
		"SEARCH_TAGS_PERIOD" => "",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SEF_FOLDER" => "/company/personal/",
		"SEF_MODE" => "Y",
		"SET_NAV_CHAIN" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_LOGIN" => "Y",
		"SHOW_RATING" => "",
		"SHOW_VOTE" => "N",
		"SHOW_YEAR" => "M",
		"SM_THEME" => "brown",
		"SONET_PATH_TO_FORUM_ICON" => "/bitrix/images/forum/icon/",
		"SONET_USER_FIELDS_LIST" => array(
			0 => "PERSONAL_BIRTHDAY",
			1 => "PERSONAL_BIRTHDAY_YEAR",
			2 => "PERSONAL_GENDER",
			3 => "PERSONAL_CITY",
		),
		"SONET_USER_FIELDS_SEARCHABLE" => array(
		),
		"SONET_USER_PROPERTY_LIST" => array(
			0 => "UF_1C",
		),
		"SONET_USER_PROPERTY_SEARCHABLE" => array(
		),
		"TASKS_FIELDS_SHOW" => array(
			0 => "ID",
			1 => "NAME",
			2 => "MODIFIED_BY",
			3 => "DATE_CREATE",
			4 => "CREATED_BY",
			5 => "DATE_ACTIVE_FROM",
			6 => "DATE_ACTIVE_TO",
			7 => "IBLOCK_SECTION",
			8 => "DETAIL_TEXT",
			9 => "TASKPRIORITY",
			10 => "TASKSTATUS",
			11 => "TASKCOMPLETE",
			12 => "TASKASSIGNEDTO",
			13 => "TASKALERT",
			14 => "TASKSIZE",
			15 => "TASKSIZEREAL",
			16 => "TASKFINISH",
			17 => "TASKFILES",
			18 => "TASKREPORT",
		),
		"TASK_FORUM_ID" => "8",
		"TASK_IBLOCK_ID" => "19",
		"TASK_IBLOCK_TYPE" => "services",
		"USER_FIELDS_CONTACT" => array(
			0 => "EMAIL",
			1 => "PERSONAL_PHONE",
			2 => "WORK_WWW",
			3 => "WORK_PHONE",
			4 => "WORK_FAX",
		),
		"USER_FIELDS_FORUM" => array(
			0 => "UF_FORUM_MESSAGE_DOC",
		),
		"USER_FIELDS_MAIN" => array(
			0 => "ID",
			1 => "BLOG_AVATAR",
		),
		"USER_FIELDS_PERSONAL" => array(
			0 => "PERSONAL_BIRTHDAY",
			1 => "PERSONAL_GENDER",
			2 => "WORK_POSITION",
		),
		"USER_FIELDS_SEARCH_ADV" => array(
			0 => "PERSONAL_GENDER",
			1 => "PERSONAL_COUNTRY",
			2 => "PERSONAL_CITY",
		),
		"USER_FIELDS_SEARCH_SIMPLE" => array(
			0 => "PERSONAL_GENDER",
			1 => "PERSONAL_CITY",
		),
		"USER_PROPERTIES_SEARCH_ADV" => array(
		),
		"USER_PROPERTIES_SEARCH_SIMPLE" => array(
		),
		"USER_PROPERTY_CONTACT" => array(
			0 => "UF_PHONE_INNER",
		),
		"USER_PROPERTY_MAIN" => array(
			0 => "UF_DNW",
			1 => "UF_SERT_DATE",
		),
		"USER_PROPERTY_PERSONAL" => array(
			0 => "UF_LINKEDIN",
			1 => "UF_FACEBOOK",
			2 => "UF_XING",
			3 => "UF_WEB_SITES",
			4 => "UF_SKILLS",
			5 => "UF_INTERESTS",
			6 => "UF_MANAGER",
			7 => "UF_NASTAVNIK",
			8 => "UF_SERT",
		),
		"USE_MAIN_MENU" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"user_reindex" => "user_reindex.php",
			"user_content_search" => "user/#user_id#/search/",
			"user" => "user/#user_id#/",
			"user_friends" => "user/#user_id#/friends/",
			"user_friends_add" => "user/#user_id#/friends/add/",
			"user_friends_delete" => "user/#user_id#/friends/delete/",
			"user_groups" => "user/#user_id#/groups/",
			"user_groups_add" => "user/#user_id#/groups/add/",
			"group_create" => "user/#user_id#/groups/create/",
			"user_profile_edit" => "user/#user_id#/edit/",
			"user_settings_edit" => "user/#user_id#/settings/",
			"user_features" => "user/#user_id#/features/",
			"group_request_group_search" => "group/#user_id#/group_search/",
			"group_request_user" => "group/#group_id#/user/#user_id#/request/",
			"search" => "search.php",
			"message_form" => "messages/form/#user_id#/",
			"message_form_mess" => "messages/form/#user_id#/#message_id#/",
			"user_ban" => "messages/ban/",
			"messages_chat" => "messages/chat/#user_id#/",
			"messages_input" => "messages/input/",
			"messages_input_user" => "messages/input/#user_id#/",
			"messages_output" => "messages/output/",
			"messages_output_user" => "messages/output/#user_id#/",
			"messages_users" => "messages/",
			"messages_users_messages" => "messages/#user_id#/",
			"log" => "log/",
			"activity" => "user/#user_id#/activity/",
			"subscribe" => "subscribe/",
			"user_subscribe" => "user/#user_id#/subscribe/",
			"user_photo" => "user/#user_id#/photo/",
			"user_calendar" => "user/#user_id#/calendar/",
			"user_files" => "user/#user_id#/files/lib/#path#",
			"user_blog" => "user/#user_id#/blog/",
			"user_blog_post_edit" => "user/#user_id#/blog/edit/#post_id#/",
			"user_blog_rss" => "user/#user_id#/blog/rss/#type#/",
			"user_blog_draft" => "user/#user_id#/blog/draft/",
			"user_blog_post" => "user/#user_id#/blog/#post_id#/",
			"user_blog_moderation" => "user/#user_id#/blog/moderation/",
			"user_forum" => "user/#user_id#/forum/",
			"user_forum_topic_edit" => "user/#user_id#/forum/edit/#topic_id#/",
			"user_forum_topic" => "user/#user_id#/forum/#topic_id#/",
			"bizproc" => "bizproc/",
			"bizproc_edit" => "bizproc/#task_id#/",
			"video_call" => "video/#user_id#/",
			"processes" => "processes/",
			"user_tasks" => "user/#user_id#/tasks/",
			"user_tasks_task" => "user/#user_id#/tasks/task/#action#/#task_id#/",
			"user_tasks_view" => "user/#user_id#/tasks/view/#action#/#view_id#/",
			"user_tasks_departments_overview" => "user/#user_id#/tasks/departments/",
			"user_tasks_projects_overview" => "user/#user_id#/tasks/projects/",
			"user_tasks_report" => "user/#user_id#/tasks/report/",
			"user_tasks_templates" => "user/#user_id#/tasks/templates/",
			"user_templates_template" => "user/#user_id#/tasks/templates/template/#action#/#template_id#/",
		)
	),
	false
);?><br>
<?if ($USER->isAdmin()){?>
<script>
	$("#im-call-button").after("<br><br><a class='webform-small-button' id='fire' style='background: red;color:white;'><span>Уволить</span></a>");
	$(".user-profile-block-wrap").after("<div id='resultOfFire'><div>");
	$("#fire").click(function(){
		if (confirm("Вы уверены, что хотите уволить этого сотрудника?")){
			console.log("Уволить нахуй!");
			var begin = document.location.href.indexOf("/user/")+6;
			var end = document.location.href.indexOf("/",begin);
			var userId = document.location.href.substring(document.location.href.indexOf("/user/")+6,document.location.href.indexOf("/",begin)); 
			console.log(userId);
			$.ajax({
				url: "/ajax/fireUser.php",
				type: "POST",
				data: {userId: userId},
				dataType: "html",
				success: function (html) {
					$("#resultOfFire").html(html);
				},
				error: function (html) {
					$("#resultOfFire").html("Технические неполадки! В ближайшее время все будет исправлено!");
				},
			});
		}
	});
</script>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<script>
	 window.onload = function() {
		 $(".bx-br-separator").each(function(){
			 $(this).detach();
		 });
		 $(".fields.files").find("img").each(function(){
			 $(this).attr("width","25%");
			 $(this).removeAttr("height");
		 });
  };
</script>