<?php

class Utility{

    public function __construct()
    {
        
    }

    public function stripTags(&$input){
     $input = htmlspecialchars(strip_tags($input));
    }

}
