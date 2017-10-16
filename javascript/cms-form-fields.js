(function($) {
	$.entwine('ss', function($) {
		$('#Root .noticefield.persist').entwine({
			onmatch: function() {
				$(this).prepend(function() {
					return $('<div class="noticefield-close"></div>').click(function() {
						$(this).parent().hide();
					});
				});
				if ($('#Form_EditForm_error').length != 0) {
					$(this).detach().insertAfter('#Form_EditForm_error');
				} else if ($('#Form_ItemEditForm_error').length != 0) {
					$(this).detach().insertAfter('#Form_ItemEditForm_error');
				}
			}
		});
	});
})(jQuery)
