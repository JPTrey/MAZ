<?php

/**
 * streamlines CRUD functionality when interfacing with a database
 */
class MY_Model extends CI_Model {
    const DB_TABLE = 'null';        // override when extending
    const DB_TABLE_PK = 'null';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * creates record
     */
    private function insert() {
        $this->db->insert($this::DB_TABLE, $this);
        $this->{$this::DB_TABLE_PK} = $this->db->insert_id();
    }
    
    /**
     * updates record when provided data
     */
    private function update() {
        $this->db->update($this::DB_TABLE, $this, $this::DB_TABLE_PK);   
    }
    
    /**
     * 
     * @param type $row
     */
    public function populate($row) {
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
    }
    
    /**
     * fetch value from database
     * @param type $id
     */
    public function load($id) {
        $query = $this->db->get_where($this::DB_TABLE, array(
            $this::DB_TABLE_PK => $id,
        ));
        $this->populate($query->row());
    }
    
    public function delete() {
        $this->db->delete($this::DB_TABLE, array(
           $this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK},    
        ));
    }
    
    
    /**
     * creates or updates entry when applicable
     */
    public function save() {
        if (isset($this->{$this::DB_TABLE_PK})) {
            $this->update();
        } 
        else {
            $this->insert();
        }
    }
    
    /**
     * fetches an array of models upto an optional limit and offset
     */
    public function get($limit = 0, $offset = 0) {
        if ($limit) {
            $query = $this->db->get($this::DB_TABLE, $limit, $offest);
        } 
        else {
            $query = $this->db->get($this::DB_TABLE);
        }
        
        $ret_val = array();
        $class = get_class($this);
        foreach ($query->result() as $row) {
            $model = new $class;
            $model->populate($row);
            $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
        }

        return $ret_val;
    }   
}

