<?php
/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 14/09/16
 * Time: 18:27.
 */
namespace Traits;

use Silex\Application;

trait initializer
{
    /** @var $app Application */
    protected $app;
    protected $twig;
    protected $request;

    /**
     * Helpers methods.
     *
     * @param Application $app
     */
    public function initialize(Application $app)
    {
        $this->app = $app;
        $this->twig = $app['twig'];
        $this->data = $app['data'];
        $this->data['products-categories'] = array_column($app['data']['products'], 'category', 'id');
    }
}
