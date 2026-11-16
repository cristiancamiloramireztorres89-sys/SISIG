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
        'a', 'img', 'span', 'div'
    ];

    /**
     * Lista blanca de atributos permitidos por etiqueta.
     */
    protected static array $allowedAttributes = [
        'a'      => ['href', 'title', 'target', 'rel', 'class'],
        'img'    => ['src', 'alt', 'title', 'width', 'height', 'class', 'style'],
        'table'  => ['class', 'style', 'border', 'cellpadding', 'cellspacing'],
        'th'     => ['class', 'style', 'colspan', 'rowspan', 'scope'],
        'td'     => ['class', 'style', 'colspan', 'rowspan'],
        'span'   => ['class', 'style'],
        'div'    => ['class', 'style'],
        'p'      => ['class', 'style'],
        'h1'     => ['class', 'style'],
        'h2'     => ['class', 'style'],
        'h3'     => ['class', 'style'],
        'h4'     => ['class', 'style'],
        'h5'     => ['class', 'style'],
        'h6'     => ['class', 'style'],
        'ul'     => ['class', 'style'],
        'ol'     => ['class', 'style'],
        'li'     => ['class', 'style'],
        'code'   => ['class'],
        'pre'    => ['class'],
        'blockquote' => ['class', 'style'],
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

        // Prevenir ataques con etiquetas script/iframe encubiertas
        $html = preg_replace('/<\s*(script|iframe|object|embed|applet|form|svg|math|link|meta|base)[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $html);
        $html = preg_replace('/<\s*(script|iframe|object|embed|applet|form|svg|math|link|meta|base)[^>]*\/?>/is', '', $html);

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

