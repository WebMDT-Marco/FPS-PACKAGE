<?php
namespace FPS\School;

use FPS\School\SchoolController;

class SchoolViews {
    
    function __construct(){
        $this->schoolController = new SchoolController();
    }

    function buildSelectForm($type = 'school'){ 
        switch($type){
            case "school":
                return $this->processSchoolList();
                break;
            case "child":
                return $this->processChildList();
                break;  
            default:
                return $this->processSchoolList();
        }
    }
    
    function processSchoolList(){
        
        /*Initialize*/
        $output = '';
        
        /*Get School List*/
        $schoolList = $this->schoolController->groupSchoolsByLevel('compact');
        
        /*Process Schools*/
        foreach($schoolList as $level => $schools){
            $output .= '<optgroup label="' . $this->slugToLabel($level) . ' Schools">';
            foreach($schools as $school){
                $output .= '<option value="' . $school['slug'] . '" data-school_id="' . $school['school_id'] . '">' . $school['name'] . ' School</option>';
            }
            $output .= '</optgroup>';
        }
        
        return $output;
    }
    
    function processChildList(){
        
        /*Initialize*/
        $output = '';
        $schoolChilds = [];
        
        /*Get School List*/
        $schoolList = $this->schoolController->groupSchoolsByLevel('compact');
        
        /*Process Schools*/
        foreach($schoolList as $level => $schools){
            foreach($schools as $school){

                if(isset($school['child'])){
                    $schoolSlug = $school['slug'];
                    $schoolChilds[$schoolSlug] = $school['child'];   
                }
            }
        }
        
        /*Process Childs*/
        foreach($schoolChilds as $schoolSlug => $childs){
            $output .= '<optgroup label="' . $schoolSlug . '">';
            foreach($childs as $child){
                $output .= '<option value="' . $child->slug . '" data-school_id="' . $child->schoolID . '">' . $child->name . ' ' . ucfirst($child->type) . '</option>'; 
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