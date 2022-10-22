<?php

namespace Bra\core\event;

interface Observer {

	function treat_event ($event_name , ...$arguments);
}
