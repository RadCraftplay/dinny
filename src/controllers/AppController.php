<?php

class AppController {

    private $request;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
    }

    protected function isPost(): bool {
        return $this->request === 'POST';
    }

    protected function isGet(): bool {
        return $this->request === 'GET';
    }

    protected function errorIfFalseWithMessage(bool $condition, string $message) {
        if (!$condition) {
            $this->render('error', ["message" => $message]);
            http_response_code(400);
            die();
        }
    }

    protected function errorIfFalseWithMessageAndCode(bool $condition, string $message, int $code) {
        if (!$condition) {
            $this->render('error', ["message" => $message]);
            http_response_code($code);
            die();
        }
    }

    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/' . $template . '.php';
        $output = 'File not found';

        if (file_exists($templatePath)) {
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }
}