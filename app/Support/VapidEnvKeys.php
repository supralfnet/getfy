<?php

namespace App\Support;

/**
 * Normaliza chaves VAPID vindas do .env para o formato esperado por minishlink/web-push (Base64 URL-safe).
 */
final class VapidEnvKeys
{
    public static function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $v = trim($value);
        // BOM UTF-8 (comum ao colar chaves de editores / exports)
        if (str_starts_with($v, "\xEF\xBB\xBF")) {
            $v = substr($v, 3);
        }
        $v = trim($v, " \t\n\r\0\x0B\"'");
        $v = str_replace(["\r", "\n", ' ', "\t"], '', $v);
        if ($v === '') {
            return null;
        }
        if (str_contains($v, '+') || str_contains($v, '/')) {
            $v = strtr($v, ['+' => '-', '/' => '_']);
        }
        // Remove qualquer caractere fora do alfabeto base64url (NBSP, zero-width, etc.)
        $v = preg_replace('/[^A-Za-z0-9\-_=]/', '', $v) ?? '';
        if ($v === '') {
            return null;
        }

        return $v;
    }
}
