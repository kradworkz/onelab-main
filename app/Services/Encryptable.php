<?php


namespace App\Services;
use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable) && !empty($value)) {
            $value = Crypt::decrypt($value);
            return $value;
        }
        
        return $value;
        
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray(); // call the parent method

        foreach ($this->encryptable as $key) {

            if (isset($attributes[$key])){

                $attributes[$key] = Crypt::decrypt($attributes[$key]);

            }
        }
        return $attributes;
    }
   
}
