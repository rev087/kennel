window.addEvent('domready', function() {
	$('title').addEvent('keyup', function() {
		permalink = this.get('value').permalink();
		$('permalink').set('value', permalink);
	});
});

String.prototype.permalink = function() {
	str = this.toLowerCase();
	str = str.clean();
	str = String.standardize(str);
	str = str.replace(/ /g, '_');
	return str;
};
