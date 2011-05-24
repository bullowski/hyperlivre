/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	// Define changes to default configuration here. For example:
	config.language = 'fr';
	config.toolbar = 'CustomToolbar';
    config.toolbar_CustomToolbar =
    [
        ['Source','-','NewPage','Templates','-','Preview','ShowBlocks','Maximize'],
		['Undo','Redo','-','Find','Replace','-','Scayt'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord'],
		['SelectAll','RemoveFormat'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['TextColor','BGColor'],
		'/',
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink','Anchor'],
		['Image','Table','Smiley','SpecialChar','HorizontalRule','PageBreak'],
        
    ];
   
    config.skin = 'kama';
    
	//config.stylesSet = 'my_styles:custom_styles.js';
	//config.filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
    //config.filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
    //config.filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    //config.filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
};
