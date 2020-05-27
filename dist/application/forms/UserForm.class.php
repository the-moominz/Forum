<?php

class UserForm extends Form
{
    public function build()
    {

        $this->addFormField('pseudo');
        $this->addFormField('birthDate');
        $this->addFormField('mail');

    }

}
