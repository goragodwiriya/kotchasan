<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 * - GNU General Public License Version 2 or later (the "GPL")
 *  http://www.gnu.org/licenses/gpl.html
 *
 * - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *  http://www.gnu.org/licenses/lgpl.html
 *
 * - Mozilla Public License Version 1.1 or later (the "MPL")
 *  http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the File Manager Connector for PHP.
 */
/**
 * @param $sBasePath
 * @param $sFolder
 */
function CombinePaths($sBasePath, $sFolder)
{
    return RemoveFromEnd($sBasePath, '/').'/'.RemoveFromStart($sFolder, '/');
}
/**
 * @param $resourceType
 * @param $sCommand
 * @return mixed
 */
function GetResourceTypePath($resourceType, $sCommand)
{
    global $config;
    if ($sCommand == "QuickUpload") {
        return $config['QuickUploadPath'][$resourceType];
    } else {
        return $config['FileTypesPath'][$resourceType];
    }

}
/**
 * @param $resourceType
 * @param $sCommand
 * @return mixed
 */
function GetResourceTypeDirectory($resourceType, $sCommand)
{
    global $config;
    if ($sCommand == "QuickUpload") {
        if (strlen($config['QuickUploadAbsolutePath'][$resourceType]) > 0) {
            return $config['QuickUploadAbsolutePath'][$resourceType];
        }

        // Map the "UserFiles" path to a local directory.
        return Server_MapPath($config['QuickUploadPath'][$resourceType]);
    } else {
        if (strlen($config['FileTypesAbsolutePath'][$resourceType]) > 0) {
            return $config['FileTypesAbsolutePath'][$resourceType];
        }

        // Map the "UserFiles" path to a local directory.
        return Server_MapPath($config['FileTypesPath'][$resourceType]);
    }
}
/**
 * @param $resourceType
 * @param $folderPath
 * @param $sCommand
 */
function GetUrlFromPath($resourceType, $folderPath, $sCommand)
{
    return CombinePaths(GetResourceTypePath($resourceType, $sCommand), $folderPath);
}
/**
 * @param $fileName
 */
function RemoveExtension($fileName)
{
    return substr($fileName, 0, strrpos($fileName, '.'));
}
/**
 * @param $resourceType
 * @param $folderPath
 * @param $sCommand
 */
function ServerMapFolder($resourceType, $folderPath, $sCommand)
{
    // Get the resource type directory.
    $sResourceTypePath = GetResourceTypeDirectory($resourceType, $sCommand);
    // Ensure that the directory exists.
    $sErrorMsg = CreateServerFolder($sResourceTypePath);
    if ($sErrorMsg != '') {
        SendError(1, "Error creating folder \"{$sResourceTypePath}\" ({$sErrorMsg})");
    }

    // Return the resource type directory combined with the required path.
    return CombinePaths($sResourceTypePath, $folderPath);
}
/**
 * @param $folderPath
 */
function GetParentFolder($folderPath)
{
    $sPattern = "-[/\\\\][^/\\\\]+[/\\\\]?$-";
    return preg_replace($sPattern, '', $folderPath);
}
/**
 * @param $folderPath
 * @param $lastFolder
 * @return mixed
 */
