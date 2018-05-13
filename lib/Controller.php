<?php

namespace lib;

use models\Layout;

abstract class Controller
{
    /* @var $router Router */
    protected $router;
    protected $model;
//    protected $data = [];
    protected $params = [];
    protected $layout;

    /**
     * @return DB
     */
    protected function getDB()
    {
        $params = Config::get('db');

        return new DB($params);
    }

    /**
     * @param string $message
     * @param string|null $type
     */
    protected function setFlash($message, $type = 'info') {
        /** @var Session $session */
        $session = Session::getInstance();
        $session->setFlash($message, $type);
    }

    /**
     * @param mixed $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params; // ???????????????????????
    }

    /**
     * @param mixed $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

//    /**
//     * @return array
//     */
//    public function getData()
//    {
//        return $this->data;
//    }

    /**
     * @param string $url
     * @param int $code
     */
//    protected function redirect($url, $code = 302)
    public function redirect($url, $code = 302)
    {
        Router::redirect($url, $code);
    }

    /**
     * @param string $tpl_name
     * @param array|null $data
     *
     * @return string
     *
     * @throws \Exception
     */
    public function render($tpl_name, array $data = null)
    {
        $view = new View($this->router);
        $tpl_file = $view->getView($tpl_name);
        $content = $view->render($tpl_file, $data);

        $layout = new View($this->router);
        $layout_name = empty($this->layout) ? $this->router->getRoute() : $this->layout;
        $layout_file = $layout->getLayout($layout_name);

        $arr_out['content'] = $content;

        if ($this->router->getRoute() === 'default') {
            $env = new Layout($this->getDB());
            $ads = $env->blockAd();
            $arr_out['menu'] = $env->fillMenu();
            $arr_out['ads_left'] = array_slice($ads, 0, 4);
            $arr_out['ads_right'] = array_slice($ads, 4, 4);
            $arr_out['hot_tags'] = $env->hotTags();
            $arr_out['top_users'] = $env->topUsers();
        }

        return $layout->render($layout_file, $arr_out);

//        return $layout->render($layout_file, [
//            'content' => $content,
//        ]);
    }
}
