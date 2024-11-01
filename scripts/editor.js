function miga_simple_events_deleteItem(obj) {
	var statusValue = obj.getAttribute("data-value");
	if (confirm("Delete item?")) {
		jQuery.post({
			url: miga_simple_events.wp_url,
			data: {
				action: 'miga_simple_events_delete',
				s: statusValue
			},
			complete: function(data) {},
			success: function(data) {
				window.location.reload()
			},
			error: function(data) {
				console.log("Error", data);
			}
		});
	}
	return false;
}

function miga_simple_events_updateItem(obj) {
	var statusValue = obj.getAttribute("data-value");
	var value_dateFrom = obj.parentNode.parentNode.querySelector(".tbl_dateFrom").value || null;
	var value_timeFrom = obj.parentNode.parentNode.querySelector(".tbl_timeFrom").value || null;
	var value_dateTo = obj.parentNode.parentNode.querySelector(".tbl_dateTo").value || null;
	var value_timeTo = obj.parentNode.parentNode.querySelector(".tbl_timeTo").value || null;
	var value_text = obj.parentNode.parentNode.querySelector(".tbl_text").value;
	var value_visible = obj.parentNode.parentNode.querySelector(".tbl_visible").checked ? 1 : 0;
	var link = obj.parentNode.parentNode.querySelector(".link").value;

	jQuery.post({
		url: miga_simple_events.wp_url,
		data: {
			action: 'miga_simple_events_update',
			s: statusValue,
			v: value_visible,
			dateFrom: value_dateFrom,
			timeFrom: value_timeFrom,
			dateTo: value_dateTo,
			timeTo: value_timeTo,
			text: value_text,
			link: link,
		},
		complete: function(data) {},
		success: function(data) {
			window.location.reload()
		},
		error: function(data) {
			console.log("Error", data);
		}
	});
	return false;
}
