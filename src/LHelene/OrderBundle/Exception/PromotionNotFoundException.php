<?php
namespace LHelene\OrderBundle\Exception;

class PromotionNotFoundException extends \Exception
{
    const MESSAGE = "\"%s\" code is an invalid promotion";

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        parent::__construct(sprintf(self::MESSAGE, $code));
    }
}
