<?php

namespace Carnet;

class Movie
{
    public readonly string $id;

    public readonly string $originalTitle;
    
    public readonly string $title;

    public readonly int $year;

    /** @var string[] $directors */
    public readonly array $directors;

    public readonly ?string $dop;

    public readonly string $imdb;
    public readonly ?string $wiki;
    public readonly ?string $tmdb;

    public function __construct(string $id, array $json) {
        $this->id = $id;
        $this->title = $json['title'] ?? $json['originalTitle'];
        $this->originalTitle = $json['originalTitle'];
        $this->year = $json['year'];
        $this->directors = $json['directors'] ?? [];
        $this->dop = $json['dop'] ?? null;
        $this->imdb = $json['imdb'];
        $this->tmdb = $json['tmdb'] ?? null;
        $this->wiki = $json['wiki'] ?? null;
    }
}