function CreateServerFolder($folderPath, $lastFolder = null)
{
    global $config;
    $sParent = GetParentFolder($folderPath);
    // Ensure the folder path has no double-slashes, or mkdir may fail on certain platforms
    while (strpos($folderPath, '//') !== false) {
        $folderPath = str_replace('//', '/', $folderPath);
    }
    // Check if the parent exists, or create it.
    if (!empty($sParent) && !file_exists($sParent)) {
        //prevents agains infinite loop when we can't create root folder
        if (!is_null($lastFolder) && $lastFolder == $sParent) {
            return "Can't create $folderPath directory";
        }
        $sErrorMsg = CreateServerFolder($sParent, $folderPath);
        if ($sErrorMsg != '') {
            return $sErrorMsg;
        }

    }
    if (!file_exists($folderPath)) {
        // Turn off all error reporting.
        error_reporting(0);
        $php_errormsg = '';
        // Enable error tracking to catch the error.
        ini_set('track_errors', '1');
        if (isset($config['ChmodOnFolderCreate']) && !$config['ChmodOnFolderCreate']) {
            mkdir($folderPath);
        } else {
            $permissions = 0777;
            if (isset($config['ChmodOnFolderCreate'])) {
                $permissions = $config['ChmodOnFolderCreate'];
            }
            // To create the folder with 0777 permissions, we need to set umask to zero.
            $oldumask = umask(0);
            mkdir($folderPath, $permissions);
            umask($oldumask);
        }
        $sErrorMsg = $php_errormsg;
        // Restore the configurations.
        ini_restore('track_errors');
        ini_restore('error_reporting');
        return $sErrorMsg;
    } else {
        return '';
    }

}
function GetRootPath()
{
    if (!isset($_SERVER)) {
        global $_SERVER;
    }
    $sRealPath = realpath('./');
    // #2124 ensure that no slash is at the end
    $sRealPath = rtrim($sRealPath, "\\/");
    $sSelfPath = $_SERVER['PHP_SELF'];
    $sSelfPath = substr($sSelfPath, 0, strrpos($sSelfPath, '/'));
    $sSelfPath = str_replace('/', DIRECTORY_SEPARATOR, $sSelfPath);
    $position = strpos($sRealPath, $sSelfPath);
    // This can check only that this script isn't run from a virtual dir
    // But it avoids the problems that arise if it isn't checked
    if ($position == false || $position != strlen($sRealPath) - strlen($sSelfPath)) {
        SendError(1, 'Sorry, can\'t map "UserFilesPath" to a physical path. You must set the "UserFilesAbsolutePath" value in "editor/filemanager/connectors/php/config.php".');
    }

    return substr($sRealPath, 0, $position);
}
// Emulate the asp Server.mapPath function.
// given an url path return the physical directory that it corresponds to
/**
 * @param $path
 * @return mixed
 */
function Server_MapPath($path)
{
    // This function is available only for Apache
    if (function_exists('apache_lookup_uri')) {
        $info = apache_lookup_uri($path);
        return $info->filename.$info->path_info;
    }
    // This isn't correct but for the moment there's no other solution
    // If this script is under a virtual directory or symlink it will detect the problem and stop
    return GetRootPath().$path;
}
/**
 * @param $sExtension
 * @param $resourceType
 */
function IsAllowedExt($sExtension, $resourceType)
{
    global $config;
    // Get the allowed and denied extensions arrays.
    $arAllowed = $config['AllowedExtensions'][$resourceType];
    $arDenied = $config['DeniedExtensions'][$resourceType];
    if (count($arAllowed) > 0 && !in_array($sExtension, $arAllowed)) {
        return false;
    }

    if (count($arDenied) > 0 && in_array($sExtension, $arDenied)) {
        return false;
    }

    return true;
}
/**
 * @param $resourceType
 */
function IsAllowedType($resourceType)
{
    global $config;
    if (!in_array($resourceType, $config['ConfigAllowedTypes'])) {
        return false;
    }

    return true;
}
/**
 * @param $sCommand
 */
function IsAllowedCommand($sCommand)
{
    global $config;
    if (!in_array($sCommand, $config['ConfigAllowedCommands'])) {
        return false;
    }

    return true;
}
/**
 * @return mixed
 */
function GetCurrentFolder()
{
    if (!isset($_GET)) {
        global $_GET;
    }
    $sCurrentFolder = isset($_GET['CurrentFolder']) ? $_GET['CurrentFolder'] : '/';
    // Check the current folder syntax (must begin and start with a slash).
    if (!preg_match('|/$|', $sCurrentFolder)) {
        $sCurrentFolder .= '/';
    }

    if (strpos($sCurrentFolder, '/') !== 0) {
        $sCurrentFolder = '/'.$sCurrentFolder;
    }

    // Ensure the folder path has no double-slashes
    while (strpos($sCurrentFolder, '//') !== false) {
        $sCurrentFolder = str_replace('//', '/', $sCurrentFolder);
    }
    // Check for invalid folder paths (..)
    if (strpos($sCurrentFolder, '..') || strpos($sCurrentFolder, "\\")) {
        SendError(102, '');
    }

    if (preg_match(",(/\.)|[[:cntrl:]]|(//)|(\\\\)|([\:\*\?\"\<\>\|]),", $sCurrentFolder)) {
        SendError(102, '');
    }

    return $sCurrentFolder;
}
// Do a cleanup of the folder name to avoid possible problems
/**
 * @param $sNewFolderName
 * @return mixed
 */
