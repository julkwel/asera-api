<?php
/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 31/12/2023
 */

namespace App\Constant;

class JobConstant
{
    public const JOB_TYPE = [
      1 => 'Informatique',
      2 => 'Assistant projet',
      3 => 'Développeur',
      4 => 'Admin réseaux système',
      5 => 'Call center',
    ];

    public const JOB_TYPE_CONSTRAINT = [1, 2, 3, 4, 5,];
}