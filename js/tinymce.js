
	// https://www.tiny.cloud/docs/configure/editor-appearance/
	// https://www.tiny.cloud/docs/general-configuration-guide/basic-setup/
	tinymce.init({
		selector: '.tinyMCE',
		// menubar: true,
		plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code ',
			'insertdatetime  table paste code help wordcount'
		],
		toolbar:
		' undo redo |  ' +
		' styleselect |  ' +
		' bold italic | ' +
		' removeformat | ' +
		' code',
		content_css: [
			'//fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300;700&family=Open+Sans:ital,wght@0,300;0,700;1,300;1,700&display=swap',
			// 'css/tiny_styles.css',
			'../css/styles.css'
		]
	});
