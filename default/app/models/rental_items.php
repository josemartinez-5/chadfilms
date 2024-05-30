<?php

class RentalItems extends ActiveRecord
{
    public function initialize()
    {
        $this->belongs_to('rental', 'model: Rental', 'fk: rental_id');
        $this->belongs_to('film', 'model: Film', 'fk: inventory_id');
    }

    public function before_create()
    {
        if($this->find_first(
            'conditions: rental_id = '.$this->rental_id.' AND inventory_id = '.$this->inventory_id
        )){
            return 'cancel';
        }
    }

    public function before_delete()
    {
        $cart = $this->getRental();
        $cart->total -= $this->getFilm()->cost;
        if(!($cart->update())){
            return 'cancel';
        }
    }
}