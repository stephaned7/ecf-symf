<?php
namespace App\DTO;

use Symfony\Component\Form\DataTransformerInterface;

class RolesArrayConverter implements DataTransformerInterface
{
    public function transform($rolesArray): ?string
    {
        // Transform the array into a single role string
        return is_array($rolesArray) && count($rolesArray) ? $rolesArray[0] : null;
    }

    public function reverseTransform($roleString): array
    {
        // Transform the role string back into an array
        return [$roleString];
    }
}