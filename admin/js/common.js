
// this functions are creted by moynul

var url = project_url+"controller/postController.php";
var notification_current_page_no = 1;
var report_head = '<div width="100%"  style="text-align:center"><img src="'+project_url+'/images/logo.png" width="80"/></div><h2 style="text-align:center">Molla Brick Factory</h2>';
// className: danger, success, info, primary, default, warning
function success_or_error_msg(div_to_show, class_name, message, field_id){
	$(div_to_show).addClass('alert alert-custom alert-'+class_name).html(message).show("slow");
	//$(window).scrollTop(200);
	var set_interval = setInterval(function(){
		$(div_to_show).removeClass('alert alert-custom alert-'+class_name).html("").hide( "slow" );
		if(field_id!=""){ $(field_id).focus();}
		clearInterval(set_interval);
	}, 4000);
}


// ckeditor instance
function ckeditorUpdateElement(){
	for ( instance in CKEDITOR.instances ) {
		CKEDITOR.instances[instance].updateElement();
	}
}

// i will work on this to ajax loading later

$(document).ajaxStart(function(){
	//alert('11')
	$("#wait").css("display", "block");
});
$(document).ajaxComplete(function(){
	$("#wait").css("display", "none");
});



// set no of record showing and from total no of records
function show_record_no(current_page_no, row_to_show, total_records ){
	var start = parseInt(((current_page_no*row_to_show)- row_to_show));
	var end   = start+row_to_show;
	$("#from_to_limit").html((start+1)+" to "+end);
	$("#total_record").html(total_records);
}



//var colums_int_array=["id", "title","post_date","post_type","post_status"];
// create_set_grid_table_row(records_array,colums_array,int_fields_array,condition_array,module_name,table/grid id, is_checkbox to select tr );
//need to provide the id/any other identification as first element of the "colums_array"
// the view_ , edit_, delete_ functions will use the id/identifications column
// module name is the name which will add after the "_" of  view_ , edit_, delete_
// need to write  view_ , edit_, delete_"module_name". ex: delete_post functions in current js script
// concate "*center" or "*right" to show the content alignment and default is "left"
// first 2 element is for view , 2nd&3rd for edit condition, 4th& 5th for delete condition
// if user can view/edit/delete all then just provide "all" to  1st / 3rd/ 5th and leave the next ""
// if condition is there for view/edit/delete then provide column_name and condition value, ex: "satus","1"
// ex:	var condition_array=["all","","status_id", "1","status_id","1"];
// cauton: not posssible to use multiple grid in same page
// developed : @momit


