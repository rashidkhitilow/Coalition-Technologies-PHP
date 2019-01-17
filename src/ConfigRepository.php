<?php

namespace Coalition;
use \ArrayAccess;
class ConfigRepository implements ArrayAccess
{
    public $container = null;
    /**
     * ConfigRepository Constructor
     */
    public function __construct($values = null)
    {
        if( is_array($values) ){
            foreach ($values as $key => $value) {
                $this->container[$key] = $value;                
            }
        }
        return $this->container;
    }
    /**
     * Determine whether the config array contains the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        if (array_key_exists($key, $this->container)) {
            return true;
        }
        return false;
    }
    /**
     * Set a value on the config array
     *
     * @param string $key
     * @param mixed  $value
     * @return \Coalition\ConfigRepository
     */
    public function set($key = null, $value = null)
    {
        if( ! is_null($key) ){
                $this->container[$key] = $value;
        }
        return $this;
    }
    /**
     * Get an item from the config array
     *
     * If the key does not exist the default
     * value should be returned
     *
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ( !$default && array_key_exists($key, $this->container)) {
            return $this->container[$key];
        }
        return $default;        
    }
    /**
     * Remove an item from the config array
     *
     * @param string $key
     * @return \Coalition\ConfigRepository
     */
    public function remove($key)
    {
        if (array_key_exists($key, $this->container)) {
            unset($this->container[$key]);
        }
        return $this;      
    }
    /**
     * Load config items from a file or an array of files
     *
     * The file name should be the config key and the value
     * should be the return value from the file
     * 
     * @param array|string The full path to the files $files
     * @return void
     */
    public function load($files = null)
    {
        if( is_string($files) ){
            $key = basename("$files", ".php");        
            $fileContent = include $files;
            $this->container[$key] = $fileContent;
            return true;
        }elseif( is_array($files) ){
            foreach($files as $file) {
                $key = basename("$file", ".php");                
                $fileContent = include $file;
                $this->container[$key] = $fileContent;
            }
            return true;
        }
        return false;        
    }
    
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->container[$offset]) 
                        ? $this->container[$offset] : null;
    }    
}