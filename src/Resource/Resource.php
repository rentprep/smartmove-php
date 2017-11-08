<?php

namespace SmartMove\Resource;

use InvalidArgumentException;

class Resource {

    public function __construct($data = []) {
        if($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    // Standard accessor magic methods
    public function __set($k, $v) {
        if ($v === '') {
            throw new InvalidArgumentException(
                'You cannot set \''.$k.'\'to an empty string. '
                .'We interpret empty strings as NULL in requests. '
                .'You may set obj->'.$k.' = NULL to delete the property'
            );
        }
        $this->$k = $v;
    }

}
