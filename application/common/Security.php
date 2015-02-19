<?php
namespace common;

require_once '/lib/htmlpurifier/HTMLPurifier.auto.php';

/**
 * We will use this class to prevent XSS and other attacks.
 * We can change libraries for this purposes, so separate "security" class is neccessary.
 * I used HtmlPurifier here just for example. I have no real life experience of working with it, but would like to have.
 *
 * @author Sergey Kuzminich <SergeyKuzminich@yandex.ru>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright © 2014, Aldoka
 * @created 27.03.2014 10:01:56     
 */
class Security
{
    /**
     * No html tags should be in a string.
     * @var string STR_NO_HTML
     */
    const STR_NO_HTML = 'STR_NO_HTML';
    /**
     * Rules for articles should be used.
     * @var string STR_HTML
     */
    const STR_HTML = 'STR_HTML';
    /**
     * Integer values, not null.
     * @var string INT
     */
    const INT = 'INT';
    /**
     * Float values, not null.
     * @var string FLOAT
     */
    const FLOAT = 'FLOAT';
    /**
     * Boolean value, not null.
     * @var string BOOL
     */
    const BOOL = 'BOOL';
    /**
     * A list of strings. Using STR_NO_HTML rules.
     * @var string LIST_OF_NO_HTML
     */
    const LIST_OF_NO_HTML = 'LIST_OF_NO_HTML';
    /**
     * A list of integer not null numbers.
     * @var string LIST_OF_INT
     */
    const LIST_OF_INT = 'LIST_OF_INT';
    /**
     * A list of float not null numbers.
     * @var string LIST_OF_FLOAT
     */
    const LIST_OF_FLOAT = 'LIST_OF_FLOAT';
    /**
     * Associative array. Values are strings(STR_NO_HTML).
     * @var string HASH_OF_NO_HTML
     */
    const HASH_OF_NO_HTML = 'HASH_OF_NO_HTML';
    /**
     * No SQL injection should be in a string.
     * @var string STR_NO_INJECTION
     */
    const STR_NO_INJECTION = 'STR_NO_INJECTION';
    /**
     * A list of strings. Values has no SQL Injection.
     * @var string LIST_NO_INJECTION
     */
    const LIST_NO_INJECTION = 'LIST_NO_INJECTION';
    /**
     * Associative array. Keys and values has no SQL Injection.
     * @var string HASH_NO_INJECTION
     */
    const HASH_NO_INJECTION = 'HASH_NO_INJECTION';


    /**
     * This method transform a value to the expected format cleans from xss injection.
     *
     * @param mixed $dirtyValue
     * @param string $cleanType
     * @return mixed
     */
    static public function cleanValue($dirtyValue, $cleanType = null) {
        $config = null;
        // TODO Разные схемы проверки для разных нужд
    	switch ($cleanType) {
    		case 'INT':
    		    $config = HTMLPurifier_Config::createDefault();
    		    break;

    		default:
    			$config = HTMLPurifier_Config::createDefault();
    		    break;
    	}

    	$purifier   = new HTMLPurifier($config);
        $cleanValue = $purifier->purify($dirtyValue);

        return $cleanValue;
    }


}