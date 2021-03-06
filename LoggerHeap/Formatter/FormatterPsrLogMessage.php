<?php
namespace Poirot\Logger\LoggerHeap\Formatter;

use Poirot\Logger\Interfaces\iContext;

/**
 * Processes a record's message according to PSR-3 rules
 *
 * It replaces {foo} with the value from $this->getFoo()
 */
class FormatterPsrLogMessage extends aFormatter
{
    // {} will replace by all extra data
    const DEFAULT_TEMPLATE = '{timestamp} ({level}): {message}, [{%}]';

    protected $template;

    /**
     * Format Data To String
     *
     * @param iContext $logData
     *
     * @return string
     */
    function toString(iContext $logData)
    {
        $template = $this->getTemplate();

        if (false === strpos($template, '{'))
            ## nothing can be replaced, so return template self
            return $template;

        $replacements = array();
        foreach ($logData as $key => $value) {
            $flatValue = $this->flatten($value);

            $repVar = '{'.$key.'}';
            if (strstr($template, $repVar) === false)
                ## the key not presented it assumed as extra data
                $replacements['{%}'][] = $key.': '.$flatValue;
            else
                $replacements[$repVar] = $flatValue;
        }

        if (isset($replacements['{%}']))
            $replacements['{%}'] = implode(', ', $replacements['{%}']);

        $return = strtr($template, $replacements);
        return $return;
    }


    // ..

    /**
     * @return string
     */
    function getTemplate()
    {
        if (!$this->template)
            $this->setTemplate(self::DEFAULT_TEMPLATE);

        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    function setTemplate($template)
    {
        $this->template = (string) $template;

        return $this;
    }
}
