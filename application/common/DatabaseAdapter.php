<?php
namespace common;

/**
 * Simple and naked PDO Mysql Database adapter.
 * In a real project we should implement adapters in case we will want to switch database.
 * For a big project with a big team we should create separate classes for each statement type to exclude human element .
 * If we use transactional database, we should care how to deal with it.
 * Also we should implement protection from table blocking.
 * Often it is usefull to implement connection pool or persistent database connection.
 * We can use table metadata for field type detecting. 
 * And so on. All this is a theme for a separate discussion.
 *
 * @author Sergey Kuzminich <SergeyKuzminich@yandex.ru>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright © 2014, Aldoka
 * @created 27.03.2014 16:06:41
 */
class DatabaseAdapter
{
    /**
     * @var \PDO connection
     */
    private $_connection = null;
    /**
     * @var string ERROR_MESSAGE
     */
    const ERROR_MESSAGE = 'Извините, произошла ошибка при внесении изменений.';  


    /**
     * Constructor creates connection to database.
     * @throws \ErrorException
     */
    public function __construct () {
        try {
            // TODO login and password sohuld be in "ini" file.
            $this->_connection = new PDO('mysql:host=127.0.0.1;dbname=articles', 'root', 'seeTievu',array(PDO::ATTR_PERSISTENT => true));
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // TODO we should use some logging system
            throw new \ErrorException(self::ERROR_MESSAGE);
        }
    }


    /**
     * Inserts a row to the table.
     *
     * @param string $tableName name of the table
     * @param array $bind array of values to bind into the query. Be sure you checked values with Security classs!
     * @throws \ErrorException
     * @return boolean
     */
    public function insert($tableName, array $bind) {
        $result = null;
        try {
            $cleanTableName    = Security::cleanValue($tableName, Security::STR_NO_INJECTION);
            $cleanBind         = Security::cleanValue($bind, Security::HASH_NO_INJECTION);
            $cleanColumnNames = array_keys($bind);

            $sql = 'INSERT INTO `'.$cleanTableName.'`
                ('.implode(',', $cleanColumnNames).')
                VALUES (:'.implode(',:', $cleanBind).')';

            $statement = $this->_connection->prepare($sql);
            $result = $statement->execute();

        } catch (\PDOException $e) {
            // TODO we should use some logging system
            throw new \ErrorException(self::ERROR_MESSAGE);
        }

        return $result;
    }


    /**
     * Updates a row in the table.
     *
     * @param string $tableName
     * @param array $bind
     * @param string $where
     * @throws \ErrorException
     * @return boolean
     */
    public function update($tableName, array $bind, $where) {
    	$result = null;
    	try {
    	    $cleanTableName = Security::cleanValue($tableName, Security::STR_NO_INJECTION);
    	    $cleanBind      = Security::cleanValue($bind, Security::HASH_NO_INJECTION);
    	    // TODO $where expressions should be cleaned by own rules(subqueries are allowed). 
    	    $cleanWhere = Security::cleanValue($where, Security::STR_NO_INJECTION);
    
    	    $sql = 'UPDATE `'.$cleanTableName.'` SET ';
    	    $setExpressions = array();
    	    foreach ($cleanBind as $columnName => $value) {
    	    	$setExpressions[] = $columnName." = '".$value."'";
    	    }

    	    $sql .= implode(',', $setExpressions);
    	    $sql .= ' '.$cleanWhere;

    	    $statement = $this->_connection->prepare($sql);
    	    $result    = $statement->execute();
    	} catch (\PDOException $e) {
    	    // TODO we should use some logging system
    	    throw new \ErrorException(self::ERROR_MESSAGE);
    	}

    	return $result;
    }


    /**
     * Delete rows from a table.
     * 
     * @param string $tableName
     * @param string $where
     * @throws \ErrorException
     * @return boolean
     */
    public function delete($tableName, $where) {
        $result = null;
        try {
            $cleanTableName = Security::cleanValue($tableName, Security::STR_NO_INJECTION);
    	    // TODO $where expressions should be cleaned by own rules(subqueries are allowed). 
            $cleanWhere = Security::cleanValue($where, Security::STR_NO_INJECTION);

            $sql = 'DELETE FROM `'.$cleanTableName.'` WHERE '.$cleanWhere;

            $statement = $this->_connection->prepare($sql);
            $result    = $statement->execute();
        } catch (\PDOException $e) {
            // TODO we should use some logging system
            throw new \ErrorException(self::ERROR_MESSAGE);
        }

        return $result;
    }


    /**
     * Select row(s) from a table.
     *
     * @param string $tableName
     * @param string $where
     * @param string $joinTables
     * @param array $columns
     * @throws \ErrorException
     * @return multitype:
     */
    function select($tableName, $where, $joinTables = array(), $columns = array()) {
    	$result = null;
    	try {
    	    $cleanTableName = Security::cleanValue($tableName, Security::STR_NO_INJECTION);
    	    // TODO $where expressions should be cleaned by own rules(subqueries are allowed).
    	    $cleanWhere   = Security::cleanValue($where, Security::STR_NO_INJECTION);
    	    $cleanColumns = Security::cleanValue($bind, Security::LIST_NO_INJECTION);

    	    $sql = 'SELECT '.implode(',', $cleanColumns).' 
    	            FROM '.$cleanTableName.' 
    	            WHERE '.$cleanWhere;

    	    $statement = $this->_connection->prepare($sql);
    	    $result    = $statement->fetchAll(\PDO::FETCH_ASSOC);
    	} catch (\PDOException $e) {
    	    // TODO we should use some logging system
    	    throw new \ErrorException(self::ERROR_MESSAGE);
    	}

    	return $result;
    }
}