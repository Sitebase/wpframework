<?php

/*
Plugin Name: Custom plugin
Plugin URI: http://www.sitebase.be
Description: Description
Author: Sitebase
Version: 1.0
Requires at least: 3.0
Author URI: http://www.sitebase.be
*/
include "class-wpbase.php";

class CustomPlugin extends WpBase_0_3{
        
        const NAME = "My Custom Plugin";
        
        public function activate(){
                echo 'activate';
        }
        
        public function deactivate(){
                echo 'deactivate';
        }
        
        public function filter_the_title($title){
                return $title . ' Modified :)';
        }
        
}

$CustomPlugin = new CustomPlugin();