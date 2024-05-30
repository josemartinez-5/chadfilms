<?php

class GenresController extends AppController {
    public function index()
    {
        $this->title = "ChadFilms - Genres";
        $this->subtitle = "WE'VE GOT ALL POSSIBLE GENRES!!!";
        $this->categories = (new Category)->find('order: RAND()');
        $this->randomFilmsByCategory = array();
        foreach ($this->categories as $i => $c):
            $this->randomFilmsByCategory[$i] =
                (new FilmCategory)->find('conditions: category_id = '.$c->id, 'order: RAND()',
                    'limit: 5');
            foreach ($this->randomFilmsByCategory[$i] as $j => $cf):
                $this->randomFilmsByCategory[$i][$j] = $cf->getFilm();
            endforeach;
        endforeach;
    }

    public function show($id)
    {
        $this->category = (new Category())->find($id);
        $this->title = "ChadFilms - " . $this->category->name;
        $this->subtitle = strtoupper($this->category->name) . '!!!';

        foreach ($this->category->getCategoryFilms() as $cf):
            $this->films[] = (new Film())->find($cf->film_id);
        endforeach;
    }
}