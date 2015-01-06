<?php 
class DB_Instance extends CI_Model {
    const DB_TABLE = 'db_info';
    const DB_TABLE_PK = 'id';
    
    public $id;         		// int
    public $update_time;     	// int
    public $comic_count;       // datestamp

}