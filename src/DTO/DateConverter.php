<?php

namespace App\DTO;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateConverter implements DataTransformerInterface
{
    public function transform($datetime): string
    {
        if ($datetime === null) {
            return '';
        }

        return $datetime->format('d/m/Y');
    }

    public function reverseTransform($dateString): ?\DateTime
    {
        if (!$dateString) {
            return null;
        }

        $date = \DateTime::createFromFormat('d/m/Y', $dateString);

        if ($date === false) {
            throw new TransformationFailedException('Format invalide.');
        }

        return $date;
    }
}