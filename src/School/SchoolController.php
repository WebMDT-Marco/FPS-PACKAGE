<?php
namespace FPS\School;

use FPS\School\SchoolAPI;

class SchoolController {
    
    function __construct(){
        $this->schoolAPI = new SchoolAPI();
    }
    
    function groupSchoolsByLevel($attribute = 'full'){
        $output = [
            'elem'   => $this->processSchoolsByLevel('primary',$attribute),
            'middle' => $this->processSchoolsByLevel('middle',$attribute),
            'high'   => $this->processSchoolsByLevel('secondary',$attribute),  
        ];
        
        return $output;   
    }

	function processSchoolsByLevel($level,$attribute){
        
        $output = [];
        
        if($level == 'primary'){
            $elemSchools = $this->getSchoolsByLevel('primary',$attribute);   
            $otherSchools = $this->getSchoolsByLevel('other',$attribute); 
            $schools = array_merge($elemSchools,$otherSchools);
        }else{
            $schools = $this->getSchoolsByLevel($level,$attribute);    
        }
        
        foreach($schools as $school){
            $schoolSlug = $school['slug'];
            $output[$schoolSlug] = $school;
        }
        
        return $output;
    }
    
    function getSchoolsByLevel($level,$attribute){
        $parameters = [
            'type'      => 'level',
            'value'     => $level,
            'sort'      => 'ASC',
            'attribute' => $attribute,
        ]; 
        $output = $this->schoolAPI->getSchools($parameters);
        return $output;
    }
}