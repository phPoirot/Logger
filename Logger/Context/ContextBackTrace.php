<?php
namespace Poirot\Logger\Logger\Context;

use Poirot\Logger\Interfaces\iContext;

class ContextBackTrace
    extends    aContext
    implements iContext
{
    /**
     * Get BackTrace Data Context
     *
     */
    function getBackTrace()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        array_shift($trace);

        $i = 0;
        while (
            isset($trace[$i]['class'])
            ## ignore Logger Traces
            && strpos($trace[$i]['class'], 'Poirot\\Logger') !== false
        )
            $i++;

        $data = array(
            'file'     => isset($trace[$i-1]['file'])   ? $trace[$i-1]['file']   : null,
            'line'     => isset($trace[$i-1]['line'])   ? $trace[$i-1]['line']   : null,
            'class'    => isset($trace[$i]['class'])    ? $trace[$i]['class']    : null,
            'function' => isset($trace[$i]['function']) ? $trace[$i]['function'] : null,
        );

        return $data;
    }
}
