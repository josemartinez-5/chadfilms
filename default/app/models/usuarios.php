<?php
class Usuarios extends ActiveRecord
{
    public function initialize()
    {
        $this->has_many('filmComments', 'model: FilmComments', 'fk: user_id');
    }
}
