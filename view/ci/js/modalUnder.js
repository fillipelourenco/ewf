if (window.addEventListener) {
	window.addEventListener('load', hideLoading, false);
} else if (window.attachEvent) {
	var r = window.attachEvent("onload", hideLoading);
} else {
	hideLoading();
}