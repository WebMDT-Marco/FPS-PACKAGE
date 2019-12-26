<?php
namespace FPS\School;

use FPS\School\SchoolController;

class SchoolViews {
    
    function __construct(){
        $this->schoolController = new SchoolController();
    }

    function buildSelectForm(){
        
        $schoolList = $this->schoolController->groupSchoolsByLevel('compact');
        
        $output = '';
        foreach($schoolList as $level => $schools){
            $output .= '<optgroup label="' . $this->slugToLabel($level) . ' Schools">';
            foreach($schools as $school){
                $output .= '<option value="' . $school['slug'] . '">' . $school['name'] . ' School</option>';
            }
            $output .= '</optgroup>';
        }
        return $output;
    }
    
    function slugToLabel($level){
        $output = '';
        switch($level){
            case 'elem':
                $output = 'Elementary';
                break;
            case 'middle':
                $output = 'Middle';
                break;
            case 'high':
                $output = 'High';
                break;  
        }
        return $output;
    }
}