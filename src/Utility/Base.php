<?php
namespace FPS\Utility;

class Base {
    
    function formatPhoneNumber($phoneNumber){
        $phoneNumberFormatted = '';
        
        if($phoneNumber){
            if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 10) {
                $phoneNumberFormatted = '(' . substr($phoneNumber, 0, 3) . ') '. substr($phoneNumber, 3, 3) .'-'. substr($phoneNumber, 6);
            }else{
                if(ctype_digit($phoneNumber) && strlen($phoneNumber) == 7) {
                    $phoneNumberFormatted = substr($phoneNumber, 0, 3) .'-'. substr($phoneNumber, 3, 4);
                }
            }
        }
        return $phoneNumberFormatted;
    }

    function gnerateSchoolURL($school){
        $schoolURL = '';
        
        if($school){
            $schoolURL = 'https://' . strtolower($school['slug']) . '.fairfieldschools.org/';
        }
        return $schoolURL;
    }
}