<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 *  Formats custom violations array
 */
class ValidatorViolationAggregator
{
    public function getViolations(ConstraintViolationListInterface $errors): ?array
    {
        foreach ($errors as $e) {
            $errorBag['errors'][$e->getPropertyPath()][] = $e->getMessage();
            $errorBag['errors'][$e->getPropertyPath()][] = $e->getCode();
        }
        return $errorBag;
    }
}