function Save_Button_onclick() {
	var outstr = self.frames["baidumapiframe"].onok();
	var code = '<p style="text-align: center;">' + outstr + '</p>';
	tinyMCEPopup.execCommand('mceInsertContent', false, code);
	tinyMCEPopup.close();
}

function Cancel_Button_onclick() {
	tinyMCEPopup.close();
	return false;
}