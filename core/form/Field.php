<?php

namespace app\core\form;

use app\models\Model;

class Field
{
    // constants for html input types
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_FILE = 'file';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';
    const TYPE_SELECT = 'select';
    const TYPE_SUBMIT = 'submit';
    const TYPE_RESET = 'reset';
    const TYPE_BUTTON = 'button';
    const TYPE_IMAGE = 'image';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIME = 'time';
    const TYPE_MONTH = 'month';
    const TYPE_WEEK = 'week';
    const TYPE_NUMBER = 'number';
    const TYPE_EMAIL = 'email';
    const TYPE_URL = 'url';
    const TYPE_SEARCH = 'search';


    public string $type;
    public Model $model;

    public string $attribute;

    // contstructor
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
    }

    // to string
    public function __toString(): string
    {
        return sprintf('
        <div class="mb-3">
            <label for="%s" class="form-label">%s</label>
            <input type="%s" class="form-control %s" id="%s" name="%s" value="%s">
            <div class="invalid-feedback">
                %s
            </div>
        </div>',
        $this->attribute, 
        $this->model->getLabel($this->attribute), 
        $this->type,
        $this->model->hasError($this->attribute) ? 'is-invalid' : '',
        $this->attribute, 
        $this->attribute, 
        $this->model->{$this->attribute}, 
        $this->model->getErrors($this->attribute));
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
}