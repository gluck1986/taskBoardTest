<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 06.04.2018
 * Time: 9:15
 */
declare(strict_types=1);

namespace Framework\Services;

use Nette\Utils\Validators;

class Validator
{
    /**
     * @param $dtoObject
     * @param $rules ValidateRule[]
     * @param bool $allowNull
     * @return array
     */
    public function validate($dtoObject, $rules, bool $allowNull = false): array
    {
        $result = [];
        foreach ($rules as $rule) {
            $value = $dtoObject->{$rule->field};
            if ($allowNull && (is_null($value) || $value === '')) {
                continue;
            }
            if (is_callable($rule->closure)) {
                $callResult = call_user_func($rule->closure, $value, $dtoObject, $rule);
                if ($callResult) {
                    $result[] = $callResult;
                }
            } else {
                if (!Validators::is($value, $rule->rule)) {
                    $result[] = $rule->message;
                }
            }
        }

        return $result;
    }
}
