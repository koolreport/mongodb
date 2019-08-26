<?php
/**
 * This file contains class to pull data from MongoDB
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/*
  The user can declare connection string
    array(
        "connectionString"="mongo://{username}:{password}@localhost:65432",
        'database' => 'test'
    )
    or
    array(
        "host"=>"mongo://localhost:65432",
        "username":"username",
        "password":"password",
        'database' => 'test'
    )
    ->query(array(
        'collection' => 'sales',
        'find' => ['age' => ['$gte' => '40']],
        'options' => [
            'skip' => 0,
            'limit' => 5,
            'projection' => [
                '_id' => 0,
                'name' => 1,
                'age' => 1,
            ],    
        ],
    ))
  
 */

namespace koolreport\mongodb;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;

class MongoDataSource extends DataSource
{
    protected $connectionString;
    protected $host;
    protected $username;
    protected $password;
    protected $charset;
    protected $database;
    protected $collection;
    protected $find;
    protected $options;
    
    protected $mongoClient;
    
    protected function onInit()
    {
        $this->connectionString = Utility::get($this->params,"connectionString");
        $this->host = Utility::get($this->params,"username");
        $this->username = Utility::get($this->params,"username");
        $this->password = Utility::get($this->params,"password");
        $this->charset = Utility::get($this->params,"charset","utf8");
        $this->database = Utility::get($this->params,"database",null);
        
        if($this->connectionString) {
            $this->mongoClient = new \MongoDB\Client($this->connectionString);
        } else {
            $this->mongoClient = new \MongoDB\Client($this->host, array(
                "username"=>$this->username,
                "password"=>$this->password,
            ));
        }
    }
  
    function query($params) 
    {
        $this->collection = Utility::get($params, "collection", null);
        $this->find = Utility::get($params, "find", array());
        $this->options = Utility::get($params, "options", array());
        return $this;
    }
    
    protected function guessType($value)
    {
        $map = array(
            "float"=>"number",
            "double"=>"number",
            "int"=>"number",
            "integer"=>"number",
            "bool"=>"number",
            "numeric"=>"number",
            "string"=>"string",
        );

        $type = strtolower(gettype($value));
        foreach($map as $key=>$value) {
            if(strpos($type,$key)!==false) {
                return $value;
            }			
        }
        return "unknown";
    }
  
    public function start()
    {
        $data = array();
        $collection = $this->mongoClient->{$this->database}->{$this->collection};
        $cursor = $collection->find($this->find, $this->options);
        foreach ($cursor as $i => $row) {
            $row = (array) $row;
            if ($i === 0) {
                $firstRow = $row;
                $columnNames = array_keys($firstRow);
                $metaData = array("columns"=>array());
                for($i=0; $i<count($columnNames); $i++) {						
                $metaData["columns"][$columnNames[$i]] = array(
                    "type"=>(isset($firstRow)) ? 
                        $this->guessType($firstRow[$columnNames[$i]]) : "unknown");
                }
                $this->sendMeta($metaData, $this);
                $this->startInput(null);
            }
            $this->next($row, $this);	
        }
        $this->endInput(null);
    }
}
