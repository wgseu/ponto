/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
var filemanager = '/static/ckeditor/filemanager/';
var browser = filemanager + 'browser/default/browser.html';
var connector = filemanager + 'connectors/php/connector.php';
var upload = filemanager + 'connectors/php/upload.php';

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here. For example:
  config.language = 'pt-br';
  config.filebrowserBrowseUrl      = browser + '?Connector='  + connector;
  config.filebrowserImageBrowseUrl = browser + '?Type=Image&Connector=' + connector;
  config.filebrowserFlashBrowseUrl = browser + '?Type=Flash&Connector=' + connector;
  config.filebrowserUploadUrl      = upload  + '?Type=File';
  config.filebrowserImageUploadUrl = upload  + '?Type=Image';
  config.filebrowserFlashUploadUrl = upload  + '?Type=Flash';
};
