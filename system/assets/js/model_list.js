window.addEvent('domready', function() {
	if($$('.filter_box')) {
		$$('.filter_box')[0].getElements('.cancel').addEvent('click', function(e) {
			e.preventDefault();
			this.form.getElements('.text').each(function(input) {
				input.set('value', '');
			});
			this.form.submit();
		});
	}
});
