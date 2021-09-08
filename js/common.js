//var customer_id = '<%= Session["customer_id"] ?? 0 %>';
 //alert(customer_id)
//************** Notification******************* /
var notification_current_page_no = 1;
var last_notification_id = 1;

///alert(project_url)
function show_notification_details(notification_id, order_id){
	//if($.trim(order_id) !="null")	view_notification_details(order_id);
	$.ajax({
		url: project_url+"includes/controller/notificationController.php",
		type: "post",
		async:false,
		data:{
			q: "update_notification_status",
			notification_id:notification_id,
			order_id: order_id
		},
		success: function(data){
			//show_notifications()
			$('#noti_a_'+notification_id).removeClass("unseen");
			$('#noti_a_'+notification_id).removeClass("color_orange");
		}
	});
}



function show_notifications(type){
	if(customer_id && customer_id>0){
		if(localStorage.getItem('notification_page') && type=='more'){
			notification_current_page_no =  localStorage.getItem('notification_page')
		}else {
			notification_current_page_no=1;
			localStorage.setItem('notification_page', 1)
		}

		//alert(notification_current_page_no)
		$.ajax({
			url: project_url+"includes/controller/notificationController.php",
			dataType: "json",
			type: "post",
			async:false,
			data:{
				q: "load_notifications",
				limit:5,
				page_no:notification_current_page_no,
			},
			success: function(data) {
				//alert(data.total_unread)
				//console.log(data)
				last_notification_id = 0
				$('#unread_notifications').html(data.total_unread);
				if(!jQuery.isEmptyObject(data.records)){
					var notification_li = "";
					$.each(data.records, function(i,notification){
						last_notification_id = last_notification_id < notification.id ? notification.id : last_notification_id;
						if(notification.status == 0){
							notification_li +='<li id="noti_a_'+notification.id+'" onmouseover="show_notification_details('+notification.id+','+notification.order_id+')" class="color_orange  unseen"> <p class="message  bottom-margin-5">'+notification.details+'</p></li>';
						}
						else{
							notification_li +='<li id="noti_a_'+notification.id+'" onmouseover="show_notification_details('+notification.id+','+notification.order_id+')"> <p class="message bottom-margin-5">'+notification.details+'</p></li>';
						}
					})
					notification_li += '<li>\n' +
						'					<div class="text-left col-md-6">\n	' +
						'					<button class="btn btn-primary btn-xs " id="load_more_not_button" onclick="load_more()">More?</button>\n	' +
						'					</div>\n' +
						'					<div class="text-right col-md-6">\n' +
						'					<button class="btn btn-primary btn-xs " id="" onclick="notificaiton_all()">All></button>\n	' +
						'					</div>\n		' +
						'				</li>'


					//$('#notification_ul>li:last').before(notification_li);
					$('#notification_ul').html(notification_li);

					if(localStorage.getItem('last_notification_id')){

						if(parseInt(localStorage.getItem('last_notification_id'))<last_notification_id){
							localStorage.setItem('last_notification_id', last_notification_id)
							document.getElementById("myAudio").play();
 
						}
					}else {
						localStorage.setItem('last_notification_id', last_notification_id)
						document.getElementById("myAudio").play();
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

}

function notificaiton_all() {
	localStorage.setItem("currenturl", "notification");
	window.location.href = 'account.php'
}

function group_order(){
	localStorage.setItem("nexturl", "grouporder");
	localStorage.setItem("currenturl", "group_order");
	if(customer_id==0){

		$('#loginModal').modal();
	}
	else {
		window.location=project_url+'account.php'
	}
}

$('body').on("click", ".dropdown-menu", function (e) {
	$(this).parent().is(".open") && e.stopPropagation();
});

load_more = () =>{
	$(this).toggleClass('active');
	localStorage.setItem('notification_page', parseInt(localStorage.getItem('notification_page'))+1)
	//notification_current_page_no++;
	show_notifications('more');
}
/*$('#load_more_not_button').click(function() {
	$(this).toggleClass('active');
	localStorage.setItem('notification_page', localStorage.getItem('notification_page')+1)
	//notification_current_page_no++;
	show_notifications();
});*/

set_time_out_fn = function set_time_out_fn(){
	setTimeout(function(){
		//show_notifications_no();
		show_notifications('')
		set_time_out_fn();
	}, 30000);
}

set_time_out_fn();
//show_notifications_no();
show_notifications('');


function clear_group_order() {
	var formdata = new FormData();
	formdata.append('q','delete_group_member_order_session')
	//alert('safdds')
	$.ajax({
		url: project_url +"includes/controller/groupController.php",
		type:'POST',
		data:formdata,
		async:false,
		cache:false,
		contentType:false,processData:false,
		success: function(data){
			location.reload()
		}
	});
}


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
					if($.trim(data[column_arr[0]]) == 'Inactive' || $.trim(data[column_arr[0]]) == 'Deleted')
						html +='<td class="'+column_arr[0]+' text-center"><span class="btn btn-danger btn-xs">'+data[column_arr[0]]+'</span></td>';
					else if($.trim(data[column_arr[0]]) == 'Active' || $.trim(data[column_arr[0]]) == 'Completed')
						html +='<td class="'+column_arr[0]+' text-center"><span class="btn btn-success btn-xs">'+data[column_arr[0]]+'</span></td>';
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
			html += '<button onclick="view_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-info btn-xs"><i class="fa  fa-search-plus " ></i> </button>';
		else if(condition_array[0] == "no")
			html +="";
		else if(data[condition_array[0]] == condition_array[1])
			html += '<button onclick="view_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-info btn-xs"><i class="fa  fa-search-plus " ></i> </button>';

		if(condition_array[2] == "all")
			html += '<button onclick="edit_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-primary btn-xs"><i class="fa  fa-pencil " ></i> </button>';
		else if(condition_array[0] == "no")
			html +="";
		else if(data[condition_array[2]] == condition_array[3])
			html += '<button onclick="edit_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-primary btn-xs"><i class="fa  fa-pencil " ></i> </button>';

		if(condition_array[4] == "all")
			html += '<button onclick="delete_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-danger btn-xs"><i class="fa  fa-trash " ></i> </button>';
		else if(condition_array[0] == "no")
			html +="";
		else if(data[condition_array[4]] == condition_array[5])
			html += '<button onclick="delete_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-danger btn-xs"><i class="fa  fa-trash " ></i> </button>';


		if(condition_array.length > 6){
			if(data[condition_array[6]] == condition_array[7])
				html += '<button onclick="payment_'+module_name+'('+"'"+identifier_for_operation+"'"+')" type="button" class="btn btn-success btn-xs"><i class="fa  fa-dollar " ></i> </button>';
		}



		html += '</td></tr>';
	});
	$('#'+grid_id+' tbody').append(html);

	//i chek this is for when the data will set as tr the ichek cant read the tr's input
	// so to initialize the input call this below codes


	$('#'+grid_id+'>tbody input').on('ifChecked', function(event){
		$(this).closest("tr").addClass("selected");
	});
	$('#'+grid_id+'>tbody input').on('ifUnchecked', function(event){
		$(this).closest("tr").removeClass("selected");
	});
}

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

function success_or_error_msg(div_to_show, class_name, message, field_id){
	$(div_to_show).addClass('alert alert-custom alert-'+class_name).html(message).show("slow");
	//$(window).scrollTop(200);
	var set_interval = setInterval(function(){
		$(div_to_show).removeClass('alert alert-custom alert-'+class_name).html("").hide( "slow" );
		if(field_id!=""){ $(field_id).focus();}
		clearInterval(set_interval);
	}, 4000);
}

set_unpaid_time_out_fn = function set_unpaid_time_out_fn(){
	setTimeout(function(){
		//alert('unpaid')
		check_unpaid_order();
	}, 50000);
}


function active_modal(type){
	if(type==1){
		$('#forget_passModal').modal('hide');
		$('#registerModal').modal('hide');
	}
	else if(type==2){
		$('#loginModal').modal('hide');
		$('#registerModal').modal('hide');
	}
	else if(type==3){
		$('#loginModal').modal('hide');
		setTimeout(function(){
			$('#registerModal').modal();
		}, 400);
	}
	else if(type==4){
		if(customer_id && customer_id!=0 && customer_id!=null && !url.includes('account.php')){
			setTimeout(function(){
				check_unpaid_order();
				set_unpaid_time_out_fn();
				//$('#unpaid_orderModal').modal();
			}, 400);
		}
	}
}


$('#login_submit').click(function(event){
	event.preventDefault();
	var formData = new FormData($('#login-form')[0]);
	formData.append("q","login_customer");
	if($.trim($('#username').val()) == ""){
		success_or_error_msg('#login_submit_error','danger',"Please type user name","#emp_name");
	}
	if($.trim($('#password').val()) == ""){
		success_or_error_msg('#login_submit_error','danger',"Please type password","#password");
	}
	else{
		$.ajax({
			url: project_url+"includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==3){
					success_or_error_msg('#login_submit_error',"danger","Invalid username","#user_name" );
				}
				else if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#login_submit_error',"danger","Invalid password","#password" );
				}
				else if($.isNumeric(data)==true && data==1){
					$('#done_login').addClass("hide");
					$('#done_login_msg').removeClass("hide");
					$('.language-menu').html('<a href="'+project_url+'account.php" class="current-lang" id="my_acc"><i class="fa fa-user" aria-hidden="true" ></i> My Account</a>');
					$('.language-menu_hide').addClass("hide");
					if($('#islogged_in').length > 0 ){
						$('#islogged_in').val(1);
						$('.logged_in_already').addClass('hide');
					}

					if(localStorage.getItem("nexturl")=='grouporder'){
						window.location=project_url+'account.php'
					}
				}
			}
		});
	}
})

