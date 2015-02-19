<?php
namespace common;

/**
 * The classic Registry Singleton. It is inheritable.
 * Actually, when singleton contains some global information - it hides dependencies and usage of this information. It smells bad.
 * But it has no serious consequences right here right now, so I used it =)  
 *
 * @author Sergey Kuzminich <SergeyKuzminich@yandex.ru>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright © 2014, Aldoka
 * @created 27.03.2014 23:02:34
 */
class Registry
{
    private static $_instances = NULL;


    private function __construct() {
        // Singleton is not constructable
    }


    private function __clone() {
    	// To be sure that nobody can clone it and use a different object.
    }


    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }


    /**
     * An instance creator method with late static binding(for inheritance).
     */
    public static function getInstance() {
        $aClass = get_called_class(); // late static bind class name
        if (!isset(self::$_instances[$aClass])) {
            self::$_instances[$aClass] = new static;
        }
        return self::$_instances[$aClass];
    }


}