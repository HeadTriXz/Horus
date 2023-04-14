<?php

namespace Horus\Core\View;

use InvalidArgumentException;

class View
{
    protected const ARG_REGEX = "\s*\(['\"]?(.*?)['\"]?,?\s*(\[[^)]*)?\)";
    protected const CALL_REGEX = "\s*\(((?:(?:[^()]+|\((?:[^()]+|(?1))*\))*)+)\)/";

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
        $content = preg_replace("/\{\{\s*(.*?)\s*}}/", "<?php echo htmlspecialchars(\$1, ENT_QUOTES); ?>", $content);
        $content = preg_replace("/\{!!\s*(.*?)\s*!!}/", "<?php echo \$1; ?>", $content);

        // If-statements
        $content = preg_replace("/@if" . self::CALL_REGEX, "<?php if(\$1): ?>", $content);
        $content = preg_replace("/@elseif" . self::CALL_REGEX, "<?php elseif(\$1): ?>", $content);
        $content = str_replace("@else", "<?php else: ?>", $content);
        $content = str_replace("@endif", "<?php endif; ?>", $content);

        // For-loop
        $content = preg_replace("/@foreach". self::CALL_REGEX, "<?php foreach(\$1): ?>", $content);
        $content = str_replace("@endforeach", "<?php endforeach; ?>", $content);

        // Imports
        $content = preg_replace("/@use" . self::CALL_REGEX, "<?php use \$1; ?>", $content);
        $content = preg_replace_callback("/@include" . self::ARG_REGEX . "/", function ($matches) use ($data) {
            $params = [];
            if (!empty($matches[2])) {
                $params = eval("return {$matches[2]};");
            }

            return static::render($matches[1], array_merge($data, $params));
        }, $content);

        // Layout
        $content = preg_replace_callback("/@layout" . self::ARG_REGEX . "([\s\S]*?)@endlayout/", function ($matches) use ($data) {
            return static::replaceComponent("Layouts/", $matches, $data);
        }, $content);

        // Component
        $content = preg_replace_callback("/@component" . self::ARG_REGEX . "([\s\S]*?)@endcomponent/", function ($matches) use ($data) {
            return static::replaceComponent("Components/", $matches, $data);
        }, $content);

        return $content;
    }

    protected static function replaceComponent(string $path, array $matches, array $data): string
    {
        $params = [];
        if (!empty($matches[2])) {
            extract($data, EXTR_SKIP);
            $params = eval("return {$matches[2]};");
        }

        $childContent = $matches[3];
        $childData = array_merge($data, $params, ["__blockContent" => []]);

        $childContent = preg_replace_callback("/@block\s*\(['\"]?(.*?)['\"]?\)([\s\S]*?)@endblock/", function ($matches) use (&$childData) {
            $blockName = $matches[1];
            $blockContent = $matches[2];

            if (!isset($childData["__blockContent"][$blockName])) {
                $childData["__blockContent"][$blockName] = "";
            }

            $childData["__blockContent"][$blockName] .= $blockContent;
            return "";
        }, $childContent);

        $parentContent = static::render($path . $matches[1], $childData);
        $content = str_replace("@content()", $childContent, $parentContent);
        foreach ($childData["__blockContent"] as $blockName => $blockContent) {
            $content = preg_replace("/@content\s*\(['\"]?" . $blockName . "['\"]?\)/", $blockContent, $content);
        }

        return $content;
    }
}
