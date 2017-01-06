<?php

namespace ECF;

class Notifier {


    /**
     * Construct
     */
    public function __construct() {}


    /**
     * Notify
     * @param $message
     */
    public static function notify($message) {
        $style = 'style="color: #a94442;background-color: #f2dede;padding: 20px;margin: 20px 0px;"';

        $html =
            '<div ' . $style . '>' .
                '<span>' .
                    $message .
                '</span>' .
            '</div>';

        echo $html;
    }
}