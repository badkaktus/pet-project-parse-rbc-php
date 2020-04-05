<?php

/*
 * Created by Nickolay Sinyukhin on 03.04.2020 08:19
 * Copyright (c) 03.04.2020 08:19. All right reserved
 *
 * Last modified 03.04.2020 08:19 
 *
 * ¯\_(ツ)_/¯
 */


namespace App\Controller;


use Pimple\Container;

abstract class BaseController
{
    public $di;

    public function __construct(Container $di)
    {
        $this->di = $di;
    }
}