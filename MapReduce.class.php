<?php

class MapReduce {
    private $_data;
    private $_map;
    private $_reduce;
    private $_parts;
    
    public function setData($data) {
        $this->_data = $data;
    }
    
    public function setMap($func) {
        $this->_map = $func;
    }
    
    public function setReduce($func) {
        $this->_reduce = $func;
    }
    
    public function setParts($parts) {
        $this->_parts = $parts;
    }
    
    private function _divide() {
        $data_divided = array();
        $i = 0;
        
        foreach ($this->_data as $data) {
            $data_divided[$i % $this->_parts][] = $data;
            $i++;
        }
        return $data_divided;
    }
    
    public function run() {
        $data_divided = $this->_divide();

        $data_mapped = array();
        foreach ($data_divided as $i => $data) {
            $data_mapped[$i] = array();
            foreach ($data as $item) {
                $data_mapped[$i][] = call_user_func($this->_map, $item);
            }
        }

        $data_reduced = array();
        foreach ($data_mapped as $i => $data) {
            $reduce_value = null;
            foreach ($data as $item) {
                $reduce_value = call_user_func($this->_reduce, $reduce_value, $item);
            }
            $data_reduced[$i] = $reduce_value;
        }

        $reduce_value = null;
        foreach ($data_reduced as $value) {
            $reduce_value = call_user_func($this->_reduce, $reduce_value, $value);
        }
        return $reduce_value;
    }  
}