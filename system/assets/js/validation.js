window.addEvent('domready', function() {
	var invalid_fields = $$('input.invalid, textarea.invalid');
	if (invalid_fields[0]) invalid_fields[0].focus();
});
