/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**/
CKEDITOR.addStylesSet( 'my_styles',
[
    // Inline Styles
    { 'name' : 'Titular Tipo 1', 'element' : 'div', 'attributes' : { 'class' : 'tituloEstilo_1' } },
    { 'name' : 'Titular Tipo 2', 'element' : 'div', 'attributes' : { 'class' : 'tituloEstilo_2' } },
    { 'name' : 'Texto Tipo 1', 'element' : 'div', 'attributes' : { 'class' : 'textoEstilo_1' } }
]);
/** /
CKEDITOR.config.toolbar_Full =
[
    ['Source','-','Save','NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
    '/',
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks','-','About']
];
/**/
CKEDITOR.config.toolbar_Full =
[
    ['Source','-'/*,'Save'*/,'NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
    '/',
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks','-','About']
];



CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.stylesCombo_stylesSet = 'my_styles';
	config.filebrowserBrowseUrl= CKEDITOR_BASEPATH+'../ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl= CKEDITOR_BASEPATH+'../ckfinder/ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl= CKEDITOR_BASEPATH+'../ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl= CKEDITOR_BASEPATH+'../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl= CKEDITOR_BASEPATH+'../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl= CKEDITOR_BASEPATH+'../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	//config.contentsCss = [CKEDITOR_BASEPATH + '../css/modulos.custom.css'];




};

