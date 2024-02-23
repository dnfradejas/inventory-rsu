<?php

namespace App\Models\Data;

abstract class BaseData
{


    protected $data = [];

    /**
     * Set data
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function setData(string $key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Get data
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function getData(string $key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}