function create_set_grid_table_row(records_array,colums_array,condition_array,module_name,grid_id, is_checkbox){
	var html = "";
	var identifier_for_operation = "";
	$.each(records_array, function(i,data){
		// take the first column as identifier
		if(i%2 == 0){
			html += '<tr class="even pointer">';
		}
		else{
			html += '<tr class="odd pointer">';
		}
		for(var j = 0; j < colums_array.length; j++) {
			// split the columns for spliting the alignment value
			var column_arr = colums_array[j].split('*');
			if(column_arr.length>1){
				if(column_arr[1]=="identifier"){
					identifier_for_operation =data[column_arr[0]];
				}

				// if the grid requires a tr/row select options then is_checkbox=1
				if(is_checkbox ==1 && j ==0){
					html += '<td class="a-center "><input type="checkbox" class="tableflat"  name="'+module_name+'[]" value="'+data['id']+'"></td>';
				}
				if($.trim(column_arr[2]) != "hidden"){
					if(column_arr[1]=="image" && $.trim(column_arr[2]) != "undefined" &&  $.trim(data[column_arr[0]]) != null &&  $.trim(data[column_arr[0]]) != ""  ){
						html +='<td class="'+column_arr[0]+' text-center"><img src="'+column_arr[2]+data[column_arr[0]]+'" width="40" class="img-rounded img-responsive"></td>';
					}
					else if(column_arr[1]=='left' || column_arr[1]=='center' || column_arr[1]=='right')
						html +='<td class="'+column_arr[0]+' '+column_arr[1]+' text-'+column_arr[1]+'">'+data[column_arr[0]]+'</td>';
					else
						html +='<td class="'+column_arr[0]+' '+column_arr[1]+'">'+data[column_arr[0]]+'</td>';
				}
			}
			else{
				// for  status column
				if(column_arr[0].indexOf("status") >-1){
					if($.trim(data[column_arr[0]]) == 'Inactive' || $.trim(data[column_arr[0]]) == 'Deleted'  || $.trim(data[column_arr[0]]) == 'Ordered' || $.trim(data[column_arr[0]]) == 'Not Paid')
						html +='<td class="'+column_arr[0]+' text-center"><span class="btn btn-danger btn-xs">'+data[column_arr[0]]+'</span></td>';
					else if($.trim(data[column_arr[0]]) == 'Active' || $.trim(data[column_arr[0]]) == 'Completed' || $.trim(data[column_arr[0]]) == 'Picked Up' || $.trim(data[column_arr[0]]) == 'Paid')
						html +='<td class="'+column_arr[0]+' text-center"><span class="btn btn-success btn-xs">'+data[column_arr[0]]+'</span></td>';
					else if($.trim(data[column_arr[0]]) == 'Rejected' )
						html +='<td class="'+column_arr[0]+' text-center"><span class="btn btn-warning btn-xs">'+data[column_arr[0]]+'</span></td>';
					else
						html +='<td class="'+column_arr[0]+' text-center"><span class="btn btn-info btn-xs">'+data[column_arr[0]]+'</span></td>';
				}
				else{
					html +='<td class="'+column_arr[0]+'">'+data[column_arr[0]]+'</td>';
				}
			}
		}
		html +='<td class="a-center">';
		// first element is for view , edit condition, delete condition
		//var condition_array=["all","data.status_id == 1","data.status_id == 1"];
		if(condition_array[0] == "all")
			html += '<button onclick="view_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-info btn-sm"><i class="fa  fa-search-plus " ></i> </button>';
		else if(condition_array[0] == "no")
			html +="";
		else if(data[condition_array[0]] == condition_array[1])
			html += '<button onclick="view_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-info btn-sm"><i class="fa  fa-search-plus " ></i> </button>';

		if(condition_array[2] == "all")
			html += '<button onclick="edit_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-primary btn-sm"><i class="fa  fa-pencil " ></i> </button>';
		else if(condition_array[0] == "no")
			html +="";
		else if(data[condition_array[2]] == condition_array[3])
			html += '<button onclick="edit_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-primary btn-sm"><i class="fa  fa-pencil " ></i> </button>';

		if(condition_array[4] == "all")
			html += '<button onclick="delete_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-danger btn-sm"><i class="fa  fa-trash " ></i> </button>';
		else if(condition_array[0] == "no")
			html +="";
		else if(data[condition_array[4]] == condition_array[5])
			html += '<button onclick="delete_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-danger btn-sm"><i class="fa  fa-trash " ></i> </button>';


		if(condition_array.length > 6){
			if(data[condition_array[6]] == condition_array[7])
				html += '<button onclick="payment_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-success btn-sm"><i class="fa  fa-dollar " ></i> </button>';
		}



		html += '</td></tr>';
	});
	$('#'+grid_id+' tbody').append(html);

	//i chek this is for when the data will set as tr the ichek cant read the tr's input
	// so to initialize the input call this below codes

	$('#'+grid_id+'>tbody').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});
	$('#'+grid_id+'>tbody input').on('ifChecked', function(event){
		$(this).closest("tr").addClass("selected");
	});
	$('#'+grid_id+'>tbody input').on('ifUnchecked', function(event){
		$(this).closest("tr").removeClass("selected");
	});
}


// paging
//@param: total_pages - that returns from the sql row count
// current_page: user's current position 
// paging_div_id - where the paging html will be set
// developed : @momit

