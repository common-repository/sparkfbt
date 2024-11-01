<?php

namespace Sparkfbt\MathPHP\LinearAlgebra\Decomposition;

use Sparkfbt\MathPHP\LinearAlgebra\NumericMatrix;
abstract class Decomposition
{
    /**
     * @param NumericMatrix $M
     * @return static
     */
    public static abstract function decompose(NumericMatrix $M);
}
