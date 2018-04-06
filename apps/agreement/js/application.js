function to26LetterCode(id){
	var code = id;
	var digits26 = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
	var decimal = id;
	var ostatok = 0;
	var stroka26 = "";
	while(decimal >0){
		ostatok = decimal % 26;
		stroka26 = digits26[ostatok] + stroka26;
		decimal = Math.floor(decimal/26);
	}
	while (stroka26.length < 6){
		stroka26 = "A"+stroka26;
	}
	return String(stroka26);
}
// our application constructor
function application () {}

application.prototype.resizeFrame = function () {
	var currentSize = BX24.getScrollSize();
	minHeight = currentSize.scrollHeight;
	if (minHeight < 400) minHeight = 400;
	BX24.resizeWindow(this.FrameWidth, minHeight);
}

application.prototype.saveFrameWidth = function () {
	this.FrameWidth = document.getElementById("app").offsetWidth;
}

application.prototype.displayErrorMessage = function(message) {
	$('#lead-list').html(message);
}

application.prototype.displayCurrentUser = function(selector) {
	BX24.callMethod(
		'user.current',
		{},
		function(result){
			$(selector).html('Hello ' + result.data().NAME + ' ' + result.data().LAST_NAME + '!');
		}
	);
}

application.prototype.displayLeads = function () {
	var leadHTML = '';
	var ackReg = /^[A-Z]{1,6}$/;
	var curapp = this;
	
	BX24.callMethod(
		"crm.lead.list", 
		{ 
			order: { "DATE_CREATE": "DESC" },
			filter: {"!UF_CRM_1522138105" : "null"},
			select: [ "ID", "TITLE", "ASSIGNED_BY_ID", "UF_CRM_1522138105"]
		}, 
		function(result) 
		{
			if (result.error()) {
				curapp.displayErrorMessage('К сожалению, произошла ошибка получения лидов. Попробуйте повторить отчет позже');
				console.error(result.error());
			}else{
				var data = result.data();
				for (indexLead in data) {
					var sootv = ackReg.test(data[indexLead].UF_CRM_1522138105)?'да':'нет';
					leadHTML += '<tr><th scope="row">' + data[indexLead].ID + '</th><td>' + data[indexLead].TITLE +'</td><td>'+data[indexLead].ASSIGNED_BY_ID + '</td><td>'+data[indexLead].UF_CRM_1522138105+'</td><td>'+sootv+'</td></tr>';
				}
				if (result.more())
					result.next();
				else {
					$('#lead-list').html(leadHTML);
				}
			}
		}
	);
}
application.prototype.displayRanges = function () {
	var rangeHTML;
	var curapp = this;
	BX24.callMethod(
		"entity.item.get",
		{
			ENTITY: "agreement",
			SORT: {ID: 'DESC'},
			FILTER: {}
		},
		function (result){
			console.log(result);
			console.log(result.answer.total);
			if (result.error()){
				curapp.displayErrorMessage('К сожалению, произошла ошибка получения созданных диапазонов. Попробуйте повторить отчет позже');
				console.error(result.error());
			}else{
				if (result.answer.total === 0){
					$('#range-list').html('<tr><td colspan="6"><center>Записей нет</center></td></tr>');
					$('#allCount').val(0);
				}else{
					var data = result.data();
					//var allCount = 0;
					var allCount = result.answer.result[0].PROPERTY_VALUES.last;
					for (indexRange in data){
						var count = data[indexRange].PROPERTY_VALUES.last - data[indexRange].PROPERTY_VALUES.first+1;
						//if (indexRange === 0) allCount = data[indexRange].PROPERTY_VALUES.last;
						//allCount += count;
						var myDate = new Date(data[indexRange].DATE_CREATE); 
						var printDate = myDate.toLocaleString("ru");
						rangeHTML += '<tr>';
						rangeHTML += '<th scope="row">'+data[indexRange].ID+'</th>';
						rangeHTML += '<td>'+data[indexRange].NAME+'</td>';
						rangeHTML += '<td>'+printDate+'</td>';
						rangeHTML += '<td title="с '+data[indexRange].PROPERTY_VALUES.first+' по '+data[indexRange].PROPERTY_VALUES.last+'">'+count+'</td>';
						rangeHTML += '<td><button type="button" class="btn btn-warning">Создать лиды</button></td>';
						rangeHTML += '<td><button type="button" class="btn btn-info" onclick="app.savePdf('+data[indexRange].PROPERTY_VALUES.first+','+data[indexRange].PROPERTY_VALUES.last+')">Печать</button></td>';
						rangeHTML += '<td><button type="button" class="btn btn-danger" onclick="app.delRange('+data[indexRange].ID+')">X</button></td>';
						rangeHTML += '</tr>';
					}
					
					if (result.more()){
						result.next();
					}else {
						$('#range-list').html(rangeHTML+'<tr><td></td><td>Итого:</td><td></td><td>'+allCount+'</td><td></td><td></td></tr>');
						$('#allCount').val(allCount);
					}
				}
			}
		}
	);
}


application.prototype.addRange = function(description, first, last){
	var curapp = this;
	BX24.callMethod(
		'entity.item.add',
		{
			'ENTITY':'agreement',
			'NAME': description,
			'PROPERTY_VALUES': {
        'first': first,
				'last': last,
			},
		},
		function(result){
			if (result.error()){
				curapp.displayErrorMessage('К сожалению, произошла ошибка добавления диапазона. Сообщите администратору.');
				console.error(result.error());
			}else{
				console.log(result);
				curapp.displayRanges();
			}
		}
	);
}
application.prototype.delRange = function(id){
	var curapp = this;
	BX24.callMethod(
		'entity.item.delete', 
		{ENTITY: 'agreement',ID: id},
		function(result){
			console.log(result);
			curapp.displayRanges();
		}
	);
}
application.prototype.savePdf = function(first,last){
	var doc = new jsPDF(
		{ orientation: 'portrait',
		 unit: 'mm',
		 format: 'a4'}
	);
	var imgData = 'data:image/png;base64,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';
	for (var i = first; i <= last; i++) {
		if (i>first) doc.addPage();
		var ID = to26LetterCode(i)
		console.log(i+": "+ID);
		doc.setFontSize(12);
		doc.setFont('times','bold');
		doc.text(ID, 177, 14);
		doc.addImage(imgData, 'JPEG', 177, 16, 19, 19);
		doc.setFontSize(14);
		doc.text(ID, 20, 260);
		doc.addImage(imgData, 'JPEG', 20, 262, 21, 21);
	}
	doc.save('Диапазон_'+first+'_'+last+'pdf');
}
// create our application
app = new application();