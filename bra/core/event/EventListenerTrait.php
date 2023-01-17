<?php

namespace Bra\core\event;

use Exception;

trait EventListenerTrait {
    public array $events = [];
    public array $listeners = [];

    public function listen (Observer $listener, $event_name) {
        $this->listeners[$event_name] = $this->listeners[$event_name] ?? [];
        array_push($this->listeners[$event_name], $listener);
    }

    public function cut_off_listener ($event_name, Observer $from_listener) {
        foreach ($this->listeners[$event_name] as $k => $listener) {
            if (spl_object_hash($from_listener) == spl_object_hash($listener)) {
                unset($this->listeners[$event_name][$k]);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function event_happened ($event_name, ...$arguments) {
        if (!isset($this->listeners[$event_name])) {
            hour_log("Unknown event: " . $event_name , chl: "event");
            return [];
        }
        $res = [];
        foreach ($this->listeners[$event_name] as $listener) {
            $res[] = $listener->treat_event($event_name, ...$arguments);
        }
        if(count($res) == 1){
            return $res[0];
        }else{
            return $res;
        }
    }
}
