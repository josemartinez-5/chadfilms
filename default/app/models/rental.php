<?php

class Rental extends ActiveRecord
{
    public function initialize()
    {
        $this->belongs_to('customer', 'model: Customer', 'fk: customer_id');
        $this->belongs_to('payment', 'model: Payment', 'fk: payment_id');
        $this->has_many('rentalItems', 'model: RentalItems', 'fk: rental_id');
    }

    /**
     * Añade una película al carro (registro en la tabla rental_items).
     * Actualiza el total del carro
     * Retorna un valor booleano de éxito o error
     *
     * @return bool
     */
    public function addItem($film_id){
        $item = new RentalItems();
        $item->rental_id = $this->id;
        $item->inventory_id = $film_id;
        if ($item->create()){
            $this->total += $item->getFilm()->cost;
            if($this->update())
                return true;
        }
        else
            return false;
    }

    /**
     * Devuelve la lista de películas asociadas al carro o compra
     *
     * @return array
     */
    public function getItems(){
        $rentalItems = $this->getRentalItems();
        $films = array();
        foreach ($rentalItems as $ri) {
            $films[] = $ri->getFilm();
        }
        return $films;
    }

    /**
     * Realiza el pago de las películas en el carro
     *
     * @return bool
     */
    public function makePayment(){
        if ($this->payment_id)
            return false;
        $payment = new Payment();
        $payment->customer_id = $this->customer_id;
        $payment->amount = $this->total;
        if (!($payment->create()))
            return false;
        $this->payment_id = $payment->id;
        $this->rental_date = $payment->payment_date;
        if ($this->update())
            return true;
        else{
            $payment->delete();
            return false;
        }
    }

    /**
     * Revisa si una película se encuentra añadida al carro
     *
     * @return bool
     */
    public function filmInCart($id){
        $item = (new RentalItems)->find_first(
            'conditions: rental_id = '.$this->id.' AND inventory_id = '.$id
        );
        if ($item) {
            return true;
        } else {
            return false;
        }
    }
}