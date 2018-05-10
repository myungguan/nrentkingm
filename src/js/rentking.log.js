function logPageview() {
	var param = {
		type: 'pageview',
		referer: document.referrer,
		url: location.href
	};
	$.post('/log.php', param);
}