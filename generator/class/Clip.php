<?php

namespace Carnet;

use RuntimeException;

class Clip
{
    public readonly int $id;

    public readonly string $description;

    /** @var string[] $tags */
    public readonly array $tags;

    public readonly ?string $movie;

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

    /**
     * @throws RuntimeException             If it's impossible to get thumbnail's height
     */
    public function getThumbnailHeight(): int
    {
        $id = $this->id;
        $filePath = "build/assets/thumbnail/$id.webp";
        $imageSize = getimagesize($filePath);
        if ($imageSize !== false) {
            $height = $imageSize[1];
            return $height;
        } elseif ($this->aspectRatio !== null) {
            return floor(298 / ($this->aspectRatio ?? 3));
        } else {
            throw new RuntimeException('impossible to get thumbnail height');
        }
    }
}
