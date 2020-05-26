<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OnlyDomain extends Constraint
{

    public $message = 'L\'adresse email doit se terminer par @deloitte.com';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
?>

