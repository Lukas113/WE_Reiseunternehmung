<?php

namespace views;

/**
 * Description of LayoutRendering
 *
 * @author Andreas Martin
 */
class LayoutRendering {
    public static function basicLayout(TemplateView $contentView, String $header = "headerAdminLoggedIn"){
        $view = new TemplateView("layout.php");
        $view->header = (new TemplateView($header.".php"))->render();
        $view->content = $contentView->render();
        $view->footer = (new TemplateView("footer.php"))->render();
        echo $view->render();
    }
}
