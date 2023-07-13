<?php
namespace FPS\School;

use Exception;

class SchoolAPI {
    
    function __construct(){}
    
    /* 
    $parameters = [
        'type'      => 'single',
        'value'     => 2,
        'sort'      => 'ASC',
        'attribute' => 'full',
    ];
    */  
    function getBySchool($parameters){
        $request = $this->processRequest($parameters);
        return $request ? $request[0] : [];
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
        $request = $this->processRequest($parameters);
        return $request ? $request : [];
    }
    
    function processRequest($parameters){
        $query = $this->processQuery($parameters);
        $response = $this->curlAPI($query);
        $decoded = json_decode($response);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Failed to decode JSON from response');
        }
    
        if(isset($decoded->message) && $decoded->message == 'pass'){
            $output = [];
            if(isset($decoded->body) && is_array($decoded->body) || is_object($decoded->body)) {
                foreach($decoded->body as $school){
                    $output[] = $this->buildSchoolData($school, $parameters['attribute']);
                }
            }
            return $output;
        }
        
        return [];  
    }

    function processQuery($parameters){
        return '?' . http_build_query($parameters, '', '&');
    }
    
    function processBranding($values){
        $branding = [];
    
        if(isset($values) && (is_array($values) || is_object($values))) {
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
        foreach($values as $value){
            $output[$value->attribute] = $value->value;
        }
        return $output;
    }
 
    function curlAPI($endpoint){
        $data = '';
        
        if(defined('SCHOOL_API')){
            $ch = curl_init(SCHOOL_API . $endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            
            if (curl_errno($ch)) {
                throw new Exception('CURL Error: ' . curl_error($ch));
            }
            
            curl_close($ch);   
        } else {
            throw new Exception('School API Endpoint must be defined'); 
        }
        
        return $data;
    }

    private function buildSchoolData($school, $attribute){
        $data = [
            'id'        => $school->id,
            'school_id' => $school->school_id,
            'name'      => $school->name,
            'slug'      => $school->slug,
            'type'      => $school->type,
            'level'     => $school->level,
            'branding'  => $this->processBranding($school->branding),
            'child'     => $school->child,
        ];

        if($attribute == 'full'){
            $data['meta'] = $this->processMeta($school->meta);
        }

        if($attribute == 'compact'){
            unset($data['meta']);
        }

        if($attribute == 'name'){
            return [
                'name'      => $school->name,
                'slug'      => $school->slug,
            ];
        }

        return $data;
    }
}
