<?php

class FilmComments extends ActiveRecord
{
    public function initialize()
    {
        $this->belongs_to('film', 'model: Film', 'fk: film_id');
        $this->belongs_to('customer', 'model: Customer', 'fk: user_id');
    }

    public function getTime(){
        $createdTimestamp = strtotime($this->created_at);
        $x_ago = time() - $createdTimestamp;
        if ($x_ago < 60) {
            return $x_ago . 's ago';
        }
        elseif ($x_ago < 3600){
            return intdiv($x_ago,60) . 'min ago';
        }elseif ($x_ago < 86400){
            return intdiv($x_ago,3600) . 'h ago';
        }
        elseif ($x_ago < 604800){
            return intdiv($x_ago,86400) . ' day(s) ago';
        }
        elseif (date('z') < date('z',$createdTimestamp)){
            return date('d M',$createdTimestamp);
        }
        return date('d M Y',$createdTimestamp);
    }
}