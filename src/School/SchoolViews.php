<?php
namespace FPS\School;

class SchoolViews {
    
    function __construct(){

    }

    function buildSelectForm($schoolList){
        $output = '';
        foreach($schoolList as $level => $schools){
            $output .= '<optgroup label="' . $level . '">';
            foreach($schools as $school){
                $output .= '<option value="' . $school['slug'] . '">' . $school['name'] . ' School</option>';
            }
            $output .= '</optgroup>';
        }
        return $output;
    }
}