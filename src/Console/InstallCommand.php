<?php

declare(strict_types=1);

namespace AiDevQuickstart\AiDevQuickstart\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use JsonException;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\select;

class InstallCommand extends Command
{
    protected $signature = 'ai-dev-quickstart:install
        {--force : Overwrite existing customizable resource files}
        {--no-composer : Install resources and scripts without running Composer}';

    protected $description = 'Install the customizable AI development quickstart into the application';

    /** @var list<string> */
    private const array DEV_DEPENDENCIES = [
        'driftingly/rector-laravel',
        'larastan/larastan',
        'pestphp/pest',
        'pestphp/pest-plugin-laravel',
        'pestphp/pest-plugin-type-coverage',
        'rector/rector',
    ];

    /** @var array<string, string|list<string>> */
    private const array COMPOSER_SCRIPTS = [
        'lint' => [
            'rector',
            'pint --parallel',
        ],
        'lint:check' => [
            'pint --parallel --test',
            'rector --dry-run',
        ],
        'test:type-coverage' => '@php -d memory_limit=1G vendor/bin/pest --type-coverage --min=100',
        'test:unit' => '@php -d memory_limit=1G -d xdebug.mode=coverage vendor/bin/pest --parallel --coverage --exactly=100.0',
        'test:lint' => [
            '@lint:check',
        ],
        'test:types' => 'phpstan analyse app routes database --memory-limit=1G --no-progress',
        'test' => [
            '@php artisan config:clear --ansi',
            '@test:type-coverage',
            '@test:unit',
            '@test:lint',
            '@test:types',
        ],
    ];

    /** @var array<string, string> */
    private const array RESOURCES = [
        'AGENTS.md' => 'AGENTS.md',
        'CLAUDE.md' => 'CLAUDE.md',
        'skills/laravel-actions/SKILL.md' => '.agents/skills/laravel-actions/SKILL.md',
        'skills/laravel-testing/SKILL.md' => '.agents/skills/laravel-testing/SKILL.md',
        'skills/requirements-to-gh-issue/SKILL.md' => '.agents/skills/requirements-to-gh-issue/SKILL.md',
        'skills/pr-description/SKILL.md' => '.agents/skills/pr-description/SKILL.md',
        'docker-compose.yml' => 'docker-compose.yml',
        'docker/laravel/Dockerfile' => 'docker/laravel/Dockerfile',
        'phpstan.neon' => 'phpstan.neon',
        'pint.json' => 'pint.json',
        'rector.php' => 'rector.php',
    ];

    /** @var list<string> */
    private const array INSTRUCTION_FILES = [
        'AGENTS.md',
        'CLAUDE.md',
    ];

    public function handle(Filesystem $files): int
    {
        try {
            $this->installResources($files);
            $this->configureComposer($files);
        } catch (JsonException $exception) {
            $this->components->error('Unable to update composer.json: '.$exception->getMessage());

            return self::FAILURE;
        }

        if (! $this->option('no-composer') && ! $this->installDependencies()) {
            return self::FAILURE;
        }

        $this->components->info('AI development quickstart installed. All generated files can now be customized.');

        return self::SUCCESS;
    }

    private function installResources(Filesystem $files): void
    {
        foreach (self::RESOURCES as $source => $destination) {
            $target = base_path($destination);

            if ($files->exists($target) && in_array($destination, self::INSTRUCTION_FILES, true) && ! $this->option('force')) {
                $this->installInstructionFile($files, $source, $destination);

                continue;
            }

            if ($files->exists($target) && ! $this->option('force')) {
                $this->components->warn("Kept existing {$destination}");

                continue;
            }

            $files->ensureDirectoryExists(dirname($target));
            $files->copy($this->resourcePath($source), $target);
            $this->components->task("Installed {$destination}");
        }
    }

    private function installInstructionFile(Filesystem $files, string $source, string $destination): void
    {
        $target = base_path($destination);
        $action = select(
            label: "{$destination} already exists. How should the AI development quickstart content be installed?",
            options: [
                'attach' => 'Attach the quickstart content',
                'replace' => 'Replace the existing file',
            ],
            default: 'attach',
        );

        if ($action === 'attach') {
            $content = rtrim($files->get($target))."\n\n".ltrim($files->get($this->resourcePath($source)));

            $files->put($target, $content, true);
            $this->components->task("Attached content to {$destination}");

            return;
        }

        $files->copy($this->resourcePath($source), $target);
        $this->components->task("Replaced {$destination}");
    }

    /** @throws JsonException */
    private function configureComposer(Filesystem $files): void
    {
        $path = base_path('composer.json');

        if (! $files->exists($path)) {
            throw new JsonException('No composer.json file was found at the application root.');
        }

        $composer = json_decode($files->get($path), true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($composer)) {
            throw new JsonException('The root composer.json must contain a JSON object.');
        }

        $scripts = is_array($composer['scripts'] ?? null) ? $composer['scripts'] : [];

        foreach (self::COMPOSER_SCRIPTS as $name => $commands) {
            $scripts[$name] ??= $commands;
        }

        $composer['scripts'] = $scripts;

        $encoded = json_encode(
            $composer,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
        );

        $files->put($path, $encoded.PHP_EOL, true);
        $this->components->task('Configured composer.json');
    }

    private function installDependencies(): bool
    {
        $process = new Process([
            'composer',
            'require',
            '--dev',
            ...self::DEV_DEPENDENCIES,
            '--with-all-dependencies',
            '--no-interaction',
        ], base_path());
        $process->setTimeout(null);

        $this->components->info('Installing development dependencies...');
        $process->run(function (string $type, string $output): void {
            $this->output->write($output);
        });

        if ($process->isSuccessful()) {
            return true;
        }

        $this->components->error('Composer could not install the development dependencies. The generated files and composer.json changes were kept.');

        return false;
    }

    private function resourcePath(string $path): string
    {
        return dirname(__DIR__, 2).'/resources/stubs/'.$path;
    }
}
