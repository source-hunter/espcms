(function() {
	tinymce.PluginManager.requireLangPack('movie');
	tinymce.create('tinymce.plugins.movie', {
		init: function(ed, url) {
			ed.addCommand('mceaddmovie', function() {
				ed.windowManager.open({
					file: url + '/movie.htm',
					width: 680 + ed.getLang('movie.delta_width', 0),
					height: 160 + ed.getLang('movie.delta_height', 0),
					inline: 1
				}, {
					plugin_url: url,
					some_custom_arg: 'custom arg'
				})
			});
			ed.addButton('movie', {
				title: 'movie.movie_button_desc',
				cmd: 'mceaddmovie',
				image: url + '/img/movie.gif'
			});
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('movie', n.nodeName == 'movie')
			})
		},
		getInfo: function() {
			return{
				longname: 'movie',
				author: 'QQ:6326420',
				authorurl: 'http://www.ecisp.cn',
				infourl: 'http://www.ecisp.cn',
				version: "1.0"
			}
		}
	});
	tinymce.PluginManager.add('movie', tinymce.plugins.movie)
})();