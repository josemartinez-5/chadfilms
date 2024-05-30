<?php
class Category extends ActiveRecord{
    public function initialize()
    {
        $this->has_many('categoryFilms', 'model: FilmCategory', 'fk: category_id');
    }
}
?>