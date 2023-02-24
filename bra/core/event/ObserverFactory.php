<?php

namespace Bra\core\event;

abstract class ObserverFactory implements Observer {

    function treat_event($event_name, ...$arguments) {
        if (method_exists($this, $event_name)) {
            return $this->$event_name(...$arguments);
        } else {
            abort(bra_res(500, "Event $event_name registered,But not implemented"));
        }
    }
}
