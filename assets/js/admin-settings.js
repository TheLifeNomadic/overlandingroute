jQuery(document).ready(function($) {
	$('#tln_waypoint_inputs').on('submit', function(e) {
		e.preventDefault();
		$.post(ajaxurl, $(this).serializeArray(), function(data) {
			if (!data.success && data.message) {
				alert(data.message);
			} else {
				location.reload();
			}
		});
	});
});
