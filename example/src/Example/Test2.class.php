<?php

namespace Example;

class Test2 {

    /**
     * @var string
     */
    private $horde;

    /**
     * @return string
     */
    public function getHorde()
    {
        return $this->horde;
    }

    /**
     * @param string $horde
     */
    public function setHorde($horde)
    {
        $this->horde = $horde;
    }

}
