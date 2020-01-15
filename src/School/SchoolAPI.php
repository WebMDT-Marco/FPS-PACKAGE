<?php
namespace FPS\School;

class SchoolAPI {
    
    function __construct(){

    }
    
    /* 
    $parameters = [
        'type'      => 'single',
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
        'type'      => 'all',
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
                    if($parameters['attribute']=='full'){
                        $output[] = [
                            'id'        => $school->id,
                            'school_id' => $school->school_id,
                            'name'      => $school->name,
                            'slug'      => $school->slug,
                            'type'      => $school->type,
                            'level'     => $school->level,
                            'branding'  => $this->processBranding($school->branding),
                            'meta'      => $this->processMeta($school->meta),
                            'child'     => $school->child,
                        ];  
                    }elseif($parameters['attribute']=='compact'){
                        $output[] = [
                            'id'        => $school->id,
                            'school_id' => $school->school_id,
                            'name'      => $school->name,
                            'slug'      => $school->slug,
                            'type'      => $school->type,
                            'level'     => $school->level,
                            'branding'  => $this->processBranding($school->branding),
                            'child'     => $school->child,
                        ];     
                    }elseif($parameters['attribute']=='name'){
                        $output[] = [
                            'name'      => $school->name,
                            'slug'      => $school->slug,
                        ]; 
                    }  
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
        
        $output = [];
        
        if($values){
            foreach($values as $value){
                $output[$value->attribute] = $value->value;
                
            }
        }
        return $output;
    }

    function processChild($values){
        
        $output = [];
        
        if($values){
            foreach($values as $value){
                $output[$value->attribute] = $value->value;
                
            }
        }
        return $output;
    }
 
    function curlAPI($endpoint){
        
        $data = [];
        
        if(SCHOOL_API){
            if($endpoint){
                $ch = curl_init(SCHOOL_API . $endpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $data = curl_exec($ch);
                curl_close($ch);   
            }
        }else{
            echo 'School API Endpoint must be defined'; 
        }
        
        return $data;
    }
}