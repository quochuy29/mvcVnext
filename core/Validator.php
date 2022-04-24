<?php

namespace App\core;

class Validator
{
    protected $request;
    protected $rule;
    protected $message;
    public $errors = [];
    public $requestCore;

    public function __construct()
    {
        $this->requestCore = new Request();
    }

    public function validate($request = [], $rule = [], $message = [])
    {
        $this->request = $this->handleRequest($request);
        $this->message = $this->handleMessage($message);
        $this->rule = $this->handleRule($rule);
    }

    public function handleRequest($request)
    {

        return $request;
    }

    public function handleRule($rule)
    {
        $dataField = $this->request;
        if (!empty($rule)) {
            foreach ($rule as $fieldKey => $value) {
                if (strpos($value, 'regex') !== false) {
                    $ruleField = preg_split('/\|/', $value, 2);
                } else {
                    $ruleField = explode('|', $value);
                }

                foreach ($ruleField as $key => $valueRule) {
                    $ruleValue = explode(':', $valueRule);
                    $ruleName = reset($ruleValue);
                    $this->$ruleName($dataField, end($ruleValue), $ruleName, $fieldKey);
                }
            }
        }
    }

    public function handleMessage($message)
    {
        return $message;
    }

    public function min($data, $value = '', $ruleName, $fieldKey)
    {
        if ($data[$fieldKey] < $value) {
            $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
        }
    }

    public function max($data, $value = '', $ruleName, $fieldKey)
    {
        if (is_array($data[$fieldKey])) {
            foreach ($data[$fieldKey] as $key => $value) {
                if (is_int($data[$fieldKey][$key])) {
                    if ($data[$fieldKey][$key] > $value) {
                        $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
                    }
                }
            }
        } else {
            if ($data[$fieldKey] > $value) {
                $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
            }
        }
    }

    public function email($data, $value = '', $ruleName, $fieldKey)
    {
        if (!filter_var($data[$fieldKey], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
        }
    }

    public function regex($data, $value = '', $ruleName, $fieldKey)
    {
        if (!preg_match($value, $data[$fieldKey])) {
            $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
        }
    }

    public function mimes($data, $value = '', $ruleName, $fieldKey)
    {
        $mimes = explode(',', $value);
        $type = explode('/', $data[$fieldKey]['type']);

        if (isset($type[1])) {
            if (!in_array($type[1], $mimes)) {
                $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
            }
        }
    }

    public function required($data, $value = '', $ruleName, $fieldKey)
    {
        if (is_array($data[$fieldKey])) {
            foreach ($data[$fieldKey] as $key => $value) {
                if (is_string($data[$fieldKey][$key])) {
                    if (empty($data[$fieldKey][$key])) {
                        $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
                    }
                }
            }
        } else {
            if (empty($data[$fieldKey])) {
                $this->errors[$fieldKey][$ruleName] = $this->message[$fieldKey . '.' . $ruleName];
            }
        }
    }

    public function errors()
    {
        if (!empty($this->errors)) {
            $firstErrors = [];
            foreach ($this->errors as $key => $value) {
                $firstErrors[$key] = reset($value);
            }
            return $firstErrors;
        }
    }

    public function fails()
    {
        if (!empty($this->errors)) {
            return true;
        }
        return false;
    }
}
