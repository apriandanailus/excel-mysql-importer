

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Data Importer</title>
       
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="assets/css/styles.css" rel="stylesheet">
    </head>
    <body>
<!-- header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Excel to Mysql Importer</a>
        </div>
        
    </div>
    <!-- /container -->
</div>
<!-- /Header -->

<!-- Main -->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
           

            <ul class="nav nav-stacked" style="background-color: #ebedef;">
                <li class="nav-header"> 
                    <ul class="nav nav-stacked collapse in" id="userMenu">
                        <li class="active"> <a href="./"> New Import</a></li>
                    </ul>
                </li>
            </ul>
                
        </div>
        
        <div class="col-md-10" >
            <div class="row">
                <div class="col-md-4">
                    <form action="" method="post" enctype="multipart/form-data">
                         <hr>
                        <div class="form-group">
                            <label>Excel File : </label>
                            <input class="form-control" type="file" name="uploaded_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div> 
                        
                        <input  class="btn btn-primary" type="submit" name="submit" value="Import"> 
                    </form>
                </div>
                
            </div>

        
        <hr>    
        <a class="btn btn-primary" data-toggle="collapse" href="#result" aria-expanded="false" aria-controls="collapseExample">
        See Result
        </a>
        </div>

    </div>
</div>




    <!-- script references -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/scripts.js"></script>
    </body>
</html>




<?php
/**
 * PHPExcel 
 * ==============================================================================
 * 
 * @version v1.0: 
 * @copyright Copyright (c) 2017, 
 * @author Apriandana Ilus <fliez92@gmail.com> & Sagar Deshmukh <sagarsdeshmukh91@gmail.com>
 * ==============================================================================
 *
 */
 
require 'Classes/PHPExcel/IOFactory.php';
// require "Classes/db/db.php";

$path = "uploads/";

if(!empty($_FILES['uploaded_file']))
  {
    $path = "uploads/";
    $path = $path . basename( $_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
      echo " <p> The file ".  basename( $_FILES['uploaded_file']['name']). 
      " has been uploaded </p>";
    } else{
        echo "There was an error uploading the file, please try again!";
    }
  }


$inputfilename = $path;
$exceldata = array();


//  Read your Excel workbook
try
{
    $inputfiletype = PHPExcel_IOFactory::identify($inputfilename);
    $objReader = PHPExcel_IOFactory::createReader($inputfiletype);
    $objPHPExcel = $objReader->load($inputfilename);
}
catch(Exception $e)
{
    die(' <div class="collapse" id="result">
		  <div class="card card-body">Error loading file "'.pathinfo($inputfilename,PATHINFO_BASENAME).'": '.$e->getMessage() . "</div> </div>");
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();

//empty database 
$sql = "TRUNCATE TABLE stores";
mysqli_query($conn, $sql);


//  Loop through each row of the worksheet in turn
for ($row = 2; $row <= $highestRow; $row++)
{ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	
    //  Insert row data array into your database of choice here
	$sql = "INSERT INTO stores 
	(pid, 
	workshopid, 
	name, 
	street, 
	postal, 
	city, 
	country, 
	phone, 
	fax, 
	email, 
	contact_warehouse, 
	phone_warehouse, 
	fax_warehouse, 
	mail_warehouse, 
	partner_of, 
	business_hours, 
	longitude, 
	latitude, 
	braketest, 
	diagnose, 
	service_brakes, 
	service_hydraulic, 
	service_tyres, 
	service_chiller, 
	service_crane, 
	service_liftgate, 
	service_construction, 
	service_anhaenger)
			VALUES ('".$rowData[0][0]."', '".$rowData[0][1]."', '".$rowData[0][2]."', '".$rowData[0][3]."', '".$rowData[0][4]."', '".$rowData[0][5]."', '".$rowData[0][6]."', '".$rowData[0][7]."', '".$rowData[0][8]."', '".$rowData[0][9]."', '".$rowData[0][10]."', '".$rowData[0][11]."', '".$rowData[0][12]."', '".$rowData[0][13]."', '".$rowData[0][14]."', '".$rowData[0][15]."', '".$rowData[0][16]."', '".$rowData[0][17]."', '".$rowData[0][18]."', '".$rowData[0][19]."', '".$rowData[0][20]."', '".$rowData[0][21]."', '".$rowData[0][22]."', '".$rowData[0][23]."', '".$rowData[0][24]."', '".$rowData[0][25]."', '".$rowData[0][26]."', '".$rowData[0][27]."')";
	
	if (mysqli_query($conn, $sql)) {
		$exceldata[] = $rowData[0];
	} else {
		echo "<div class='collapse' id='result'>
		  <div class='card card-body'>";
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		echo " </div>
		</div>";
	}
}


// Print excel data
echo "
<div class='collapse' id='result'>
		  <div class='card card-body'> 
<table>";
foreach ($exceldata as $index => $excelraw)
{
	echo "<tr>";
	foreach ($excelraw as $excelcolumn)
	{
		echo "<td>".$excelcolumn."</td>";
	}
	echo "</tr>";
}
echo "</table> </div> </div>";

mysqli_close($conn);
?>