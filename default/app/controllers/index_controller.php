<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class IndexController extends AppController
{
    public function index()
    {
        $this->title = "ChadFilms";
        $this->subtitle = "MOST EPIC FILMS OF ALL TIME !!";

        $this->randomFilms = (new Film)->find('order: RAND()','limit: 10');
        $this->randomCategories = (new Category)->find('order: RAND()','limit: 3');
        $this->randomFilmsByCategory = array();
        foreach ($this->randomCategories as $i => $c):
            $this->randomFilmsByCategory[$i] =
                (new FilmCategory)->find('conditions: category_id = '.$c->id, 'order: RAND()',
                    'limit: 5');
            foreach ($this->randomFilmsByCategory[$i] as $j => $cf):
                $this->randomFilmsByCategory[$i][$j] = $cf->getFilm();
            endforeach;
        endforeach;
        $this->newestFilms = (new Film)->find('order: id DESC','limit: 5');
        $this->popularFilms = (new Film)->find('order: views DESC','limit: 5');
//        echo '<pre>'; var_dump(Auth::get_active_identity()); echo '</pre>';
    }

    public function logout()
    {
        Auth::destroy_identity();
        Redirect::to('login');
        return false;
    }
}
