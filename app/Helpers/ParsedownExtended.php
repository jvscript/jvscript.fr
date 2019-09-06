<?php

namespace App\Helpers;

use Parsedown;

class ParsedownExtended extends Parsedown
{
    protected function blockHeader($line)
    {
        $block = Parsedown::blockHeader($line);

        if (isset($block['element']['name'])) {
            $block['element']['name'] = 'div';
        }

        return $block;
    }
}
