<?php

namespace Carnet;

class AspectRatio
{
    public readonly float $ratio;
    public readonly float $min;
    public readonly float $max;
    public readonly string $slug;

    public function __construct(float $ratio, float $prev, float $next)
    {
        $this->ratio = $ratio;
        $this->min = ($ratio + $prev) / 2;
        $this->max = ($ratio + $next) / 2;
        $this->slug = str_replace('.', '-', $ratio);
    }

    public function isInRange(float $ratio): bool
    {
        return ($ratio > $this->min && $ratio < $this->max);
    }
}
