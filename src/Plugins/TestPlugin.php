<?php

/*
 * Documentation on composer plugins available at https://getcomposer.org/doc/articles/plugins.md 
 *
 * http://stackoverflow.com/questions/21747819/php-composer-how-to-load-and-use-a-plugin-located-outside-of-vendor
 * 
 * Other plugins:
 *  - https://github.com/francoispluchino/composer-asset-plugin/
 *  - https://github.com/mnsami/composer-custom-directory-installer
 */
namespace Potherca\Composer\Plugins;

class TestPlugin extends \Potherca\Base\Project implements \Composer\Plugin\PluginInterface,  \Composer\EventDispatcher\EventSubscriberInterface
{
    private $composer;
    private $io;

    final public function activate(\Composer\Composer $composer, \Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    
    final public static function getSubscribedEvents()
    {
        return array(
            \Composer\Plugin\PluginEvents::PRE_FILE_DOWNLOAD => 'preFileDownloadHandler',
            \Composer\Plugin\PluginEvents::COMMAND => 'commandHandler',
        );
    }
    
    final public function preFileDownloadHandler(\Composer\Plugin\PreFileDownloadEvent $event)
    {
        
    }

    final public function commandHandler(\Composer\Plugin\CommandEvent $event)
    {
        echo 'PLUGIN EVENT: ' . $event->getCommandName();
    }
/* AVAILABLE AS CLASS CONST IN https://github.com/composer/composer/blob/master/src/Composer/Script/ScriptEvents.php

    pre-install-cmd             : occurs before the install command is executed.
    post-install-cmd            : occurs after the install command is executed.
    pre-update-cmd              : occurs before the update command is executed.
    post-update-cmd             : occurs after the update command is executed.
    pre-status-cmd              : occurs before the status command is executed.
    post-status-cmd             : occurs after the status command is executed.
    pre-dependencies-solving    : occurs before the dependencies are resolved.
    post-dependencies-solving   : occurs after the dependencies are resolved.
    pre-package-install         : occurs before a package is installed.
    post-package-install        : occurs after a package is installed.
    pre-package-update          : occurs before a package is updated.
    post-package-update         : occurs after a package is updated.
    pre-package-uninstall       : occurs before a package has been uninstalled.
    post-package-uninstall      : occurs after a package has been uninstalled.
    pre-autoload-dump           : occurs before the autoloader is dumped, either during install/update, or via the dump-autoload command.
    post-autoload-dump          : occurs after the autoloader is dumped, either during install/update, or via the dump-autoload command.
    post-root-package-install   : occurs after the root package has been installed, during the create-project command.
    post-create-project-cmd     : occurs after the create-project command is executed.
    pre-archive-cmd             : occurs before the archive command is executed.
    post-archive-cmd            : occurs after the archive command is executed.
*/

}
