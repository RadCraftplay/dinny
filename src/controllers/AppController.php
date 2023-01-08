<?php

class AppController {

    protected function errorIfFalseWithMessage(bool $condition, string $message) {
        if (!$condition) {
            $this->render_error($message, 400);
        }
    }

    protected function errorIfFalseWithMessageAndCode(bool $condition, string $message, int $code) {
        if (!$condition) {
            $this->render_error($message, $code);
        }
    }

    protected function render_error(string $message, int $code) {
        $this->render('error', ["message" => $message]);
        http_response_code($code);
        die();
    }

    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/' . $template . '.php';
        $output = 'File not found';

        if (file_exists($templatePath)) {
            extract($variables);

            function printvarf(string $format, string $varname, array $vars) {
                if (array_key_exists($varname, $vars)) {
                    $var = $vars[$varname];
                    echo sprintf($format, $var);
                } else {
                    echo "";
                }
            }

            function printvarfordefault(string $format, string $varname, string $default, array $vars) {
                if (array_key_exists($varname, $vars)) {
                    $var = $vars[$varname];
                    echo sprintf($format, $var);
                } else {
                    echo $default;
                }
            }

            function getServiceTypeIcon(int $serverType, array $service_types): string {
                foreach ($service_types as $type) {
                    if ($type->getServiceTypeId() == $serverType) {
                        return $type->getServiceImageName();
                    }
                }

                return "other.svg";
            }

            function printTableIfServersProvidedOrDefault(array $servers, array $service_types, string $default) {
                if (count($servers) == 0) {
                    echo $default;
                    return;
                }

                echo '
                    <table>
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>';

                foreach ($servers as $server) {
                    echo sprintf("
                        <tr>
                            <td>
                                <a href=\"/server?id=%s\">
                                    <img src=\"public/img/svg/server-types/%s\">
                                </a>
                            </td>
                            <td><a class=\"server-entry\" href=\"/server?id=%s\">%s</a></td>
                        </tr>",
                        $server->getSubmissionId(),
                        getServiceTypeIcon($server->getServiceTypeId(), $service_types),
                        $server->getSubmissionId(),
                        $server->getTitle());
                }

                echo '
                    </tbody>
                    </table>';
            }

            if (session_status() != PHP_SESSION_ACTIVE){
                session_start();
            }

            $vars = get_defined_vars();

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }
}