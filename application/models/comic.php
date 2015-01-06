<?php 
class Comic extends MY_Model {
    const DB_TABLE = 'info';
    const DB_TABLE_PK = 'id';
    
    public $id;         // int
    public $number;     // int
    public $date;       // datestamp
    public $topic;      // varchar
    public $title;      // varchar
    public $img_src;    // varchar
}