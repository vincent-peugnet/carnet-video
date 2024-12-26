<?php

namespace Carnet;

class Clip
{
    public readonly int $id;

    public readonly string $description;

    /** @var string[] $tags */
    public readonly array $tags;

    public readonly ?int $movie;

    public readonly ?float $aspectRatio;

    public readonly ?int $duration;

    public readonly ?string $color;

    public function __construct(int $id, array $json) {
        $this->id = $id;
        $this->description = $json['description'] ?? '';
        $this->tags = $json['tags'] ?? [];
        $this->movie = $json['movie'] ?? null;
        $this->aspectRatio = $json['aspectRatio'] ?? null;
        $this->duration = $json['duration'] ?? null;
        $this->color = $json['color'] ?? null;
    }
}
