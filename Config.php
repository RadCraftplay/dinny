<?php

class Config
{
    private static $CONFIG = null;

    static function getConfig() {
        if (self::$CONFIG != null) {
            return self::$CONFIG;
        }

        $CONFIG = [];

        if ($file = fopen(".env", "r")) {
            while(!feof($file)) {
                $line = fgets($file);
                if (trim($line) == "") {
                    continue;
                }

                $split = explode("=", $line);
                if (count($split) != 2) {
                    continue;
                }
                $CONFIG[trim($split[0])] = trim($split[1]);
            }
            fclose($file);
        } else {
            die("No .env file found!");
        }

        self::$CONFIG = $CONFIG;
        return $CONFIG;
    }
}