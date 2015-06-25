<?php
/**
 * Created by PhpStorm.
 * User: c3zi
 * Date: 25/06/15
 * Time: 06:54
 */

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Token extends Constraint
{
    public $message = 'The token "%string%" is not correct.';

    public function validatedBy()
    {
        return 'app.validator.token';
    }
}