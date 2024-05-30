<?php
class FilmController extends AppController
{
    //Action
    //View: app/views/film/index.phtml
    //url: KumbiaPHP/film
    public function index()
    {
        $this->title = "Film";
        $this->subtitle = "Lista de películas";
        $this->films = (new Film())->find();
    }

    public function create()
    {
        $this->title = "Film";
        $this->subtitle = "Registro de nueva película";
        $this->film = new Film();

        if(input::hasPost("film")){
            $params = Input::post("film");
            if(($_FILES["pic"]["name"] && $this->film->saveWithPhoto($params)) ||
                $this->film->save($params)){
                Flash::valid("Se guardó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function show($id)
    {
        $this->film = (new Film())->find($id);
        $this->title = "ChadFilms - " . $this->film->title;
        $this->subtitle = 'BEST FILM EVER!!!';
        $this->customer = (new Customer)->find($this->user['id']);
        $this->cart = $this->customer->getCart();
        $this->purchased = $this->customer->filmPurchased($this->film->id);
        if (!($this->purchased)){
            $this->inCart = $this->cart->filmInCart($this->film->id);
        }

        if(Input::hasPost("pay")) {
            $this->cart->addItem($this->film->id);
            Redirect::to("/customer/checkout");
        }
    }

    public function edit($id)
    {
        $this->title = "Film";
        $this->subtitle = "Editar película";
        $this->film = (new Film())->find($id);

        if(Input::hasPost("film")){
            $params = Input::post("film");
            if(($_FILES["pic"]["name"] && $this->film->saveWithPhoto($params)) ||
                $this->film->update($params)){
                Flash::valid("Se actualizó");
            }else{
                Flash::error("Error al guardar en BD");
            }
        }
    }

    public function play($id)
    {
        $this->film = (new Film())->find($id);
        $this->title = "ChadFilms - " . $this->film->title . " - WATCH NOW";
        $this->subtitle = "WATCHING RIGHT NOW: " . $this->film->title;
        $this->customer = (new Customer)->find($this->user['id']);

        if (!$this->customer->filmPurchased($this->film->id)) {
            Redirect::to("/");
            return false;
        }

        if(Input::hasPost("film_comments")){
            $params = Input::post("film_comments");
            $this->filmComment = new FilmComments($params);
            $this->filmComment->user_id = $this->user['id'];
            $this->filmComment->film_id = $this->film->id;
            if($this->filmComment->save()){
                Flash::valid("Comentario enviado");
            }else{
                Flash::error("Error al enviar comentario");
            }
        }
        else{
            if(!$this->film->addView($this->user['id'])){
                Flash::error("Error al aumentar el contador de vistas");
            }
        }

        $this->filmSavedComments = (new FilmComments)->find(
            'conditions: film_id = '.$this->film->id, 'order: created_at DESC'
        );
    }
}