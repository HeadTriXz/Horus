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

        $content = self::replacePlaceholders(file_get_contents($file));

        extract($data, EXTR_SKIP);
        ob_start();

        eval(" ?>" . $content . "<?php ");

        return ob_get_clean();
    }

    public static function replacePlaceholders(string $content): string
    {
        // Placeholders
        $content = preg_replace('/\{\{\s*(.*?)\s*}}/', '<?php echo htmlspecialchars($1, ENT_QUOTES); ?>', $content);
        $content = preg_replace('/\{!!\s*(.*?)\s*!!}/', '<?php echo $1; ?>', $content);

        // If-statements
        $content = preg_replace('/@if\s*\((.*?)\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@elseif\s*\((.*?)\)/', '<?php elseif($1): ?>', $content);
        $content = preg_replace('/@else/', '<?php else: ?>', $content);
        $content = preg_replace('/@endif/', '<?php endif; ?>', $content);

        // For-loop
        $content = preg_replace('/@foreach\s*\((.*?)\)/', '<?php foreach($1): ?>', $content);
        $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

        // Imports
        $content = preg_replace('/@use\s*\((.*?)\)/', '<?php use ($1); ?>', $content);

        return $content;
    }
}
