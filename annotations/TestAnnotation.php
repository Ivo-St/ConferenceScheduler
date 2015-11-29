<?php

class TestAnnotation extends Annotation
{
    public function execute()
    {
        parent::execute();
        var_dump('test');
    }
}