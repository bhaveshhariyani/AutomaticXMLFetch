<?php
	error_reporting(0);
	$filepath = getcwd();
	// echo $filepath;

// 	if ($handle = opendir($filepath)) {
//     while (false !== ($file = readdir($handle)))
//     {
//         if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml')
//         {
//             $thelist .= '<li><a href="'.$file.'">'.$file.'</a></li>';
//         }
//     }
//     closedir($handle);
// }
?>


<?php
	
	//get the lastest file uploaded in excel_uploads/
	$path = $filepath;
	$latest_ctime = 0;
	$latest_filename = '';    
	$d = dir($path);
	while (false !== ($entry = $d->read())) {
	$filepath1 = "{$path}/{$entry}";
	if ($entry != "." && $entry != ".." && strtolower(substr($entry, strrpos($entry, '.') + 1)) == 'xml')
        {
	//Check whether the entry is a file etc.:
	    if(is_file($filepath1) && filectime($filepath1) > $latest_ctime) {
	    $latest_ctime = filectime($filepath1);
	    $latest_filename = $entry;
	    }//end if is file etc.
	}
	}//end while going over files in excel_uploads dir.
	echo "The latest XML File is - "; echo $latest_filename; 
	?>
	<br>
<?php
	$file = glob('*.xml');
	$url = $latest_filename;
	
	$xml = simplexml_load_file($url);

	$con = mysqli_connect("localhost","root","","new_xml_extract");  //Connect to the server

	foreach ($xml->channel->item as $row) {
		$title = mysqli_real_escape_string($con,$row->title);
		$link = mysqli_real_escape_string($con,$row->link);
		$description = mysqli_real_escape_string($con,$row->description);

		//Perform SQL Query

		$sql = "INSERT INTO test_xml (title,link,description) VALUES ('$title','$link','$description')";
		$result = mysqli_query($con,$sql);

		if (!$result)
		{
			echo 'Data not Inserted';
		}
		else
		{
			echo 'Data inserted successfully';
		}
	}
?>
