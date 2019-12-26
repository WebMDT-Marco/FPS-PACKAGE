<?php
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require_once $composer;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);	

$schoolViews = new FPS\School\SchoolViews;


/*Select Menu Output*/
$schoolSelectList = '<label for="schoolList">School List</label>';
$schoolSelectList .= '<select id="schoolList" name="schoolList" class="form-control"><option "">Choose your school</option>' . $schoolViews->buildSelectForm() . '</select>';

echo $schoolSelectList;