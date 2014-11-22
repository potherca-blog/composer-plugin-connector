<?php

/**
 * Documentation on composer plugins available at https://getcomposer.org/doc/articles/plugins.md
 *
 * Other plugins:
 *  - https://github.com/francoispluchino/composer-asset-plugin/
 *  - https://github.com/mnsami/composer-custom-directory-installer
 */
namespace Potherca\Composer\Plugins;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Potherca\Base\Project;
use Potherca\Composer\Annotations\Script;
use Symfony\Component\Console\ConsoleEvents;

class BasePlugin extends Project implements PluginInterface,  EventSubscriberInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var Composer */
    private $m_oComposer;
    /** @var IOInterface */
    private $m_oIo;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return Composer
     */
    final public function getComposer()
    {
        return $this->m_oComposer;
    }
    /**
     * @param Composer $p_oComposer
     */
    final public function setComposer($p_oComposer)
    {
        $this->m_oComposer = $p_oComposer;
    }

    /**
     * @return IOInterface
     */
    final public function geOIo()
    {
        return $this->m_oIo;
    }

    /**
     * @param IOInterface $p_oIo
     */
    final public function setIo($p_oIo)
    {
        $this->m_oIo = $p_oIo;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    final public function activate(Composer $p_oComposer, IOInterface $p_oIo)
    {
        echo 'CALLED: ' . __METHOD__ . PHP_EOL;
        $this->setComposer($p_oComposer);
        $this->setIo($p_oIo);
    }
    final public static function getSubscribedEvents()
    {
        $oReflectionClass = new \ReflectionClass(static::class);

        $aClasses = self::buildClassList($oReflectionClass);
        AnnotationRegistry::registerAutoloadNamespace('Potherca\\Composer\\Annotations\\');
        $reader = new AnnotationReader();
        //$reader->setDefaultAnnotationNamespace('Potherca\\Composer\\Annotations\\');


        foreach ($aClasses as $t_sClass) {
            if (class_exists($t_sClass)) {
                $oReflectionClass = new \ReflectionClass($t_sClass);
                $classAnnotations = $reader->getClassAnnotations($oReflectionClass);

                foreach ($classAnnotations AS $annotation) {
                    if ($annotation instanceof Script) {
                        echo '>>> ' . $annotation->name;
                        echo '>>> ' . $annotation->event;
                    //} else if ($annotation instanceof \MyCompany\Annotations\Bar) {
                    //    echo $annotation->foo;
                    }
                }
            }
        }
        echo '--- CALLED: ' . __METHOD__ . PHP_EOL;
        echo '--- CLASS: ' . static::class . PHP_EOL;
        $aEventHandlers = array(
            ConsoleEvents::COMMAND => 'consoleCommandEventHandler',
            ConsoleEvents::TERMINATE => 'consoleTerminateEventHandler',
            ConsoleEvents::EXCEPTION => 'consoleExceptionEventHandler',

            InstallerEvents::PRE_DEPENDENCIES_SOLVING => 'installerPreDependenciesSolvingEventHandler',
            InstallerEvents::POST_DEPENDENCIES_SOLVING => 'installerPostDependenciesSolvingEventHandler',

            PluginEvents::PRE_FILE_DOWNLOAD => 'pluginPreFileDownloadEventHandler',
            PluginEvents::COMMAND => 'pluginCommandEventHandler',

            ScriptEvents::PRE_ARCHIVE_CMD => 'scriptPreArchiveCommandEventHandler',
            ScriptEvents::POST_ARCHIVE_CMD => 'scriptPostArchiveCommandEventHandler',
            ScriptEvents::PRE_AUTOLOAD_DUMP => 'scriptPrePostAutoloadDumpEventHandler',
            ScriptEvents::POST_AUTOLOAD_DUMP => 'scriptPostAutoloadDumpEventHandler',
            //ScriptEvents::PRE_CREATE_PROJECT_CMD => 'scriptPreCreateProjectCommandEventHandler',
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'scriptPostCreateProjectCommandEventHandler',
            ScriptEvents::PRE_INSTALL_CMD => 'scriptPreInstallCommandEventHandler',
            ScriptEvents::POST_INSTALL_CMD => 'scriptPostInstallCommandEventHandler',
            ScriptEvents::PRE_PACKAGE_INSTALL => 'scriptPrePackageInstallEventHandler',
            ScriptEvents::POST_PACKAGE_INSTALL => 'scriptPostPackageInstallEventHandler',
            ScriptEvents::PRE_PACKAGE_UNINSTALL => 'scriptPrePackageUninstallEventHandler',
            ScriptEvents::POST_PACKAGE_UNINSTALL => 'scriptPostPackageUninstallEventHandler',
            ScriptEvents::PRE_PACKAGE_UPDATE => 'scriptPrePackageUpdateEventHandler',
            ScriptEvents::POST_PACKAGE_UPDATE => 'scriptPostPackageUpdateEventHandler',
            //ScriptEvents::PRE_ROOT_PACKAGE_INSTALL => 'scriptPreRootPackageInstallEventHandler',
            ScriptEvents::POST_ROOT_PACKAGE_INSTALL => 'scriptPostRootPackageInstallEventHandler',
            ScriptEvents::PRE_STATUS_CMD => 'scriptPreStatusCommandEventHandler',
            ScriptEvents::POST_STATUS_CMD => 'scriptPostStatusCommandEventHandler',
            ScriptEvents::PRE_UPDATE_CMD => 'scriptPreUpdateCommandEventHandler',
            ScriptEvents::POST_UPDATE_CMD => 'scriptPostUpdateCommandEventHandler',
        );

        return $aEventHandlers;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param $oReflectionClass
     *
     * @return array
     */
    private static function buildClassList(\ReflectionClass $oReflectionClass)
    {
        $aClasses = array();

        $sNamespaceName = $oReflectionClass->getNamespaceName();
        $sFileName = $oReflectionClass->getFileName();
        $sPath = dirname($sFileName);
        $sPathLength = strlen($sPath);
        /* Using glob for trolling purposes */
        $aFiles = glob($sPath . '/*{*,/}{/,*}{*,/}{/,*}{*,/}{/,*}*.php', GLOB_BRACE);
        foreach ($aFiles as $t_sFile) {
            $sClassName = substr($t_sFile, $sPathLength, -4);
            $sClassName = str_replace(array(DIRECTORY_SEPARATOR, '\\\\'), '\\', $sClassName);
            $sClass = $sNamespaceName . $sClassName;
            if (in_array($sClass, $aClasses) === false) {
                $aClasses[] = $sClass;
            }
        }

        return $aClasses;
    }

}
