<?php
/**
 * Copyright (c) 2017 Phalcon Debugbar
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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