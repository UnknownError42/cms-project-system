<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

/**
 * @author macosxvn
 * Date: 19/06/2017
 * Time: 16:47
 */

namespace Macosxvn\Debugbar\Events;

use Macosxvn\Debugbar\Debugbar;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;

class Manager extends Plugin {

    public function beforeSendResponse(Event $event) {
        /* @var $response \Phalcon\Http\Response */
        $response = $event->getData();
        $content = $response->getContent();

        /* @var $debugbar Debugbar */
        $debugbar = $this->getDI()->get(Debugbar::SERVICE_NAME);
        $renderer = $debugbar->getJavascriptRenderer(Debugbar::PUBLIC_URI);

        $content = str_replace("</head>", $renderer->renderHead() . "</head>", $content);
        $content = str_replace("</body>", $renderer->render() . "</body>", $content);
        $response->setContent($content);
    }
}