<?php

namespace A2Workspace\ModelBuilder\Exceptions;

class CallSubtaskException extends ModelBuilderException
{
    /**
     * @var string
     */
    protected $message = '無法處理子任務。';
}
