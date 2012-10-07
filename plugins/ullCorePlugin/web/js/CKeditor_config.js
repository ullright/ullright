/*
Default CKEditor config for ullright
*/

CKEDITOR.editorConfig = function( config )
{
	// Set a content area body css class
	config.bodyClass = 'ull_cms_content';	
	
	// Configure Stylesheets for edit area
	config.contentsCss = [ '/ullCoreThemeNGPlugin/css/common.css', '/css/custom.css' ];
	
	// Configure styles (Custom drop-down for css styles)
	config.stylesSet = 'my_styles:/ullCorePlugin/js/CKeditor_styles.js';	
	
	// Configure toolbar
	config.toolbar = 'MyToolbar';
	config.toolbar_MyToolbar =
	[
		{ name: 'clipboard', items : [ 'Cut','Copy','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule' ] },
                '/',
		{ name: 'styles', items : [ 'Styles','Format' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent'] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] },
		{ name: 'document', items : [ 'Source' ] }
	];
	

  /*
   *  Word import
   */
  
  // Force word import cleanup on every paste, not only word
  // From http://stackoverflow.com/questions/5227140/ckeditor-use-pastefromword-filtering-on-all-pasted-content/8379364#8379364
  CKEDITOR.on('instanceReady', function(ev) {
    ev.editor.on('paste', function(evt) {    
        evt.data['html'] = '<!--class="Mso"-->'+evt.data['html'];
    }, null, null, 9);
  });
  
  config.pasteFromWordPromptCleanup = false;
  config.pasteFromWordRemoveFontStyles = true;
  config.pasteFromWordRemoveStyles = true;
  
  /*
   * Plugin management
   */
  
  // Remove dom elements path from the bottom status bar
  config.removePlugins = 'elementspath';  
  
  // Add shy button (Conditional hyphen) 
  config.extraPlugins = 'shybutton';
  
  // Shy button key binding
  config.keystrokes =config.keystrokes.concat([ [ CKEDITOR.CTRL + 109 /*-*/,
                                                  'insertshy' ] ]); 
	
	
	
	
  // Remove bottom status bar completely
//	config.resize_enabled = false;	
	
//	config.toolbar_Full =
//		[
//			{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
//			{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
//			{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
//			{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
//		 
//		         'HiddenField' ] },
//			'/',
//			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
//			{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-
//		 
//		        ','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
//			{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
//			{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
//			'/',
//			{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
//			{ name: 'colors', items : [ 'TextColor','BGColor' ] },
//			{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
//		];	

	
/* Indent/Outdent via tab is not supported at the moment */	
//	config.keystrokes =
//	[
//	    [ CKEDITOR.ALT + 121 /*F10*/, 'toolbarFocus' ],
//	    [ CKEDITOR.ALT + 122 /*F11*/, 'elementsPathFocus' ],
//
//	    [ CKEDITOR.SHIFT + 121 /*F10*/, 'contextMenu' ],
//
//	    [ CKEDITOR.CTRL + 90 /*Z*/, 'undo' ],
//	    [ CKEDITOR.CTRL + 89 /*Y*/, 'redo' ],
//	    [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 90 /*Z*/, 'redo' ],
//
//	    [ CKEDITOR.CTRL + 76 /*L*/, 'link' ],
//
//	    [ CKEDITOR.CTRL + 66 /*B*/, 'bold' ],
//	    [ CKEDITOR.CTRL + 73 /*I*/, 'italic' ],
//	    [ CKEDITOR.CTRL + 85 /*U*/, 'underline' ],
//
//	    [ CKEDITOR.ALT + 109 /*-*/, 'toolbarCollapse' ]
//	    
//	    [ 09 /*TAB*/, 'indent' ],
//	    [ CKEDITOR.SHIFT + 09 /*SHIFT+TAB*/, 'outdent' ]	    
//	];	
	
	

	
	
	
};
