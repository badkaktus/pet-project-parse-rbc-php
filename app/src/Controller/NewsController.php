<?php

/*
 * Created by Nickolay Sinyukhin on 03.04.2020 00:36
 * Copyright (c) 03.04.2020 00:36. All right reserved
 *
 * Last modified 03.04.2020 00:36 
 *
 * ¯\_(ツ)_/¯
 */


namespace App\Controller;


class NewsController extends BaseController
{

    public function exec(array $args = []): void
    {
        if (isset($args['id'])) {
            $this->single((int)$args['id']);
            return;
        }

        $this->all();
    }

    private function all(): void
    {
        $news = $this->di['db']->fetchAll('SELECT * FROM rbc_parse.latest_news');

        echo $this->di['twig']->render('all.twig', ['news' => $news]);
    }

    private function single(int $id): void
    {

        $news = $this->di['db']->fetchAssoc(
            'SELECT * FROM rbc_parse.latest_news WHERE id = ?',
            [$id]
        );

        echo $this->di['twig']->render('single.twig', ['news' => $news]);
    }
}