function SanitizeFolderName($sNewFolderName)
{
    $sNewFolderName = stripslashes($sNewFolderName);
    // Remove . \ / | : ? * " < >
    $sNewFolderName = preg_replace('/\\.|\\\\|\\/|\\||\\:|\\?|\\*|"|<|>|[[:cntrl:]]/', '_', $sNewFolderName);
    return $sNewFolderName;
}
// Do a cleanup of the file name to avoid possible problems
/**
 * @param $sNewFileName
 * @return mixed
 */
function SanitizeFileName($sNewFileName)
{
    global $config;
    $sNewFileName = stripslashes($sNewFileName);
    // Replace dots in the name with underscores (only one dot can be there... security issue).
    if ($config['ForceSingleExtension']) {
        $sNewFileName = preg_replace('/\\.(?![^.]*$)/', '_', $sNewFileName);
    }

    // Remove \ / | : ? * " < >
    $sNewFileName = preg_replace('/\\\\|\\/|\\||\\:|\\?|\\*|"|<|>|[[:cntrl:]]/', '_', $sNewFileName);
    return $sNewFileName;
}
// This is the function that sends the results of the uploading process.
/**
 * @param $errorNumber
 * @param $fileUrl
 * @param $fileName
 * @param $customMsg
 */
function SendUploadResults($errorNumber, $fileUrl = '', $fileName = '', $customMsg = '')
{
    // Minified version of the document.domain automatic fix script (#1919).
    // The original script can be found at _dev/domain_fix_template.js
    echo <<< EOF
<script type="text/javascript">
(function(){var d=document.domain;while (true){try{var A=window.parent.document.domain;break;}catch(e) {};d=d.replace(/.*?(?:\.|$)/,'');if (d.length==0) break;try{document.domain=d;}catch (e){break;}}})();
EOF;
    if ($errorNumber && $errorNumber != 201) {
        $fileUrl = "";
        $fileName = "";
    }
    $rpl = array('\\' => '\\\\', '"' => '\\"');
    echo 'window.parent.OnUploadCompleted('.$errorNumber.',"'.strtr($fileUrl, $rpl).'","'.strtr($fileName, $rpl).'", "'.strtr($customMsg, $rpl).'") ;';
    echo '</script>';
    exit;
}
// This is the function that sends the results of the uploading process to CKEditor.
/**
 * @param $errorNumber
 * @param $CKECallback
 * @param $fileUrl
 * @param $fileName
 * @param $customMsg
 */
function SendCKEditorResults($errorNumber, $CKECallback, $fileUrl, $fileName, $customMsg = '')
{
    // Minified version of the document.domain automatic fix script (#1919).
    // The original script can be found at _dev/domain_fix_template.js
    echo <<< EOF
<script type="text/javascript">
(function(){var d=document.domain;while (true){try{var A=window.parent.document.domain;break;}catch(e) {};d=d.replace(/.*?(?:\.|$)/,'');if (d.length==0) break;try{document.domain=d;}catch (e){break;}}})();
EOF;
    if ($errorNumber && $errorNumber != 201) {
        $fileUrl = "";
        $fileName = "";
    }
    $msg = "";
    switch ($errorNumber) {
        case 0:
            $msg = "Upload successful";
            break;
        case 1: // Custom error.
            $msg = $customMsg;
            break;
        case 201:
            $msg = 'A file with the same name is already available. The uploaded file has been renamed to "'.$fileName.'"';
            break;
        case 202:
            $msg = 'Invalid file';
            break;
        case 900:
            $msg = 'Sorry! Your disk space is not enough to upload';
            break;
        default:
            $msg = 'Error on file upload. Error number: '+$errorNumber;
            break;
    }
    $rpl = array('\\' => '\\\\', '"' => '\\"');
    echo 'window.parent.CKEDITOR.tools.callFunction("'.$CKECallback.'","'.strtr($fileUrl, $rpl).'", "'.strtr($msg, $rpl).'");';
    echo '</script>';
}
