<?php

class ModelMongo {

    /**
     * Holds the name of the database that uses mongo.
     * 
     * @var String 
     */
    protected $db;

    /**
     * Maintains the collection that is being used
     * 
     * @var MongoCollection
     */
    protected $colh;

    /**
     * Holds the name of the collection
     * 
     * @var String 
     */
    protected $collectionName;

    /**
     * Select the collection and initializes the attributes
     * 
     * @param String $collectionName
     */
    public function __construct($collectionName) {
        $this->db = MongodbFactory::getDBHandler();
        $this->collectionName = $collectionName;
        $this->colh = $this->db->$collectionName;
    }

    /**
     * Find documents from the collection
     * 
     * @param Array $query Search Terms
     * @param Array $fields Fields to retrieve documents
     * @return Array
     */
    public function find($query = null, $fields = null) {
        if (!is_null($query)) {
            if (!is_null($fields)) {
                return $this->colh->find($query, $fields);
            }
            return $this->colh->find($query);
        } else {
            return $this->colh->find();
        }
    }

    /**
     * Gets the number of documents in the collection
     * 
     * @return int
     */
    public function countAll() {
        return $this->colh->count();
    }

    /**
     * Gets all the documents in the collection
     *
     * @return Array
     */
    public function getAllRecords($limit = null) {
        if (is_numeric($limit) && $limit > 0) {
            $cursor = $this->colh->find()->limit($limit);
        } else {
            $cursor = $this->colh->find();
        }
        return iterator_to_array($cursor);
    }

    /**
     * Obtain Documents collection 'orders' ordered by a set of options <br /> 
            * Specified by the parameter $fields
     *
     * @link http://www.php.net/manual/es/mongocursor.sort.php 
     * @param $fields Set options for sorting
     * @return Array
     */
    public function getAllRecordsOrderBy($fields) {
        return iterator_to_array($this->colh->find()->sort($fields));
    }

    /**
     * remove one Document with condition $arrCond, safe = true
     * example: $mdao->safeRemoveDoc( array('username'=>'user1', 'banned'=>1) );
     */

    /**
     * Safely Removes a document collection
     * 
     * @param type $arrCond
     * @return type
     */
    public function safeRemoveDoc($arrCond) {
        return $this->colh->remove($arrCond, array('safe' => true));
    }

    /**
     * Removes a document colución from a specific field.
     * 
     * @param String $fieldName field
     * @param type $val Valur
     * @param array $options Options to remove
     * @return Boolean
     */
    public function removeByField($fieldName, $val, $options) {
        if (isset($options)) {
            return $this->colh->remove(array($fieldName => $val, $options));
        }
        return $this->colh->remove(array($fieldName => $val));
    }

    /**
     * Removes a document collection from the field_id
     * 
     * @param String $mongoId Document identifier
     * @param Array $options Options to remove
     * @return Boolean
     */
    public function removeByMongoId($mongoId, $options) {
        if (isset($options)) {
            return $this->colh->remove(array('_id' => new MongoId($mongoId)), $options);
        }
        return $this->colh->remove(array('_id' => new MongoId($mongoId)));
    }

    /**
     * Inserts a document collection
     *
     * @param Array document
     * @return Boolean Process Status
     */
    public function insert($doc, $safe = false) {
        if ($safe) {
            return $this->colh->insert($doc, array('safe' => true));
        }
        return $this->colh->insert($doc);
    }

    /**
     * Safely update a document from the specified conditions.
     * 
     * @param type $arr
     * @param Array $arrCond
     * @return 
     */
    public function safeUpdateDoc($arr, $arrCond) {
        return $this->colh->update($arrCond, array('$set' => $arr), array('safe' => true, 'multiple' => false));
    }

}