// send mail if forget password
$('#foget_pass_submit').click(function(event){
	event.preventDefault();
	var formData = new FormData($('#forget-pass-form')[0]);
	formData.append("q","forget_password");
	if($.trim($('#forget_email').val()) == ""){
		success_or_error_msg('#foget_pass_submit_error','danger',"Please enter email address","#forget_email");

	}
	else{
		$.ajax({
			url: project_url+"includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#foget_pass_submit_error',"danger","Please provide a valid email address","#forget_email" );
				}
				else if($.isNumeric(data)==true && data==1){
					$('.sent_password').addClass("hide");
					$('.sent_password_msg').removeClass("hide");
					$('#password_set').modal('show');

				}
			}
		});
	}
})

$('#new_password_submit').click(function (even) {
	event.preventDefault();
	var formData = new FormData($('#new-pass-form')[0]);

	formData.append("q","reset_password");
	if($.trim($('#new_password').val()) != $.trim($('#new_password_retype').val())){
		success_or_error_msg('#new_password_submit_error','danger',"Passwords Did not match","#forget_email");
	}
	else{
		$.ajax({
			url: project_url+"includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#foget_pass_submit_error',"danger","Please provide a valid email address","#forget_email" );
				}
				else if($.isNumeric(data)==true && data==1){
					$('.sent_password').addClass("hide");
					$('.sent_password_msg').removeClass("hide");
				}
			}
		});
	}
})
// send mail if forget password
$('#register_submit').click(function(event){

	//return  false;
	event.preventDefault();
	var formData = new FormData($('#register-form')[0]);
	formData.append("q","registration");
	if($.trim($('#cust_name').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter name","#cust_name");
	}
	else if($.trim($('#cust_username').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter username","#cust_username");
	}
	else if($.trim($('#cust_email').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter email address","#cust_email");
	}
	else if($.trim($('#cust_password').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter pasword","#cust_password");
	}
	else if($.trim($('#cust_conf_password').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please confirm password ","#cust_conf_password");
	}
	else if($.trim($('#cust_password').val()) != $.trim($('#cust_conf_password').val())){
		success_or_error_msg('#registration_submit_error','danger',"Please enter same password","#cust_conf_password");
	}
	else if($.trim($('#cust_contact').val()) == ""){
		success_or_error_msg('#registration_submit_error','danger',"Please enter valid contact no","#cust_contact");
	}
	else{
		if($.trim($('#cust_email').val()) != ""){
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if(!re.test($.trim($('#cust_email').val()))){
				success_or_error_msg('#registration_submit_error','danger',"Please Insert a valid email address","#cust_email");
				return false;
			}
		}


		$.ajax({
			url: project_url+"includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:true,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==2){
					$('#cust_username').focus();
					success_or_error_msg('#registration_submit_error',"danger","Username is already exist, please try with another one","#cust_username" );
				}
				else if($.isNumeric(data)==true && data==3){
					$('#cust_email').focus();
					success_or_error_msg('#registration_submit_error',"danger","Email is already exist, please try with another one","#cust_email" );
				}
				else if($.isNumeric(data)==true && data==1){
					$('.done_registration').addClass("hide");
					$('.done_registration_msg').removeClass("hide");


					if(localStorage.getItem("nexturl")=='grouporder'){
						window.location=project_url+'account.php'
					}
				}
				else{
					success_or_error_msg('#registration_submit_error',"danger","Registration is not completed. please check your information again.","#cust_email" );
				}
			}
		});
	}
})

$('#delete_cart').on('click', function () {
	//alert('sdf')
	$('#cart_empty').modal('hide');

	$.ajax({
		url: "includes/controller/groupController.php",
		dataType: "json",
		type: "post",
		async: false,
		data: {
			q: "check_cart",
		},
		success: function (data) {
			showCart()
		}
	})
})

if(localStorage.getItem('key') && localStorage.getItem('key')!=''){
	$('#password_set_key').val(localStorage.getItem('key'))
	localStorage.removeItem('key')
	$('#password_set').modal('show');
}

//send mail to cakencookies from contact page
$('#contact_submit').click(function(event){
	event.preventDefault();
	var formData = new FormData($('#contact-form')[0]);
	formData.append("q","contact_us_mail");
	if($.trim($('#first_name').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type name","#first_name");
	}
	else if($.trim($('#email').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type email","#email");
	}
	else if($.trim($('#mobile').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please enter mobile no.","#mobile");
	}
	else if($.trim($('#subject').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type subject.","#subject");
	}
	else if($.trim($('#message').val()) == ""){
		success_or_error_msg('#contact_submit_error','danger',"Please type message.","#message");
	}
	else{
		$.ajax({
			url: project_url+"includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==1){
					success_or_error_msg('#contact_submit_error',"success","Mail has sent","" );
					$('#contact-form')[0].reset();
				}
				else if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#contact_submit_error',"danger","Mail has sent","" );
				}
			}
		});
	}
})

//custome cake
$('#cc_submit').click(function(event){
	event.preventDefault();
	var formData = new FormData($('#custome-cake-form')[0]);
	formData.append("q","insert_custom_cake");
	if($.trim($('#cc_details').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please type details","#cc_details");
	}
	else if($.trim($('#cc_name').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please type name","#cc_name");
	}
	else if($.trim($('#cc_mobile').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please enter mobile no.","#cc_mobile");
	}
	else if($.trim($('#cc_email').val()) == ""){
		success_or_error_msg('#cc_submit_error','danger',"Please type email.","#cc_email");
	}
	else{
		$.ajax({
			url: project_url+"includes/controller/customerController.php",
			type:'POST',
			data:formData,
			async:false,
			cache:false,
			contentType:false,processData:false,
			success: function(data){
				if($.isNumeric(data)==true && data==1){
					success_or_error_msg('#cc_submit_error',"success","Request has benn accepted, please keep in touch. We will contact with you shortly","" );
					$('#custome-cake-form')[0].reset();
				}
				else if($.isNumeric(data)==true && data==2){
					success_or_error_msg('#cc_submit_error',"danger","Error! ","" );
				}
			}
		});
	}
})

$(document).on('click','#order_print', function(){
	var divContents = $("#order-div").html();
	var printWindow = window.open('', '', 'height=400,width=800');
	printWindow.document.write('<html><head><title>DIV Contents</title>');
	printWindow.document.write('</head><body style="padding:10px">');
	printWindow.document.write('<link href="plugin/bootstrap/bootstrap.css" rel="stylesheet">');
	printWindow.document.write(divContents);
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	printWindow.print();
});


$('.category a').on('click',function(){
	window.location = $(this).attr('href');
});

$('#searchSubmit').on('click',function(){
	window.location = 'search.php?search='+$('#searchbox'). val();
});


function catering() {
	window.location.href= project_url+'catering.php'
}
function pickup_order() {
	window.location.href= project_url+'categories.php'
}
function delivery_order() {
	window.location.href= 'https://postmates.com/merchant/burrito-brothers-washington-dc'
}

base_url = window.location.href
var url_info = base_url.split('?')[1];

if( url_info && url_info.split('=')[0]=='passreset'){
	var key = url_info.split('=')[1]
	//var data=[]
	var formdata = new FormData();
	formdata.append('key', key);
	formdata.append('q','password_reset_url_check')
	//alert('safdds')
	$.ajax({
		url: project_url +"includes/controller/customerController.php",
		type:'POST',
		data:formdata,
		async:false,
		cache:false,
		contentType:false,processData:false,
		success: function(data){
			//console.log(data)

			if(data==1){
				localStorage.clear();
				localStorage.setItem('passkey',key);
				//localStorage.setItem("currenturl", "update-profile");
				window.location.replace(project_url+"account.php")
			}
			else {
				localStorage.removeItem('key');
				alert("This Link No More Valid, You are Redirected to Home Page")
				window.location.replace(project_url+"index.php")


			}
		}
	});
}
else if(url_info && url_info.split('=')[0]=='groupmaster'){
	//alert('ok')
	var tem = url_info.split('=')[1].split('&')
	//alert(tem[0])
	//var data=[]
	var formdata = new FormData();
	formdata.append('group_order_details_id', tem[0]);
	formdata.append('order_key' , tem[1])
	formdata.append('q','group_member_order')
	//alert('safdds')
	$.ajax({
		url: project_url +"includes/controller/groupController.php",
		type:'POST',
		data:formdata,
		async:false,
		cache:false,
		contentType:false,processData:false,
		success: function(data){
			//alert(data)

			if(data==1){
				if(base_url.includes('/app/')){
					window.location.replace(project_app_url)
				}else {
					window.location.replace(project_url+"categories.php")
				}
			}
			else {
				alert("This Link No More Valid, You are Redirected to Home Page")
				window.location.replace(project_url)


			}
		}
	});
}
else if (url_info && url_info.split('=')[1] && url_info.split('=')[1].split('&')[0]== 'groupCheckout'){
	$("#content").load("views/groupCheckout.php");
}
url = window.location.href

//const string = "foo";
//const substring = "oo";

//console.log(url.includes('account.php'));
//if(customer_id && customer_id!=0 && customer_id!=null && !url.includes('account.php')){
	check_unpaid_order = () =>{
		let url = window.location.href;
		let url_arr = url.split('/');
		let page = url_arr[url_arr.length-1];
		if(page=='checkout_confirm.php') return;
		
		$.ajax({
			url:project_url +"includes/controller/ecommerceController.php",
			dataType: "json",
			type: "post",
			async:false,
			data:{
				q: "check_unpaid_order",
			},
			success: function(data) {
				//console.log();
				//alert(3)
				if(data!=false ){
					//alert('inn')
					$('#unpaid_order_id').html(data[0]['invoice_no']);
					$('#unpaid_orderModal').modal();
					//alert(555)
					//active_modal(4)
					//$('#unpaid_orderModal_click').trigger('click')

				}
			}
		});
	}
	//check_unpaid_order()
//}
paynow = () =>{
	localStorage.setItem("currenturl", "orders");
	//alert(localStorage.getItem("currenturl"))
	window.location.href=project_url+'account.php'
}


cancel_order = () =>{
	$.ajax({
		url:project_url +"includes/controller/ecommerceController.php",
		dataType: "json",
		type: "post",
		async:false,
		data:{
			q: "cancel_order",
			unpaid_order_id: $.trim($('#unpaid_order_id').html()),
		},
		success: function(data) {
			//alert(data)
			if(data!=false ){
				//$('#unpaid_orderModal').hide();
				$('#unpaid_orderModal_click').trigger('click')
			}
		}
	});
	
}



