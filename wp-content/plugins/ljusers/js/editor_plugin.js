(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('ljusers');

	tinymce.create('tinymce.plugins.LjusersPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceLjusers');


			// ***** livejournal.com
			ed.addCommand('mceLjusers', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[ljuser]' + content + '[/ljuser]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});
			
			ed.addCommand('mceLjcomm', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[ljcomm]' + content + '[/ljcomm]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});


			// ***** liveinternet.ru
			ed.addCommand('mceLiruman', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[liruman]' + content + '[/liruman]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});

			ed.addCommand('mceLirugirl', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[lirugirl]' + content + '[/lirugirl]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});


			// ***** livejournal
			// Register ljusers button
			ed.addButton('ljusers', {
				title : 'LJ-user',
				cmd : 'mceLjusers',
				image : url + '/img/userinfo.gif'
			});
			
			// Register ljcomm button
			ed.addButton('ljcomm', {
				title : 'LJ-community',
				cmd : 'mceLjcomm',
				image : url + '/img/community.gif'
			});
			

			// ***** liveinternet
			ed.addButton('liruman', {
				title : 'Liveinternet user (man)',
				cmd : 'mceLiruman',
				image : url + '/img/liru_man.gif'
			});

			ed.addButton('lirugirl', {
				title : 'Liveinternet user (girl)',
				cmd : 'mceLirugirl',
				image : url + '/img/liru_girl.gif'
			});

		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'Ljusers plugin',
				author : 'Some author',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/ljusers',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('ljusers', tinymce.plugins.LjusersPlugin);
})();
