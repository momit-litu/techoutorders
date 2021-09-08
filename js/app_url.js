$(function () {
/*	
window.onhashchange = function() {
	alert("FUHHH");
    if (window.innerDocClick) {
        window.innerDocClick = false;
    } else {
        if (window.location.hash != '#undefined') {
            goBack();
        } else {
            history.pushState("", document.title, window.location.pathname);
            location.reload();
        }
    }
}
	*/
	/*$('.url_a').on("click", function(){ 
		var page = $(this).attr('href');						
		var val  = $(this).data('custom-value');
		$("#content").load("views/"+page+".php?"+val);
		history.pushState(null, null, "index.php?page="+page+"&"+val);
		return false;
	})
	
	
	
	$('.top-menu li a ').on('click touchstart', function() {
		var page = $(this).attr('href');
		if(page){
			$("#content").load("views/"+page+".php");
			history.pushState(null, null, "index.php?page="+page);
			return false;
		}
		else{
			history.pushState(null, null, "index.php");
		}
	});
	
	var current_page = location.search.split('page=');
	if(current_page != "undefined"){
		var current_page_r = location.search.split('page=')[1];
		if(current_page_r != "undefined" ){
			var right_url = current_page_r.split('&');
			alert(right_url[1])
			if($.trim(right_url[1]) != "undefined" ){
				alert(111)
				$("#content").load("views/"+right_url[0]+".php&"+right_url[1]);
			}
			else{		
				$("#content").load("views/"+right_url[0]+".php");
			}
			return false;
		}
		else{
			
		}
	}
	else{
	//	$("#content").load("views/index.php");
	}
	*/
/*
 return;
alert(00)

	var current_page = location.search.split('view=')[1];
	var current_mmod = location.search.split('module=');
	if($.trim(current_mmod[1]) != "undefined" && $.trim(current_mmod[1]) != ""){
		var current_module = current_mmod[1].split('&')[0];
		if(current_page != "undefined"){
			//$('#main_container').load("view/post/"+current_page+".php");
			$('#main_container').load("view/"+current_module+"/"+current_page+".php");
		}
	}


alert(11)

return;




    var url_info = location.search.split('?')[1];
    if(!url_info) {
        var url = location.search;
        if(window.location.pathname.split('/')[1] =='account.php'){
            $("#content").load("views/account.php");
        }
        else if(window.location.pathname.split('/')[1] =='cart.php'){
            $("#content").load("views/cart.php");
        }
        else if(window.location.pathname.split('/')[1] =='checkout.php'){
            $("#content").load("views/checkout.php");
        }
    }
    else{
        //alert('window..href')
        //alert(url_info)
        if(url_info.split('=')[0]=='groupmaster'){
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
                url: "./includes/controller/groupController.php",
                type:'POST',
                data:formdata,
                async:false,
                cache:false,
                contentType:false,processData:false,
                success: function(data){
                    //alert(data)

                    if(data==1){
                        window.location.replace(project_url+"index.php?page=categories")
                    }
                    else {
                        alert("This Link No More Valid, You are Redirected to Home Page")
                        window.location.replace(project_url)


                    }
                }
            });
        }		
        else if (url_info.split('=')[1].split('&')[0]== 'groupCheckout'){
            $("#content").load("views/groupCheckout.php");
        }
		

        var main_view=url_info.split('&')
        if(!main_view[1]){
            $("#content").load("views/"+main_view[0].split('page=')[1]+".php");
		}
        else {
            if(main_view[1].split('=')[0]=='category'){
                var data=main_view[1].split('category=')
                data = data[1].split('__').join(' ')
                $("#content").load("views/"+main_view[0].split('page=')[1]+".php");
            }
            else if(main_view[0].split('=')[1]=='item') {
                $("#content").load("views/"+main_view[0].split('page=')[1]+".php");
            }
            else if(main_view[1].split('=')[0]=='id') {
                var data=main_view[1].split('id=')
            }
        }
		
    }*/
});
