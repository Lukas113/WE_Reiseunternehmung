<?php

namespace http;

/**
 * @author Andreas Martin
 */
class HTTPHeader implements HTTPStatusCode
{
    use HTTPStatusHeader;

    public static function redirect($redirect_path, $statusCode = HTTPStatusCode::HTTP_301_MOVED_PERMANENTLY) {
        header("Location: " . $GLOBALS["ROOT_URL"] . $redirect_path, true, self::getStatusCodeNumber($statusCode));
        exit;
    }
}