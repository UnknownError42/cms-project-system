<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace DebugBar\DataCollector;

interface MessagesAggregateInterface
{
    /**
     * Returns collected messages
     *
     * @return array
     */
    public function getMessages();
}
