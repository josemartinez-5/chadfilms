<?php
class Language extends ActiveRecord{
    public function initialize()
    {
        $this->has_many('films', 'model: Film', 'fk: language_id');
    }
}
?>
