<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Example;

/**
 * Description of Test
 *
 * @author michaeldoehler
 */
class Test {

    /**
     * @var int
     */
    private $integer;

    /**
     * @var bool
     */
    private $boolean;

    /**
     * @var string
     */
    private $string;

    /**
     * @var float
     */
    private $float;

    /**
     * @var \Example\Test2
     */
    private $test2;

    /**
     * @return int
     */
    public function getInteger()
    {
        return $this->integer;
    }

    /**
     * @param int $integer
     */
    public function setInteger($integer)
    {
        $this->integer = $integer;
    }

    /**
     * @return boolean
     */
    public function getBoolean()
    {
        return $this->boolean;
    }

    /**
     * @param boolean $boolean
     */
    public function setBoolean($boolean)
    {
        $this->boolean = $boolean;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     */
    public function setString($string)
    {
        $this->string = $string;
    }

    /**
     * @return float
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * @param float $float
     */
    public function setFloat($float)
    {
        $this->float = $float;
    }

    /**
     * @return \Example\Test2
     */
    public function getTest2()
    {
        return $this->test2;
    }

    /**
     * @param \Example\Test2 $test2
     */
    public function setTest2(\Example\Test2 $test2)
    {
        $this->test2 = $test2;
    }



		/**
	 * a + b test
	 * 
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	public function add($a, $b) {
		return $a + $b;
	}

    private static $instance;

    /**
     * @return \Example\Test
     */
    public static function getInstance(){
        return new self();
    }
		

}