function paging(total_pages, current_page_no,paging_div_id ){
	if(total_pages == 1){
		var paging_html = '<a tabindex="0" class="first paginate_button paginate_button_disabled" id="'+paging_div_id+'_first">First</a>'+
			'<a tabindex="0" class="previous paginate_button paginate_button_disabled" id="'+paging_div_id+'_previous">Previous</a>'+
			'<span>';
		paging_html  += '<a tabindex="0" class="paginate_active">1</a>';
		paging_html   += '</span>'+
			'<a tabindex="0" class="next paginate_button" id="'+paging_div_id+'_next">Next</a>'+
			'<a tabindex="0" class="last paginate_button" id="'+paging_div_id+'_last">Last</a>';
	}
	else if(total_pages > 1){
		if(current_page_no == 1) prev_page_no = 0;
		else 					 prev_page_no = (parseInt(current_page_no)-1);
		var paging_html = '<a tabindex="0" class="first paginate_button paginate_button_disabled" id="'+paging_div_id+'_first" onclick="load_page(1)">First</a>'+
			'<a tabindex="0" class="previous paginate_button paginate_button_disabled" id="'+paging_div_id+'_previous" onclick="load_page('+prev_page_no+')" >Previous</a>'+
			'<span>';
		page_i=1
		while(page_i < total_pages+1){
			if(current_page_no == page_i) paging_html  += '<a tabindex="0" class="paginate_active" onclick="load_page('+page_i+')">'+page_i+'</a>';
			else 					      paging_html  += '<a tabindex="0" class="paginate_button" onclick="load_page('+page_i+')">'+page_i+'</a>';
			page_i++;
		}
		if(current_page_no == total_pages)  next_page_no = 0;
		else 					 			next_page_no = (parseInt(current_page_no)+1);
		paging_html   += '</span>'+
			'<a tabindex="0" class="next paginate_button" id="'+paging_div_id+'_next" onclick="load_page('+next_page_no+')">Next</a>'+
			'<a tabindex="0" class="last paginate_button" id="'+paging_div_id+'_last" onclick="load_page('+total_pages+')">Last</a>';
	}
	$('#'+paging_div_id+'_paginate').html(paging_html);
}

function grid_has_no_result(table_id, colspan){
	html = '<tr class="even pointer"><td class="text-center" colspan="'+colspan+'" rowspan="10" ><div class="text-center alert alert-danger">There is no records</div></td></tr>';
	$('#'+table_id+' tbody').append(html);
	$('#'+table_id+'_div').hide();
	//mypostTable_div
}



// calculate hours / days 
function show_date_foramting(date) {
	var curr_date = new Date().toLocaleFormat('%Y-%m-%d %H:%M:%S');
	var date2     = new Date(date).toLocaleFormat('%Y-%m-%d %H:%M:%S');
	//alert(curr_date + '---' +date2);

	//	var _MS_PER_DAY = 1000 * 60 * 60 * 24;
	// Discard the time and time-zone information.
	var timeDiff = Math.abs(curr_date.getTime() - date2.getTime());
	var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

	alert(diffDays);
	//return Math.floor((utc2 - utc1) / _MS_PER_DAY);

}





function set_localStorage(columns){
	$.each(columns, function(i,column_name){
		var column_arr = column_name.split('*');
		$('#'+column_arr[0]).change(function () {
			localStorage.setItem(column_arr[0], $('#'+column_arr[0]).val());
			if(column_arr[1]!= "undefined")
				localStorage.setItem(column_arr[1], $('#'+column_arr[1]).val());
		});
		if (name = localStorage.getItem(column_arr[0])) {
			$('#'+column_arr[0]).val(name);
			if(column_arr[1]!= "undefined")
				$('#'+column_arr[1]).val(localStorage.getItem(column_arr[1]));
		}
	});
}






function reset_localStorage(columns){
	$.each(columns, function(i,column_name){
		if (localStorage.getItem(column_name)) {
			localStorage.removeItem(column_name);
		}
	});
}


function reset_autoSuggest_ids(columns){
	$.each(columns, function(i,column_name){
		$('#'+column_name).val("")
	});
}






//************** Notification******************* /


