<?php

  function com_install()
  {
	jimport('joomla.filesystem.path');
	jimport( 'joomla.filesystem.folder' );
	$source=JPATH_ADMINISTRATOR . "/components/com_joominajszarinpalzg/js/pm_zarinpalzg/";
    $destination=JPATH_SITE . "/components/com_jshopping/payments/pm_zarinpalzg/";
	$path=JPATH_ADMINISTRATOR . "/components/com_joominajszarinpalzg/";
	Folder($source , $destination); 
	//delete($path);
	}
function Folder($source , $destination){
    if(JFolder::exists($destination)){
        $failure = '[Copy] An error has occurred when copying component file between '. $source.' and '.$destination. ' folder already exsist ';
        JError::raiseWarning(E_ERROR, $failure, '');
        return false;
    }else {
        if(JFolder::create($destination, '755')){
            JFolder::copy($source, $destination,'', TRUE);
            return true;
        }else{
            $failure = '[Copy] An error has occurred when copying component file between '. $source.' and '.$destination. ' please check your folder permission';
            JError::raiseWarning(E_ERROR, $failure, '');
            }
        }
    }	
function delete($path)
{
        // Sanity check
        if (!$path) {
                // Bad programmer! Bad Bad programmer!
                JError::raiseWarning(500, 'JFolder::delete: ' . JText::_('Attempt to delete base directory') );
                return false;
        }
 
        // Initialize variables
        jimport('joomla.client.helper');
        $ftpOptions = JClientHelper::getCredentials('ftp');
 
        // Check to make sure the path valid and clean
        $path = JPath::clean($path);
 
        // Is this really a folder?
        if (!is_dir($path)) {
                JError::raiseWarning(21, 'JFolder::delete: ' . JText::_('Path is not a folder'), 'Path: ' . $path);
                return false;
        }
 
        // Remove all the files in folder if they exist
        $files = JFolder::files($path, '.', false, true, array());
        if (!empty($files)) {
                jimport('joomla.filesystem.file');
                if (JFile::delete($files) !== true) {
                        // JFile::delete throws an error
                        return false;
                }
        }
 
        // Remove sub-folders of folder
        $folders = JFolder::folders($path, '.', false, true, array());
        foreach ($folders as $folder) {
                if (is_link($folder)) {
                        // Don't descend into linked directories, just delete the link.
                        jimport('joomla.filesystem.file');
                        if (JFile::delete($folder) !== true) {
                                // JFile::delete throws an error
                                return false;
                        }
                } elseif (JFolder::delete($folder) !== true) {
                        // JFolder::delete throws an error
                        return false;
                }
        }
 
        if ($ftpOptions['enabled'] == 1) {
                // Connect the FTP client
                jimport('joomla.client.ftp');
                $ftp = &JFTP::getInstance(
                        $ftpOptions['host'], $ftpOptions['port'], null,
                        $ftpOptions['user'], $ftpOptions['pass']
                );
        }
 
        // In case of restricted permissions we zap it one way or the other
        // as long as the owner is either the webserver or the ftp
        if (@rmdir($path)) {
                $ret = true;
        } elseif ($ftpOptions['enabled'] == 1) {
                // Translate path and delete
                $path = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $path), '/');
                // FTP connector throws an error
                $ret = $ftp->delete($path);
        } else {
                JError::raiseWarning(
                        'SOME_ERROR_CODE',
                        'JFolder::delete: ' . JText::_('Could not delete folder'),
                        'Path: ' . $path
                );
                $ret = false;
        }
        return $ret;
}	
?>
