<?php

namespace PhpUnitTestGenerator\Resource;

/**
 * Helper class eases the work with files from /Resources directory
 *
 * @author Michael Doehler
 */
class Helper
{

    /**
     * get template file by given template name, all templates stored in folder Resources/Template
     *
     * @param string $name
     * @return string
     */
    public static function getTemplateFileByName($name)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * parse a given template name with given hash of key-value pairs
     *
     * @param string $name
     * @param array $hash
     * @return string
     */
    public static function getParsedTemplateByNameAndHash($name, array $hash = array())
    {
        $tpl = new \Text_Template(self::getTemplateFileByName($name));
        $tpl->setVar($hash);

        return $tpl->render();
    }

}
