import { execSync } from 'node:child_process';
import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import babel from '@rolldown/plugin-babel';
import tailwindcss from '@tailwindcss/vite';
import react, { reactCompilerPreset } from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig, lazyPlugins } from 'vite-plus';

const resolveWayfinderCommand = (): string => {
    if (process.env.WAYFINDER_COMMAND) {
        return process.env.WAYFINDER_COMMAND;
    }

    // On Windows with Laravel Herd, check if the php85 alias exists in PATH
    if (process.platform === 'win32') {
        try {
            execSync('where php85', { stdio: 'ignore' });
            return 'php85 artisan wayfinder:generate';
        } catch {
            // php85 not in PATH, fallback to standard php
        }
    }

    // Default for Linux, Docker, Laravel Cloud/Forge, and production environments
    const phpBinary = process.env.PHP_BINARY || 'php';
    return `${phpBinary} artisan wayfinder:generate`;
};

export default defineConfig(({ command }) => ({
    plugins: lazyPlugins(() => [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600, 700],
                    optimizedFallbacks: false,
                }),
            ],
        }),
        inertia(),
        react(),
        babel({
            presets: [reactCompilerPreset()],
        }),
        tailwindcss(),
        // Wayfinder auto-generates TypeScript route/action helpers.
        // On shared hosting (such as Hostinger / cPanel / CloudLinux), spawning child processes
        // during production build fails with EAGAIN (process limit).
        // Wayfinder is only needed during development (command === 'serve').
        ...((command === 'serve' && !process.env.SKIP_WAYFINDER) ||
        process.env.ENABLE_WAYFINDER_BUILD
            ? [
                  wayfinder({
                      formVariants: true,
                      command: resolveWayfinderCommand(),
                  }),
              ]
            : []),
    ]),
    server: {
        watch: {
            ignored: [
                '**/.agents/**',
                '**/.claude/**',
                '**/.cursor/**',
                '**/.junie/**',
                '**/vendor/**',
            ],
        },
    },
    lint: {
        ignorePatterns: [
            'vendor/**',
            'node_modules/**',
            'public/**',
            'bootstrap/ssr/**',
            'tailwind.config.js',
            'resources/js/actions/**',
            'resources/js/components/ui/*',
            'resources/js/routes/**',
            'resources/js/wayfinder/**',
        ],
        options: {
            denyWarnings: true,
            typeAware: true,
        },
    },
    fmt: {
        printWidth: 80,
        tabWidth: 4,
        singleQuote: true,
        semi: true,
        singleAttributePerLine: false,
        htmlWhitespaceSensitivity: 'css',
        ignorePatterns: [
            '.agents/**',
            '.claude/**',
            '.cursor/**',
            '.github/**',
            '.junie/**',
            'docs/**',
            '*.json',
            'AGENTS.md',
            'CLAUDE.md',
            'composer.json',
            'resources/js/components/ui/*',
            'resources/views/mail/*',
        ],
        sortTailwindcss: {
            functions: ['clsx', 'cn', 'cva'],
            stylesheet: 'resources/css/app.css',
        },
    },
}));
