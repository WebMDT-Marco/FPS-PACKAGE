<?php
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require_once $composer;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);	  

/******************************************************************/
/*
* End-point must be defined with the "SCHOOL_API"
*  constant when calling the API Library
*/
if (!defined('SCHOOL_API')){
    define('SCHOOL_API', 'https://api.fairfieldschools.org/schools');
}
/******************************************************************/

$schoolAPI = new FPS\School\SchoolAPI;
$schoolViews = new FPS\School\SchoolViews;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
  </head>
  <body>
  	<div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="form-group" id="schoolListWrapper">
                    <label for="schoolList">School List</label>
                    <select id="schoolList" name="schoolList" class="form-control"><option "">Choose your school</option><?php echo $schoolViews->buildSelectForm('school'); ?></select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group" id="childListWrapper">
                    <label for="childList">Child/House List</label>
                    <select id="childList" name="childList" class="form-control"><option "">Choose your Child/House</option><?php echo $schoolViews->buildSelectForm('child'); ?></select>
                </div>
            </div>
        </div>
  	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script>
        handleChildList('#schoolList','#schoolListWrapper','#childList','#childListWrapper');
        
        /*
        * Hides the Child Select List and the Items in the list
        *     based on the School Selected
        *   ex: Selecting FWHS will display the three(3) Houses: Fitts, Townsend, Pequot
        *   ex: Selecting BURR will hide the Child List as no Houses or Child's exist for Burr
        *
        * @param - ID's of the School List select, its wrapper, the Child List and its wrapped
        * ex: handleChildList('#schoolList','#schoolListWrapper','#childList','#childListWrapper');
        */ 
        function handleChildList(schoolList,schoolListWrapper,childList,childListWrapper){
            
            /*Hide the Child List by Default*/
            $(childListWrapper).hide();
            
            /*Watch for a change to the School List Dropdown*/
        	$(schoolList).change(function(){
            	
            	/*Find Childs with Matching School ID*/
        		var selectedSchoolID = $(schoolList).find('option:selected[data-school_id]');

                /*Check if a valid selection was made*/
        		if(selectedSchoolID.val() !== undefined){

                    /*Show ONLY the Childs that match the School ID*/
            		if($(childList).find("option[data-school_id=" + selectedSchoolID.data('school_id') + "]").length != 0){
            		    $(childListWrapper).show();
                		$(childList).find("option").show();
                		$(childList).find("optgroup").show();
            			$(childList).find("option[data-school_id!=" + selectedSchoolID.data('school_id') + "]").hide();
            			$(childList).find("optgroup[label!=" + selectedSchoolID.val()+ "]").hide();
            		}else{
                		$(childListWrapper).hide();
            		}
        		}else{
                    $(childListWrapper).hide();
        			$(childList).find("option").hide();
            		$(childList).find("optgroup").hide();
                }
        	});
        }
    </script>
  </body>
</html>

