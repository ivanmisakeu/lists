<?php

if( !defined('APP_VERSION') ){
    exit();
}

class StdObject extends stdClass
{

    /** @var array */
    private $_keys = [];

    public function __construct( array $array = array() )
    {
        foreach ( $array as $key => $value )
        {
            $this->set( $key, $value );
        }
    }

    /**
     * Set property value
     * 
     * @param string $key
     * @param mixed $value
     * @return NkObject $this
     */
    public function set( string $key, $value )
    {

        if ( !in_array( $key, $this->_keys ) )
        {
            $this->_keys[] = $key;
        }

        $this->{$key} = $value;

        return $this;
    }

    /**
     * Remove property by key name
     * 
     * @param string $key
     * @return NkObject $this
     */
    public function flush( string $key )
    {

        if ( ($index = array_search( $key, $this->_keys )) !== false )
        {
            unset( $this->_keys[ $key ] );
        }

        unset( $this->{$key} );

        return $this;
    }

    /**
     * Get list of object properties keys
     * 
     * @return array
     */
    public function keys()
    {
        return $this->_keys;
    }

    /**
     * Get count of object properties
     * 
     * @return int
     */
    public function count()
    {
        return count( $this->_keys );
    }

}
