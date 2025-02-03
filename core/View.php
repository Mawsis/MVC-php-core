<?php
namespace app\core;
class View{
    public string $title = '';
    public function renderView($view,$params=[]){
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view,$params);
        return str_replace("{{content}}",$viewContent,$layoutContent);
    }

    protected function layoutContent() {
        $layout = Application::$app->controller->layout ?? 'main';
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view,$params=[]) {
        extract($params);
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}