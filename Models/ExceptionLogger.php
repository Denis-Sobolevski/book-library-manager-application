<?php

class ExceptionLogger
{
    private static string $fName;

    public function __construct(string $fName)
    {
        self::$fName = $fName;
    }

    public static function logException(string $exceptionMessage, string $fromFunction)
    {
        $file = fopen(self::$fName, "a");
        $date = date("Y-m-d h:i:sa");

        $line = "An Exception occoured at: $date
                from function: $fromFunction
                Information:
                $exceptionMessage\n\n\n";

        fputs($file, $line . "\n");

        fclose($file);
    }
}
