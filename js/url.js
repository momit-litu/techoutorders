$(function () {
    //http://burritobrothers.test/admin/index.php?module=personal&view=profile
    //alert('ss')
    //alert(location.search)

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

        if(url_info.split('=')[0]=='passreset'){
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
                    console.log(data)

                    if(data==1){
                        localStorage.setItem('passkey',key);
                        window.location.replace(project_url+"index.php?page=account")
                    }
                    else {
                        localStorage.removeItem('key');
                        alert("This Link No More Valid, You are Redirected to Home Page")
                        window.location.replace(project_url+"index.php")


                    }
                }
            });
        }
        else if(url_info.split('=')[0]=='groupmaster'){
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
		
    }
});