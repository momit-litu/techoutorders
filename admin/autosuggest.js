$(document).ready(function () {
	//Find Parent Exp categories based
	$("#parent_cat_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/autosuggests.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "expense_parent_cat_info",
					term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			var parent_cat_id = ui.item.id;
			$(this).next().val(parent_cat_id);
		}
	});


	//Find Exp categories for exp heads
	$("#exp_cat_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/autosuggests.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "expense_cat_info",
					term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			var exp_cat_id = ui.item.id;
			$(this).next().val(exp_cat_id);
		}
	});


	//Find Exp categories for exp heads
	$("#expense_head_name, #adv_expense_head_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/autosuggests.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "expense_head_info",
					term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			var expense_head_id = ui.item.id;
			$(this).next().val(expense_head_id);
		}
	});


	//Find people id who are only EMP
	$("#people_emp_name, #adv_people_emp_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/autosuggests.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "people_emp_infos",
					term: request.term,
					people_type:1
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			var people_emp_id = ui.item.id;
			$(this).next().val(people_emp_id);
		}
	});

	// find the employees  only
	$("#emp_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/autosuggests.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "employee_infos",
					term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			var emp_id = ui.item.id;
			$(this).next().val(emp_id);
		}
	});


	$("#salling_unit, #base_unit_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			$.ajax({
				url: project_url+'controller/autosuggests.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "unit_infos",
					term: request.term,
					people_type:9
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			var unit_id = ui.item.id;
			$(this).next().val(unit_id);
		}
	});

	$("#ingredient_size_name").autocomplete({
		search: function() {
		},
		source: function(request, response) {
			//alert(request.term)
			$.ajax({
				url: project_url+'controller/autosuggests.php',
				dataType: "json",
				type: "post",
				async:false,
				data: {
					q: "size_infos",
					term: request.term,
					people_type:9
				},
				success: function(data) {
					//alert(data)
					response(data);
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			var unit_id = ui.item.id;
			//alert(unit_id)
			$(this).next().val(unit_id);
		}
	});


	/*

        $('.date-picker').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_3",
            locale: {
                  format: 'YYYY-MM-DD',
                  separator: " - ",
            }
        });
        $('.date-picker').val("");
    */
});