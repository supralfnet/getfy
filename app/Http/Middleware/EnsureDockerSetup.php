<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDockerSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isDocker()) {
            return $next($request);
        }

        if ($this->isSetupDone()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return $next($request);
        }

        if ($request->is('docker-setup') || $request->is('docker-setup/*')
            || $request->is('install') || $request->is('install/*')
            || $request->is('up')
            || $request->is('manifest.json') || $request->is('painel-sw.js')
            || $request->is('build/*') || $request->is('storage/*')) {
            return $next($request);
        }

        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        return redirect('/docker-setup', 302);
    }

    private function isDocker(): bool
    {
        $val = env('GETFY_DOCKER', false);
        if (is_string($val)) {
            $val = strtolower(trim($val));
            return in_array($val, ['1', 'true', 'yes', 'on'], true);
        }
        return (bool) $val;
    }

    private function isSetupDone(): bool
    {
        $envPath = base_path('.env');
        if (! is_file($envPath)) {
            return false;
        }
        $content = (string) file_get_contents($envPath);

        if (preg_match('/^\s*DOCKER_SETUP_DONE\s*=\s*["\']?true["\']?\s*(?:#|$)/mi', $content)) {
            return true;
        }

        if (preg_match('/^\s*APP_URL\s*=\s*(.+)\s*$/mi', $content, $m)) {
            $url = trim((string) ($m[1] ?? ''), " \t\n\r\0\x0B\"'");
            $urlLower = strtolower($url);
            if ($urlLower !== '' && $urlLower !== 'http://localhost' && $urlLower !== 'https://localhost'
                && $urlLower !== 'http://127.0.0.1' && $urlLower !== 'https://127.0.0.1'
                && $urlLower !== 'http://localhost:80' && $urlLower !== 'https://localhost:443') {
                return true;
            }
        }

        return false;
    }
}

