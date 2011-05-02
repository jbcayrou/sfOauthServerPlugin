<?php echo sprintf('<?xml version="1.0" encoding="%s" ?>', sfConfig::get('sf_charset', 'UTF-8'))."\n" ?>
<error type="<?php echo $type ?>" message="<?php echo  htmlspecialchars($message, ENT_QUOTES, sfConfig::get('sf_charset', 'UTF-8')) ?>" />

