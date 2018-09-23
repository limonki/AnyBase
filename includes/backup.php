<?php

require_once("definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__."/includes/database.php");

class Backup
{
	private $m_backup_directory;
  private $m_auto_download;

	public function __construct($backup_directory, $auto_download)
	{
		$this->m_backup_directory = str_replace("\\", "/", $backup_directory."/");
  	$this->m_auto_download = $auto_download;
	}

  public function execute()
  {
    global $config;

    $dir = "../../".$this->m_backup_directory;
    if(!(file_exists($dir))) mkdir($dir, 0777, true);

    $zip = new ZipArchive();

		$db = new DataBase($config["db_server"], $config["db_username"], $config["db_password"], $config["db_database"]);
		$db->backup();

		$path = dirname($_SERVER['PHP_SELF']);

		$zipname = date('Y/m/d');
		$str = "database-".$zipname.".zip";
		$str = str_replace("/", "-", $str);

		if($zip->open($str, ZipArchive::CREATE) !== true) die("Could not open archive");

		if(glob("*.sql") != false)
		{
			$filecount = count(glob("*.sql"));
			$arr_file = glob("*.sql");

				for($i = 0; $i < $filecount; $i++)
				{
					$zip->addFile($arr_file[$i]);
				}
		}

		$zip->addFile("config/config.php");
		//$this->zipDir($zip, "database");

		$zip->close();

		if(glob("*.sql") != false)
		{
			$filecount = count(glob("*.sql"));
			$arr_file = glob("*.sql");

				for($i = 0; $i < $filecount; $i++)
				{
					unlink($arr_file[$i]);
				}
		}

		if(glob("*.zip") != false) $arr_zip = glob("*.zip");

		foreach($arr_zip as $key => $value)
		{
			if(strstr($value, "database"))
			{
				$delete_zip[] = $value;
				copy("$value", "$dir/$value");
			}
		}

		$flag = false;
		for($i = 0; $i < count($delete_zip); $i++) $flag = unlink($delete_zip[$i]);

		if($this->m_auto_download)
		{
	    $file = $dir.$str;
			return $file.":".$flag;
		}

		return ":".$flag;
  }

	private function zipDir($zip, $dir)
	{
		// Get real path for our folder
		$rootPath = realpath($dir);
		// Create recursive directory iterator.
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

		foreach($files as $name => $file)
		{
		    // Skip directories (they would be added automatically).
		    if(!$file->isDir())
		    {
		        // Get real and relative path for current file.
		        $filePath = $file->getRealPath();
		        $relativePath = $dir."/".substr($filePath, strlen($rootPath) + 1);

		        // Add current file to archive.
		        $zip->addFile($filePath, $relativePath);
		    }
		}
	}
}

?>
