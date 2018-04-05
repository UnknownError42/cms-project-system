<?php
namespace Core\Flash;

class Message {

    public $type;
    public $message;

    public function __construct($type, $message) {
        $this->type     = $type;
        $this->message  = $message;
    }

    public function render() {
        return '<div class="alert alert-'.$this->type.' alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p>' . $this->message . '</p>
        </div>';
    }
}
?>