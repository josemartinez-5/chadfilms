<?php

class FilmViews extends ActiveRecord
{
    public function initialize()
    {
        $this->belongs_to('film', 'model: Film', 'fk: film_id');
    }
}