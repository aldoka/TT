<?php
namespace domain;

use common\Security;
use common\DatabaseAdapter;
/**
 * Active Record pattern implementation for the author entity.
 * 
 * @author Sergey Kuzminich <SergeyKuzminich@yandex.ru>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright © 2014, Aldoka
 * @created 26.03.2014 10:04:06
 */
class AuthorAR
{
    /**
     * @var integer Id
     */
    protected $_id;
    /**
     * @var String FIO
     */
    protected $_fio;
    /**
     * @var String tableName
     */
    protected static final $_tableName;


	/**
     * @return the $_id
     */
    public function getId () {
        return $this->_id;
    }


	/**
     * @return the $_fio
     */
    public function getFio () {
        return $this->_fio;
    }


	/**
     * @param number $_id
     */
    protected function setId ($_id) {
        $this->_id = Security::cleanValue($_id, Security::INT);
    }


	/**
     * @param string $_fio
     */
    protected function setFio ($_fio) {
        $this->_fio = Security::cleanValue($_fio, Security::STR_NO_HTML);
    }


    /**
     * CRUD method.
     *
     * @return boolean
     */
    public function insert() {
        $bind = array(
        	'fio' => Security::cleanValue($this->getFio(), Security::STR_NO_HTML)
        );

        $db     = new DatabaseAdapter();
        $result = $db->insert(self::$_tableName, $bind);

        return $result;
    }


    /**
     * CRUD method.
     *
     * @return boolean
     */
    public function update() {
        $bind = array(
            'fio' => Security::cleanValue($this->getFio(), Security::STR_NO_HTML)
        );

        $db     = new DatabaseAdapter();
        $result = $db->update(self::$_tableName, $bind, 'id='.Security::cleanValue($this->getId(), Security::INT));

        return $result;
    }


    /**
     * CRUD method.
     *
     * @return boolean
     */
    public function delete() {
        $db     = new DatabaseAdapter();
        $result = $db->delete(self::$_tableName, 'id='.Security::cleanValue($this->getId(), Security::INT));

        return $result;
    }


    /**
     * A finder method
     * 
     * @param int $id
     */
    public function findById(int $id) {
   	    $db        = new DatabaseAdapter();
   	    $data = $db->select(self::$_tableName, 'id='.Security::cleanValue($id, Security::INT));

   	    $this->map($data);
    }


    private function map($data) {
        if (isset($data['id']) === true) {
            $this->setId(Security::cleanValue($data['id'], Security::INT));
            $this->setFio(Security::cleanValue($data['fio'], Security::STR_NO_HTML));
        } else {
            throw new \ErrorException("Невозможно загрузить автора с id ".$data['id']);
        }
    }


}