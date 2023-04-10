<?php

namespace Horus\Core\View;

use InvalidArgumentException;

class View
{
    public static function render(string $view, array $data = []): string
    {
        $file = dirname(__DIR__, 2) . "/Views/" . $view;
        if (!file_exists($file)) {
            throw new InvalidArgumentException("View {$view} not found");
        }

        $content = static::replacePlaceholders(file_get_contents($file), $data);

        extract($data, EXTR_SKIP);
        ob_start();

        eval(" ?>" . $content . "<?php ");

        return ob_get_clean();
    }

    public static function replacePlaceholders(string $content, array $data = []): string
    {
        // Placeholders
        $content = preg_replace('/\{\{\s*(.*?)\s*}}/', '<?php echo htmlspecialchars($1, ENT_QUOTES); ?>', $content);
        $content = preg_replace('/\{!!\s*(.*?)\s*!!}/', '<?php echo $1; ?>', $content);

        // If-statements
        $content = preg_replace('/@if\s*\(((?:(?:[^()]+|\((?:[^()]+|(?1))*\))*)+)\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@elseif\s*\(((?:(?:[^()]+|\((?:[^()]+|(?1))*\))*)+)\)/', '<?php elseif($1): ?>', $content);
        $content = preg_replace('/@else/', '<?php else: ?>', $content);
        $content = preg_replace('/@endif/', '<?php endif; ?>', $content);

        // For-loop
        $content = preg_replace('/@foreach\s*\(((?:(?:[^()]+|\((?:[^()]+|(?1))*\))*)+)\)/', '<?php foreach($1): ?>', $content);
        $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

        // Imports
        $content = preg_replace('/@use\s*\(((?:(?:[^()]+|\((?:[^()]+|(?1))*\))*)+)\)/', '<?php use $1; ?>', $content);
        $content = preg_replace_callback('/@include\s*\([\'"]?(.*?)[\'"]?,?\s*(\[[^)]*)?\)/', function ($matches) use ($data) {
            $params = [];
            if (!empty($matches[2])) {
                extract($data, EXTR_SKIP);
                $params = eval("return {$matches[2]};");
            }

            return static::render($matches[1], array_merge($data, $params));
        }, $content);

        // Layout
        $content = preg_replace_callback('/@layout\s*\([\'"]?(.*?)[\'"]?,?\s*(\[[^)]*)?\)([\s\S]*?)@endlayout/', function ($matches) use ($data) {
            $params = [];
            if (!empty($matches[2])) {
                extract($data, EXTR_SKIP);
                $params = eval("return {$matches[2]};");
            }

            $content = static::render('Layouts/' . $matches[1], array_merge($data, $params));
            return str_replace('@content', $matches[3], $content);
        }, $content);

        // Component
        $content = preg_replace_callback('/@component\s*\([\'"]?(.*?)[\'"]?,?\s*(\[[^)]*)?\)([\s\S]*?)@endcomponent/', function ($matches) use ($data) {
            $params = [];
            if (!empty($matches[2])) {
                extract($data, EXTR_SKIP);
                $params = eval("return {$matches[2]};");
            }

            $content = static::render('Components/' . $matches[1], array_merge($data, $params));
            return str_replace('@content', $matches[3], $content);
        }, $content);

        return $content;
    }
}
