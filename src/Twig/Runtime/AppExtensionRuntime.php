<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function minutesToHour($value): string
    {
        if ($value < 60) {
            return sprintf('%s', $value);
        }

        $hours = floor($value / 60);
        $minutes = $value % 60;

        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        $time = sprintf('%sh%s', $hours, $minutes);

        return $time;
    }
}
