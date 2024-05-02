<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Custom Twig extension to shuffle an array.
 */
class ShuffleExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('shuffle', [$this, 'shuffleArray']),
        ];
    }

    public function shuffleArray($array)
    {
        if (is_array($array)) {
            shuffle($array);
        }

        return $array;
    }
}
