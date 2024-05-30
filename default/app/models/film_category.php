<?php
class FilmCategory extends ActiveRecord{
    public function initialize()
    {
        $this->belongs_to('film', 'model: Film', 'fk: film_id');
        $this->belongs_to('category', 'model: Category', 'fk: category_id');
    }
}
