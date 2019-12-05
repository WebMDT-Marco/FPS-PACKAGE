<?php
namespace FPS;

class SchoolAPI {
    
    function __construct(){
        if(isset(SCHOOL_API)){
            $this->API_ENDPOINT = SCHOOL_API;
        }else{
            $this->API_ENDPOINT = NULL; 
        }

    }
    
    /* 
    $parameters = [
        'type'      => single,
        'value'     => 2,
        'sort'      => 'ASC',
        'attribute' => 'full',
    ];
    */  
    function getBySchool($parameters){
        
        /*Initialize*/
        $school = [];
        
        $request = $this->processRequest($parameters);
        
        if($request){
            $school = $request[0];
        }
        
        return $school;     
    }

    /*
    $parameters = [
        'type'      => all,
        'value'     => NULL,
        'sort'      => 'ASC',
        'attribute' => 'full',
    ];
    */ 
    function getSchools($parameters){
        
        /*Initialize*/
        $school = [];
        
        $request = $this->processRequest($parameters);
        
        if($request){
            $school = $request;
        }
        
        return $school;     
    }
    
    function processRequest($parameters){
        
        /*Initialize*/
        $output = [];
        
        /*Build Query String*/
        $query = $this->processQuery($parameters);
        
        /*Perform CURL Request*/
        $response = $this->curlAPI($query);
        
        if($response){
            
            /*Decode*/
            $decoded = json_decode($response); 
            if($decoded->message == 'pass'){
                foreach($decoded->body as $school){
                    $output[] = [
                        'id'        => $school->id,
                        'school_id' => $school->school_id,
                        'name'      => $school->name,
                        'slug'      => $school->slug,
                        'type'      => $school->type,
                        'level'     => $school->level,
                        'branding'  => $this->processBranding($school->branding),
                        'meta'      => $this->processMeta($school->meta),
                    ];
                }
            }
        }
        
        return $output;  
    }
    
    function processQuery($parameters){
        
        /*Initialize*/
        $query = '';
        $isFirst = TRUE;
        
        if($parameters){
            foreach($parameters as $name => $value){
                if($isFirst){
                    $query .= '?' . $name . '=' . $value . '&';
                }else{
                    $query .= $name . '=' . $value . '&';
                }
                $isFirst = FALSE;
            }
        }
        return $query;
    }
    
    function processBranding($values){

        $branding = [];
        
        if($values){
            foreach($values as $value){
                $branding[$value->type][$value->attribute] = [
                    'value'       => $value->value,
                    'description' => $value->description,
                ];
            }
        }
        return $branding;
    }

    function processMeta($values){
        
        $meta = [];
        
        if($values){
            foreach($values as $value){
                $meta[$value->attribute] = $value->value;
                
            }
        }
        return $meta;
    }
    
    function formatPhoneNumber($phoneNumber){
        
        /*Initialize*/
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

    function generateURL($school){
        $schoolURL = '';
        
        if($school){
            $schoolURL = 'https://' . strtolower($school['slug']) . '.fairfieldschools.org/';
        }
        return $schoolURL;
    }
    
    function curlAPI($endpoint){
        
        $data = [];
        if($endpoint){
            $ch = curl_init($this->API_ENDPOINT . $endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);   
        }

        return $data;
    }
}