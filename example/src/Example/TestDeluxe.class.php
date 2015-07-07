<?php

namespace Example;

/**
 * Description of Test2
 *
 * @author michaeldoehler
 */
class TestDeluxe {

	/**
	 * test
	 *
	 * @var \Example\TestDeluxe
	 */
	private static $instance;

    /**
     * @var \Exception
     */
    private $exception;

	/**
	 *
	 * @var string
	 */
	private $dummy;
	
	/**
	 * calculate based on test
	 * 
	 * @param \Example\Test $test
	 * @return int
	 */
	public function calculate(Test $test) {
		return $test->add(1, 2);
	}

	/**
	 * get dummy value
	 * 
	 * @return string
	 */
	public function getDummy() {
		return $this->dummy;
	}

	/**
	 * set dummy value
	 * 
	 * @param string $dummy
	 */
	public function setDummy($dummy) {
		$this->dummy = $dummy;
	}

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param \Exception $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }
	
	/**
	 * get singleton of test
	 * 
	 * @return \Example\TestDeluxe
	 */
	public static function instance() {
		if (self::$instance === null) {
			self::$instance = new TestDeluxe();
		}
		return self::$instance;
	}

	/**
	 * get singleton of test
	 *
	 * @return \Example\TestDeluxe
	 */
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new TestDeluxe();
		}
		return self::$instance;
	}

    /**
     * @return \Example\Test2
     */
    public static function getFancyalue(){
        return new \Example\Test2();
    }

    /**
     * @return \DOMDocument
     */
    public function getDOMDocument(){
        return new \DOMDocument();
    }

}

?>
