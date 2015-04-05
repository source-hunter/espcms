var moviedigo = {
	init: function(ed) {
		// Setup browse button
		document.getElementById('srcbrowsercontainer').innerHTML = getBrowserHTML('srcbrowser', 'moviefile', 'media', 'theme_advanced_image');
		if (isVisible('srcbrowser'))
			document.getElementById('moviefile').style.width = '380px';
	}
};

tinyMCEPopup.onInit.add(moviedigo.init, moviedigo);


function Save_Button_onclick() {
	var moviefile = document.getElementById("moviefile").value;

	if (document.getElementById('width').value.match(/^[1-9]{1}[0-9]*$/ig)) {
		var width = document.getElementById('width').value;
	}
	if (document.getElementById('height').value.match(/^[1-9]{1}[0-9]*$/ig)) {
		var height = document.getElementById('height').value;
	}
	moviefile = tinyMCEPopup.editor.documentBaseURI.toAbsolute(moviefile);
	var outstr = '<embed src="http://www.ecisp.cn/ckplayer/ckplayer.swf" flashvars="f=' + moviefile + '&p=\'1\'&my_url=' + moviefile + '" quality="high" width="' + width + '" height="' + height + '" align="center" allowScriptAccess="always" allowFullscreen="true" type="application/x-shockwave-flash"></embed>';
	tinyMCEPopup.execCommand('mceInsertContent', false, outstr);
	tinyMCEPopup.close();
}

function Cancel_Button_onclick() {
	tinyMCEPopup.close();
	return false;
}