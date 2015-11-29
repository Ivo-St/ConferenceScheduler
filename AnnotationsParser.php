<?php

class AnnotationsParser
{
    const DEFAULT_ANNOTATION_SUFFIX = 'Annotation';
    const BASE_ANNOTATION_CLASS_NAME = 'Annotation';
    const DEFAULT_ANNOTATION_ACTION_NAME = 'execute';
    const ANNOTATIONS_MATCH_REGEX = '/\@\s*(\w+)(?:\(([^)]*)?\))*\@*\n*/';

    public function __construct()
    {
        spl_autoload_register(array($this, 'loadAnnotation'));
    }

    public function parseActionAnnotations($className, $methodName)
    {
        $annotationsArray = $this->getActionComments($className, $methodName);
        if (!$annotationsArray) {
            return false;
        }

        for ($i = 0; $i < count($annotationsArray[1]); $i++) {
            $annotation = trim($annotationsArray[1][$i]);
            $arguments = explode(',', trim($annotationsArray[2][$i]));

            $this->executeAnnotation($annotation, $arguments);
        }
    }

    public function parseClassAnnotations($className){
        $annotationsArray = $this->getClassComments($className);
        if (!$annotationsArray) {
            return false;
        }

        for ($i = 0; $i < count($annotationsArray[1]); $i++) {
            $annotation = trim($annotationsArray[1][$i]);
            $arguments = explode(',', trim($annotationsArray[2][$i]));

            $this->executeAnnotation($annotation, $arguments);
        }
    }

    private function getClassComments($className){
        $reflector = new ReflectionClass($className);
        $comment = $reflector->getDocComment();
        if (!$comment) {
            return false;
        }

        $commentContent = trim(substr($comment, 3, -2));
        preg_match_all(self::ANNOTATIONS_MATCH_REGEX, $commentContent, $annotationsArray);

        return $annotationsArray;
    }

    private function getActionComments($className, $methodName)
    {
        $reflector = new ReflectionClass($className);
        $comment = $reflector->getMethod($methodName)->getDocComment();
        if (!$comment) {
            return false;
        }

        $commentContent = trim(substr($comment, 3, -2));
        preg_match_all(self::ANNOTATIONS_MATCH_REGEX, $commentContent, $annotationsArray);

        return $annotationsArray;
    }

    private function executeAnnotation($annotation, $annotationArguments)
    {
        $annotationClassName = ucfirst($annotation) . self::DEFAULT_ANNOTATION_SUFFIX;

        if (class_exists($annotationClassName) && is_subclass_of($annotationClassName, self::BASE_ANNOTATION_CLASS_NAME)) {
            call_user_func_array(array($annotationClassName, self::DEFAULT_ANNOTATION_ACTION_NAME), $annotationArguments);
        } else {
            die('Could not find annotation ' . $annotation);
        }
    }

    private function loadAnnotation($name)
    {
        if (file_exists("annotations/$name.php")) {
            include "annotations/$name.php";
        }
    }
}
