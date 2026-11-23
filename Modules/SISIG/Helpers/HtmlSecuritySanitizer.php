<?php

namespace Modules\SISIG\Helpers;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSecuritySanitizer
{
    /**
     * Lista blanca de etiquetas HTML permitidas para contenidos didácticos.
     */
    protected static array $allowedTags = [
        'p', 'br', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup',
        'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
        'a', 'img', 'span', 'div', 'section', 'header', 'footer',
        'iframe', 'video', 'source', 'style'
    ];

    /**
     * Lista blanca de atributos permitidos por etiqueta.
     */
    protected static array $allowedAttributes = [
        'style'   => ['type', 'id'],
        'a'       => ['href', 'title', 'target', 'rel', 'class', 'style', 'id'],
        'img'     => ['src', 'alt', 'title', 'width', 'height', 'class', 'style', 'id', 'loading'],
        'table'   => ['class', 'style', 'border', 'cellpadding', 'cellspacing', 'id'],
        'th'      => ['class', 'style', 'colspan', 'rowspan', 'scope', 'id'],
        'td'      => ['class', 'style', 'colspan', 'rowspan', 'id'],
        'span'    => ['class', 'style', 'id'],
        'div'     => ['class', 'style', 'id', 'data-gjs-type', 'data-highlightable'],
        'section' => ['class', 'style', 'id'],
        'header'  => ['class', 'style', 'id'],
        'footer'  => ['class', 'style', 'id'],
        'p'       => ['class', 'style', 'id'],
        'h1'      => ['class', 'style', 'id'],
        'h2'      => ['class', 'style', 'id'],
        'h3'      => ['class', 'style', 'id'],
        'h4'      => ['class', 'style', 'id'],
        'h5'      => ['class', 'style', 'id'],
        'h6'      => ['class', 'style', 'id'],
        'ul'      => ['class', 'style', 'id'],
        'ol'      => ['class', 'style', 'id'],
        'li'      => ['class', 'style', 'id'],
        'code'    => ['class', 'style', 'id'],
        'pre'     => ['class', 'style', 'id'],
        'blockquote' => ['class', 'style', 'id'],
        'iframe'  => ['src', 'width', 'height', 'frameborder', 'allow', 'allowfullscreen', 'class', 'style', 'id', 'title', 'loading'],
        'video'   => ['src', 'controls', 'width', 'height', 'class', 'style', 'id', 'poster', 'autoplay', 'loop', 'muted', 'playsinline'],
        'source'  => ['src', 'type'],
    ];

    /**
     * Protocolos URI permitidos para atributos href y src.
     */
    protected static array $allowedProtocols = ['http', 'https', 'mailto', 'tel'];

    /**
     * Sanitiza una cadena HTML enriquecida eliminando scripts, etiquetas peligrosas,
     * manejadores de eventos (inline JS) y enlaces maliciosos.
     *
     * @param string|null $html Contenido HTML original
     * @return string|null Contenido HTML saneado
     */
    public static function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        // Eliminar caracteres nulos
        $html = str_replace(chr(0), '', $html);

        // Prevenir ataques con etiquetas script/object encubiertas (se preserva iframe para videos de YouTube/Vimeo seguros)
        $html = preg_replace('/<\s*(script|object|embed|applet|form|svg|math|link|meta|base)[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $html);
        $html = preg_replace('/<\s*(script|object|embed|applet|form|svg|math|link|meta|base)[^>]*\/?>/is', '', $html);

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();

        // Envolver en contenedor UTF-8 para preservar tildes y caracteres especiales
        $wrappedHtml = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><div id="sisig-sanitizer-wrapper">' . $html . '</div></body></html>';
        
        $dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $wrapper = $dom->getElementById('sisig-sanitizer-wrapper');
        if (!$wrapper) {
            return '';
        }

        // Recorrer y sanitizar todos los nodos hijos recursivamente
        self::sanitizeNode($wrapper);

        // Extraer el HTML resultante del contenedor
        $cleanHtml = '';
        foreach ($wrapper->childNodes as $child) {
            $cleanHtml .= $dom->saveHTML($child);
        }

        return trim($cleanHtml);
    }

    /**
     * Sanitiza un nodo del DOM y sus descendientes.
     */
    protected static function sanitizeNode(DOMNode $node): void
    {
        $childNodes = [];
        foreach ($node->childNodes as $child) {
            $childNodes[] = $child;
        }

        foreach ($childNodes as $child) {
            if ($child instanceof DOMElement) {
                $tagName = strtolower($child->tagName);

                // Si la etiqueta no está permitida, remover el nodo
                if (!in_array($tagName, self::$allowedTags, true)) {
                    $node->removeChild($child);
                    continue;
                }

                // Para bloques de estilo CSS, sanitizar el contenido interno
                if ($tagName === 'style') {
                    $css = $child->textContent;
                    $child->textContent = self::sanitizeCssContent($css);
                    continue;
                }

                // Para iframes, restringir estrictamente a proveedores de video autorizados (YouTube / Vimeo)
                if ($tagName === 'iframe') {
                    $src = $child->getAttribute('src');
                    $host = parse_url($src, PHP_URL_HOST);
                    $allowedVideoHosts = [
                        'youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com',
                        'youtu.be', 'vimeo.com', 'player.vimeo.com'
                    ];
                    $isValidHost = false;
                    if ($host) {
                        foreach ($allowedVideoHosts as $allowedHost) {
                            if ($host === $allowedHost || str_ends_with($host, '.' . $allowedHost)) {
                                $isValidHost = true;
                                break;
                            }
                        }
                    }
                    if (!$isValidHost) {
                        $node->removeChild($child);
                        continue;
                    }
                }

                // Sanitizar atributos
                self::sanitizeAttributes($child, $tagName);

                // Sanitizar recursivamente
                if ($child->hasChildNodes()) {
                    self::sanitizeNode($child);
                }
            }
        }
    }

