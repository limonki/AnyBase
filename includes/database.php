<?php

require_once('definitions.php');

/**
  * Database class to connect with MySQL database and fire queries.
  *
  * @remarks A simple MySQL database class.
  * @author Balcerzak Mateusz
  * @version 1.0
  * @since 1.0
  * @copyright Copyright (c) 2018, Balcerzak Mateusz
  *
  **/
class DataBase
{
  /**
    * Database configuration, connection and result.
    *
    * @var $m_config $m_connection $m_result
    **/
  protected $m_config = array();
  protected $m_connection;
  private $m_result;

  /**
    * Constructor.
    *
    * @param string $host $username $password $database String identifier.
    * @return void
    **/
  public function __construct($host, $username, $password, $database)
  {
    $this->m_config['host'] = $host;
    $this->m_config['username'] = $username;
    $this->m_config['password'] = $password;
    $this->m_config['database'] = $database;
    self::connect();
  }

  /**
    * Deletes key from the current session data.
    *
    * @param void
    * @return void
    * @throws ConnectionErrorException Connection error occurred.
    **/
  private function connect()
  {
    if(!empty($this->m_config['database'])) $this->m_connection = mysqli_connect($this->m_config['host'], $this->m_config['username'], $this->m_config['password'], $this->m_config['database']);
    else $this->m_connection = mysqli_connect($this->m_config['host'], $this->m_config['username'], $this->m_config['password']);
    mysqli_set_charset($this->m_connection, 'utf8');
    if(mysqli_connect_errno())
    {
      // throw an exception
    }
  }

  /**
    * Sets database.
    *
    * @param void
    * @return void
    **/
  public function setDb($database)
  {
    $this->m_config['database'] = $database;
  }

  /**
    * Returns last mysql error.
    *
    * @param void
    * @return void
    * @throws ConnectionErrorException Connection error occurred.
    **/
  public function error()
  {
    return mysqli_error($this->m_connection);
  }

  /**
    * Returns database selected.
    *
    * @param void
    * @return void
    **/
  public function selected()
  {
    return mysqli_select_db($this->m_connection, $this->m_config['database']);
  }

  /**
    * Return num rows.
    *
    * @param void
    * @return void
    **/
  public function numRows()
  {
    return mysqli_num_rows($this->m_result);
  }

  /**
    * Return num fileds.
    *
    * @param void
    * @return void
    **/
  public function numFields()
  {
    return mysqli_num_fields($this->m_result);
  }

  /**
    * Return fetch assoc.
    *
    * @param void
    * @return void
    **/
  public function fetchAssoc()
  {
    return mysqli_fetch_assoc($this->m_result);
  }

  /**
    * Return fetch array.
    *
    * @param void
    * @return void
    **/
  public function fetchArray()
  {
    return mysqli_fetch_array($this->m_result);
  }

  /**
    * Fires query.
    *
    * @param string $query String identifier.
    * @return mysqli_result Return query result.
    **/
  public function query($query)
  {
    $this->m_result = mysqli_query($this->m_connection, $query);
    return $this->m_result;
  }

  /**
    * Builds and executes select query.
    *
    * @param string $from $order
    * @param string/array $param $where
    * @param int $limit
    * @return void
    **/
  public function select($from, $param = '*', $where = null, $limit = null, $order = null)
  {
    // Temps for building query.
    $param_ = '';
    $where_ = '';

    // If param is array then add all elements to query.
    if(is_array($param))
    {
      $i = 0;
      foreach($param as $key => $value)
      {
        $param_ .= $value;
        if($i < count($param) - 1) $param_ .= ', ';
        $i++;
      }
    }
    else $param_ = $param;

    // If where is an array add all statements to query.
    if($where !== null)
    {
      if(is_array($where))
      {
        $i = 0;
        foreach($where as $key => $value)
        {
          $where_ .= $key.' = \''.$value.'\'';
          if($i < count($where) - 1) $where_ .= ' AND ';
          $i++;
        }
      }
      else $where_ = $where;
    }

    // Build query.
    $query = 'SELECT '.$param_.' FROM '.$from.' WHERE '.$where_;

    // Additional options for query.
    if($limit !== null) $query .= ' LIMIT '.$limit;
    if($order !== null) $query .= ' ORDER BY '.$order;

    // Get the result.
    $this->m_result = self::query($query);

    return $this->m_result;
  }

  /**
    * Builds and executes update query.
    *
    * @param string $from $order
    * @param string/array $param $where
    * @param int $limit
    * @return void
    **/
  public function update($table, $param, $where)
  {
    // Temps for building query.
    $param_ = '';
    $where_ = '';

    // If param is array then add all elements to query.
    if(is_array($param))
    {
      $i = 0;
      foreach($param as $key => $value)
      {
        $param_ .= $key.' = \''.$value.'\'';
        if($i < count($param) - 1) $param_ .= ', ';
        $i++;
      }
    }
    else $param_ = $param;

    // If where is an array add all statements to query.
    if(is_array($where))
    {
      $i = 0;
      foreach($where as $key => $value)
      {
        $where_ .= $key.' = \''.$value.'\'';
        if($i < count($where) - 1) $where_ .= ' AND ';
        $i++;
      }
    }
    else $where_ = $where;

    // Build query.
    $query = 'UPDATE '.$table.' SET '.$param_.' WHERE '.$where_;

    // Get the result.
    $this->m_result = self::query($query);

    return $this->m_result;
  }

  /**
    * Backup tables in database.
    *
    * @param array/string $tables
    * @return void
    **/
  function backup($tables = "*")
  {
		// Get all of the tables.
    if($tables == "*")
    {
      $tables = array();
      $result = self::query("SHOW TABLES");

      while($row = mysqli_fetch_row($result)) $tables[] = $row[0];
    }
    else
    {
      $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    $return = "";

    // Cycle through.
    foreach($tables as $table)
    {
      $result = self::query("SELECT * FROM ".$table);
      $num_fields = mysqli_num_fields($result);
      $return .= "DROP TABLE ".$table.";";
      $row2 = mysqli_fetch_row($this->query("SHOW CREATE TABLE ".$table));
      $return .= $row2[1].";";

      while($row = mysqli_fetch_row($result))
      {
	      $return .= "INSERT INTO ".$table." VALUES(";

	      for($j = 0; $j < $num_fields; $j++)
	      {
		      $row[$j] = addslashes($row[$j]);
		      $row[$j] = preg_replace("#n#", "n", $row[$j]);

		      if(isset($row[$j])) $return .= '"'.$row[$j].'"';
					else $return.= '""';

		      if($j < ($num_fields - 1)) $return.= ",";
	      }

	      $return .= ");";
	    }
    }

    // Save file.
    $handle = fopen("do-backup-".time()."-".(md5(implode(",", $tables))).".sql", "w+");
    fwrite($handle, $return);
    fclose($handle);
  }

  /**
    * Restore tables in database.
    *
    * @param array $data
    * @return void
    **/
	function restore($data)
	{
    foreach($data as $key => $value)
    {
		  mysqli_query($this->m_connection, $value);
    }
	}

  /**
    * Closes conection.
    *
    * @param void
    * @return void
    **/
  public function close()
  {
    mysqli_close($this->m_connection);
  }
}

?>
