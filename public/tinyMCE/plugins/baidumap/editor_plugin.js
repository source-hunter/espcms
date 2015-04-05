(function () {
	tinymce.PluginManager.requireLangPack('baidumap');
	tinymce.create('tinymce.plugins.baidumap', {
		init: function (ed, url) {
			ed.addCommand('mceaddbaidumap', function () {
				ed.windowManager.open({
					file: url + '/baidumap.htm',
					width: 720 + ed.getLang('baidumap.delta_width', 0),
					height: 450 + ed.getLang('baidumap.delta_height', 0),
					inline: 1
				}, {
					plugin_url: url,
					some_custom_arg: 'custom arg'
				})
			});
			ed.addButton('baidumap', {
				title: 'baidumap.baidumap_button_desc',
				cmd: 'mceaddbaidumap',
				image: url + '/img/baidumap.gif'
			});
			ed.onNodeChange.add(function (ed, cm, n) {
				cm.setActive('baidumap', n.nodeName == 'baidu')
			})
		},
		getInfo: function () {
			return{
				longname: 'baidumap',
				author: 'QQ:6326420',
				authorurl: 'http://www.ecisp.cn',
				infourl: 'http://www.ecisp.cn',
				version: "1.0"
			}
		}
	});
	tinymce.PluginManager.add('baidumap', tinymce.plugins.baidumap);
})();