    /**
     * Filtra atributos para permitir solo los aprobados y eliminar manejadores de eventos.
     */
    protected static function sanitizeAttributes(DOMElement $element, string $tagName): void
    {
        $allowedAttrs = self::$allowedAttributes[$tagName] ?? ['class'];
        $attributesToRemove = [];

        foreach ($element->attributes as $attr) {
            $attrName = strtolower($attr->name);
            $attrValue = trim($attr->value);

            // Eliminar cualquier manejador de evento JS (on...)
            if (str_starts_with($attrName, 'on')) {
                $attributesToRemove[] = $attrName;
                continue;
            }

            // Si el atributo no está en la lista blanca para esta etiqueta
            if (!in_array($attrName, $allowedAttrs, true)) {
                $attributesToRemove[] = $attrName;
                continue;
            }

            // Validar URLs en href y src
            if ($attrName === 'href' || $attrName === 'src') {
                if (!self::isValidUri($attrValue)) {
                    $attributesToRemove[] = $attrName;
                    continue;
                }

                // Para iframes, restringir estrictamente a proveedores de video educativos confiables (YouTube / Vimeo)
                if ($tagName === 'iframe') {
                    $host = parse_url($attrValue, PHP_URL_HOST);
                    $allowedVideoHosts = [
                        'youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com',
                        'youtu.be', 'vimeo.com', 'player.vimeo.com'
                    ];
                    $isValidHost = false;
                    if ($host) {
                        foreach ($allowedVideoHosts as $allowedHost) {
                            if ($host === $allowedHost || str_ends_with($host, '.' . $allowedHost)) {
                                $isValidHost = true;
                                break;
                            }
                        }
                    }
                    if (!$isValidHost) {
                        $attributesToRemove[] = $attrName;
                        continue;
                    }
                }

                // Para enlaces externos, forzar rel noopener
                if ($attrName === 'href' && $element->getAttribute('target') === '_blank') {
                    $element->setAttribute('rel', 'noopener noreferrer');
                }
            }

            // Validar estilo en línea (prohibir expresiones, URLs a scripts o behaviors)
            if ($attrName === 'style') {
                if (!self::isValidStyle($attrValue)) {
                    $attributesToRemove[] = $attrName;
                    continue;
                }
            }
        }

        foreach ($attributesToRemove as $attrName) {
            $element->removeAttribute($attrName);
        }
    }

    /**
     * Valida si un URI es seguro (no javascript, vbscript o data exec).
     */
    protected static function isValidUri(string $uri): bool
    {
        $decoded = rawurldecode($uri);
        $clean = preg_replace('/[\x00-\x20]+/', '', $decoded);

        // Prohibir esquemas de script o código
        if (preg_match('/^(javascript|vbscript|data):/i', $clean)) {
            // Permitir data:image solo si es estrictamente imagen válida
            if (preg_match('/^data:image\/(jpeg|png|webp);base64,/i', $clean)) {
                return true;
            }
            return false;
        }

        // Permitir rutas relativas
        if (str_starts_with($uri, '/') || str_starts_with($uri, '#') || !str_contains($uri, ':')) {
            return true;
        }

        // Validar esquema
        $scheme = parse_url($uri, PHP_URL_SCHEME);
        if (!$scheme) {
            return true;
        }

        return in_array(strtolower($scheme), self::$allowedProtocols, true);
    }

    /**
     * Sanitiza el contenido de un bloque <style> eliminando código ejecutable o fuentes remotas no confiables.
     */
    public static function sanitizeCssContent(string $css): string
    {
        // Eliminar comentarios CSS
        $css = preg_replace('/\/\*.*?\*\//s', '', $css);
        // Eliminar @import
        $css = preg_replace('/@import[^;]*;/i', '', $css);
        // Eliminar esquemas peligrosos
        $css = preg_replace('/javascript\s*:/i', '', $css);
        $css = preg_replace('/expression\s*\(.*?\)/is', '', $css);
        $css = preg_replace('/behavior\s*:[^;}]*/i', '', $css);
        $css = preg_replace('/-moz-binding\s*:[^;}]*/i', '', $css);

        return $css;
    }

    /**
     * Valida que el estilo CSS en línea no contenga vectores de ataque.
     */
    protected static function isValidStyle(string $style): bool
    {
        $lowered = strtolower($style);
        if (str_contains($lowered, 'expression')
            || str_contains($lowered, 'javascript:')
            || str_contains($lowered, 'behavior')
            || str_contains($lowered, '-moz-binding')
            || str_contains($lowered, 'url(')
        ) {
            return false;
        }

        return true;
    }
}

