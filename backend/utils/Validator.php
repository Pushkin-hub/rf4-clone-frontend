<?php
class Validator {
    public function validate($data, $rules) {
        foreach ($rules as $field => $ruleString) {
            if (!isset($data[$field]) && strpos($ruleString, 'required') !== false) {
                return "Field {$field} is required";
            }
            
            if (isset($data[$field])) {
                $value = $data[$field];
                $rules = explode('|', $ruleString);
                
                foreach ($rules as $rule) {
                    if ($rule === 'required' && empty($value)) {
                        return "Field {$field} is required";
                    }
                    
                    if (strpos($rule, 'min:') === 0) {
                        $min = intval(substr($rule, 4));
                        if (strlen($value) < $min) {
                            return "Field {$field} must be at least {$min} characters";
                        }
                    }
                    
                    if (strpos($rule, 'max:') === 0) {
                        $max = intval(substr($rule, 4));
                        if (strlen($value) > $max) {
                            return "Field {$field} must be less than {$max} characters";
                        }
                    }
                    
                    if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return "Field {$field} must be a valid email address";
                    }
                    
                    if ($rule === 'numeric' && !is_numeric($value)) {
                        return "Field {$field} must be a number";
                    }
                    
                    if (strpos($rule, 'min:') === 0 && is_numeric($value)) {
                        $min = floatval(substr($rule, 4));
                        if ($value < $min) {
                            return "Field {$field} must be at least {$min}";
                        }
                    }
                    
                    if ($rule === 'integer' && !is_int($value)) {
                        return "Field {$field} must be an integer";
                    }
                    
                    if (strpos($rule, 'in:') === 0) {
                        $allowedValues = explode(',', substr($rule, 3));
                        if (!in_array($value, $allowedValues)) {
                            return "Field {$field} must be one of: " . implode(', ', $allowedValues);
                        }
                    }
                    
                    if ($rule === 'array' && !is_array($value)) {
                        return "Field {$field} must be an array";
                    }
                }
            }
        }
        
        return true;
    }
}
