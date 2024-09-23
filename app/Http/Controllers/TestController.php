<?php

namespace App\Http\Controllers;


class TestController extends Controller {
    public function test() {
        $a = array(
            null => 'a',
            true => 'b',
            false => 'c',
            0 => 'd',
            1 => 'e',
            '' => 'f',
        );
        var_dump(count($a));
    }
}