load_more = () =>{
	//$(this).toggleClass('active');
	localStorage.setItem('notification_page', parseInt(localStorage.getItem('notification_page'))+1)
	//notification_current_page_no++;
	//alert(1)
	show_notifications('more');
	//alert(2)
}

notificaiton_all = () =>{
	window.location.href= project_url+'index.php?module=notification&view=adminNotification';
}

var audio = new Audio(project_root_url+'tone/Tinkle.mp3');
playTune = (type) =>{

	if(type==1){
		audio.play();
	}else {
		audio.pause();
	}
}

function show_notifications(type){

	if(localStorage.getItem('notification_page') && type=='more'){
		notification_current_page_no =  localStorage.getItem('notification_page')
	}else {
		notification_current_page_no=1;
		localStorage.setItem('notification_page', 1)
	}

	//alert(notification_current_page_no)
	$.ajax({
		url: project_url+"controller/notificationController.php",
		dataType: "json",
		type: "post",
		async:false,
		data:{
			q: "load_notifications",
			limit:5,
			page_no:notification_current_page_no,
		},
		success: function(data) {
			if(data==0){
				alert("You session is expired! please login again");
				window.location.href=project_url+'login.php';
			}
			//alert(data.total_unread)
			//console.log(data)
			last_notification_id = 0
			 urnead =0
			$('#unread_notifications').html(data.total_unread);
			if(!jQuery.isEmptyObject(data.records)){
				var notification_li = "";
				$.each(data.records, function(i,notification){
					last_notification_id = last_notification_id < notification.id ? notification.id : last_notification_id;
					if(notification.status == 0){
						//alert(notification.details)
						urnead =1
						notification_li +='<li id="noti_a_'+notification.id+'" onclick="show_notification_details('+notification.id+','+notification.order_id+')" class="color_orange  unseen" style="background-color: #db9b;"> <p class="message  bottom-margin-5">'+notification.details+'</p></li>';
					}
					else{
						notification_li +='<li id="noti_a_'+notification.id+'" onclick="show_notification_details('+notification.id+','+notification.order_id+')"> <p class="message bottom-margin-5">'+notification.details+'</p></li>';
					}
				})
				notification_li_buttom = '<li>\n' +
					'					<div class="text-left col-md-6">\n	' +
					'					<button class="btn btn-primary btn-xs " id="load_more_not_button" onclick="load_more()">More?</button>\n	' +
					'					</div>\n' +
					'					<div class="text-right col-md-6">\n' +
					'					<button class="btn btn-primary btn-xs " id="" onclick="notificaiton_all()">All></button>\n	' +
					'					</div>\n		' +
					'				</li>'


				//$('#notification_ul>li:last').before(notification_li);
				if(localStorage.getItem('notification_page') && type=='more'){
					$('#notification_ul_body').append(notification_li)
					//$('.noti').trigger('click')

				}else {
					$('#notification_ul_body').html(notification_li);
				}
				$('#notification_ul_b').html(notification_li_buttom);


				if(localStorage.getItem('last_notification_id')){
					if(parseInt(localStorage.getItem('last_notification_id'))<last_notification_id){
						localStorage.setItem('last_notification_id', last_notification_id)
						//document.getElementById("myAudio").play();
						//playTune(1)
					}
				}
				else {
					localStorage.setItem('last_notification_id', last_notification_id)
					//document.getElementById("myAudio").play();
					//playTune(1)
				}
				//alert('before play')
				//document.getElementById("myAudio").play();
				//alert('playing');
				//document.getElementById("myAudio").pause();
				if(urnead==1){
					document.getElementById("myAudio").play();
					//playTune(1)
				}else {
					document.getElementById("myAudio").pause();
					//playTune(11)

				}


				//alert(document.getElementById("myAudio").html)

				$('#load_more_not_button').removeClass("active");
			}
			else{
				notification_li = '<li> <div class="text-center alert alert-danger"> No More Notifications   </div></li>	';
				$('#notification_ul>li:last').before(notification_li);
				$('#load_more_not_button').removeClass("active");
				$('#load_more_not_button').attr("disabled","disabled");
			}
		}
	});
}

