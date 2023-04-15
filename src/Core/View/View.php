<?php

namespace Horus\Core\View;

use InvalidArgumentException;

/**
 * Utility class used for rendering views.
 */
class View
{
    /**
     * Regular expression pattern for matching "function" calls.
     *
     * @var string
     */
    protected const CALL_REGEX = "\s*\(((?:(?:[^()]+|\((?:[^()]+|(?1))*\))*)+)\)/";

    /**
     * Render the view with the given data.
     *
     * @param string $view The name of the view file to render.
     * @param array $data The data to be passed to the view file.
     *
     * @throws InvalidArgumentException if the view file does not exist.
     * @return string The rendered content of the view file.
     */
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

    /**
     * Replace the placeholders in the given content with the values from the data.
     *
     * @param string $content The content to replace placeholders in.
     * @param array $data The data to replace placeholders with.
     *
     * @return string The content with placeholders replaced by values.
     */
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
        $content = preg_replace_callback("/@include\s*\(['\"]?(.*?)['\"]?,?\s*(\[[^)]*)?\)/", function ($matches) use ($data) {
            $params = [];
            if (!empty($matches[2])) {
                extract($data, EXTR_SKIP);
                $params = eval("return {$matches[2]};");
            }

            return static::render($matches[1], array_merge($data, $params));
        }, $content);

        $content = self::replaceComponent("layout", "Layouts/", $content, $data);
        $content = self::replaceComponent("component", "Components/", $content, $data);

        return $content;
    }

    /**
     * Replace the component placeholders in the given content with the values from the data.
     *
     * @param string $type The type of component.
     * @param string $path The path to the component directory.
     * @param string $content The content to replace components in.
     * @param array $data The data to pass to the components being rendered.
     *
     * @return string The content with components replaced by their rendered contents.
     */
    protected static function replaceComponent(string $type, string $path, string $content, array $data): string
    {
        $pattern = "/@$type\s*\(\s*['\"]([^'\"]+)['\"](?:\s*,\s*(\[[^]]+]))?\s*\)((?:(?>[^@]+)|@(?!$type|end$type)|(?R))*)@end$type/";

        return preg_replace_callback($pattern, function ($matches) use ($path, $data) {
            $params = [];
            if (!empty($matches[2])) {
                extract($data, EXTR_SKIP);
                $params = eval("return {$matches[2]};");
            }

            $childData = array_merge($data, $params, ["__blockContent" => []]);
            $childContent = self::replacePlaceholders($matches[3], $childData);

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
        }, $content);
    }
}
