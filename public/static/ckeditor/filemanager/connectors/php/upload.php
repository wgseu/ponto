<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2010 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *	- GNU General Public License Version 2 or later (the "GPL")
 *		http://www.gnu.org/licenses/gpl.html
 *
 *	- GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *		http://www.gnu.org/licenses/lgpl.html
 *
 *	- Mozilla Public License Version 1.1 or later (the "MPL")
 *		http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the "File Uploader" for PHP.
 */

require_once(dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/app.php');

use MZ\System\Permissao;

require('./config.php') ;
require('./util.php') ;
require('./io.php') ;
require('./commands.php') ;
require('./phpcompat.php') ;

function SendError( $number, $text )
{
    SendUploadResults( $number, '', '', $text ) ;
}
if(!logged_employee()->has(Permissao::NOME_ALTERARPAGINAS))
    SendError( 1, 'Você não tem permissão para fazer uploads' );

// Check if this uploader has been enabled.
if ( !$Config['Enabled'] )
    SendError( 1, 'This file uploader is disabled. Please check the "editor/filemanager/connectors/php/config.php" file' ) ;

$sCommand = 'FileUpload' ;

// The file type (from the QueryString, by default 'Image').
$sType = isset( $_GET['Type'] ) ? $_GET['Type'] : 'Image' ;

$sCurrentFolder	= '/';

// Is enabled the upload?
if ( ! IsAllowedCommand( $sCommand ) )
    SendError( 1, 'The ""' . $sCommand . '"" command isn\'t allowed' ) ;

// Check if it is an allowed type.
if ( !IsAllowedType( $sType ) )
    SendError( 1, 'Invalid type specified' ) ;

FileUpload($sType, $sCurrentFolder, $sCommand);