//show_notifications('');

/*
function show_notifications_no(){
	//alert("show notification no");
	$.ajax({
		url: project_url+"controller/notificationController.php",
		dataType: "json",
		type: "post",
		async:true,
		global: false,
		data:{
			q: "load_notifications_no"
		},
		success: function(data) {
			//alert(data.total_un_notified)
			// play tone
			if(data.total_un_notified>0) document.getElementById("myAudio").play();
			$('#unread_notifications').html(data.total_unread);
		}
	});
}
*/

function show_notification_details(notification_id, order_id){
	//document.getElementById("myAudio").pause();
	//clearInterval(playTone);
	playTune(11)

	if(order_id != null){
		//alert(notification_id)
		$.ajax({
			url: project_url+"controller/notificationController.php",
			type: "post",
			async:false,
			data:{
				q: "get_order_id",
				notification_id:notification_id
			},
			success: function(data){
				//console.log(data)
				view_notification_details(data);
			}
		});

	}
	$.ajax({
		url: project_url+"controller/notificationController.php",
		type: "post",
		async:false,
		data:{
			q: "update_notification_status",
			notification_id:notification_id
		},
		success: function(data){
			show_notifications('')
			$('#noti_a_'+notification_id).css("color","#5A738E !important")
		}
	});
}


//************** Notification End******************* /


function tofixed(num) {
	return parseFloat(num).toFixed(2);
}


function roundNumber(num, nearest) {
	if(!nearest) { nearest = 0.05; }
	return Math.round((num / nearest) * nearest);
}
function getNumber(x) {
	return accounting.unformat(x);
}
function formatQuantity(x) {
	return (x != null) ? '<div class="text-center">'+formatNumber(x, 2)+'</div>' : '';
}
function formatQuantity2(x) {
	return (x != null) ? formatQuantityNumber(x, 2) : '';
}
function formatQuantityNumber(x, d) {
	if (!d) { d = 2; }
	return parseFloat(accounting.formatNumber(x, d, '', '.'));
}
function formatQty(x) {
	return (x != null) ? formatNumber(x, 2) : '';
}
function formatNumber(x, d) {
	if(!d && d != 0) { d = 2; }

	return formatSA(parseFloat(x).toFixed(d));

	//return accounting.formatNumber(x, d, ',');
}
function formatSA (x) {
	x=x.toString();
	var afterPoint = '';
	if(x.indexOf('.') > 0)
		afterPoint = x.substring(x.indexOf('.'),x.length);
	x = Math.floor(x);
	x=x.toString();
	var lastThree = x.substring(x.length-3);
	var otherNumbers = x.substring(0,x.length-3);
	if(otherNumbers != '')
		lastThree = ',' + lastThree;
	var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;

	return res;
}



function formatMoney(x, symbol) {
	// symbol = "";
	//if(site.settings.sac == 1) {
	return formatSA(parseFloat(x).toFixed(2));
	//}
	//var fmoney = accounting.formatMoney(x, symbol, 2, ',', "%s%v");
	// return    fmoney;
}



function is_valid_discount(mixed_var) {
	return (is_numeric(mixed_var) || (/([0-9]%)/i.test(mixed_var))) ? true : false;
}
function is_numeric(mixed_var) {
	var whitespace =
		" \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
	return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
		1)) && mixed_var !== '' && !isNaN(mixed_var);
}
function is_float(mixed_var) {
	return +mixed_var === mixed_var && (!isFinite(mixed_var) || !! (mixed_var % 1));
}
function decimalFormat(x) {
	return '<div class="text-center">'+formatNumber(x != null ? x : 0)+'</div>';
}
function currencyFormat(x) {
	return '<div class="text-right">'+formatMoney(x != null ? x : 0)+'</div>';
}
function formatDecimal(x, d) {
	if (!d) { d = 2; }
	return parseFloat(accounting.formatNumber(x, d, '', '.'));
}
function formatDecimals(x, d) {
	if (!d) { d = 2; }
	return parseFloat(accounting.formatNumber(x, d, '', '.')).toFixed(d);
}




