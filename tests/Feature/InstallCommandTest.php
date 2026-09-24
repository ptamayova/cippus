<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;

beforeEach(function () {
    $this->applicationPath = sys_get_temp_dir().'/ai-dev-quickstart-'.bin2hex(random_bytes(8));

    mkdir($this->applicationPath, 0755, true);

    file_put_contents($this->applicationPath.'/composer.json', json_encode([
        'name' => 'example/application',
        'require-dev' => [
            'fakerphp/faker' => '^1.24',
        ],
        'scripts' => [
            'existing' => 'php artisan about',
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

    $this->app->setBasePath($this->applicationPath);
});

afterEach(function () {
    (new Filesystem)->deleteDirectory($this->applicationPath);
});

it('installs editable development resources and configures composer', function () {
    $this->artisan('ai-dev-quickstart:install', ['--no-composer' => true])
        ->expectsOutputToContain('AI development quickstart installed')
        ->assertSuccessful();

    expect($this->applicationPath.'/AGENTS.md')->toBeFile()
        ->and($this->applicationPath.'/CLAUDE.md')->toBeFile()
        ->and($this->applicationPath.'/.agents/skills/laravel-actions/SKILL.md')->toBeFile()
        ->and($this->applicationPath.'/.agents/skills/laravel-testing/SKILL.md')->toBeFile()
        ->and($this->applicationPath.'/.agents/skills/requirements-to-gh-issue/SKILL.md')->toBeFile()
        ->and($this->applicationPath.'/.agents/skills/pr-description/SKILL.md')->toBeFile()
        ->and($this->applicationPath.'/docker-compose.yml')->toBeFile()
        ->and($this->applicationPath.'/docker/laravel/Dockerfile')->toBeFile()
        ->and($this->applicationPath.'/phpstan.neon')->toBeFile()
        ->and($this->applicationPath.'/pint.json')->toBeFile()
        ->and($this->applicationPath.'/rector.php')->toBeFile();

    expect(file_get_contents($this->applicationPath.'/AGENTS.md'))
        ->toBe(file_get_contents(__DIR__.'/../../resources/stubs/AGENTS.md'))
        ->and(file_get_contents($this->applicationPath.'/CLAUDE.md'))
        ->toBe(file_get_contents(__DIR__.'/../../resources/stubs/CLAUDE.md'));

    $composer = json_decode(
        (string) file_get_contents($this->applicationPath.'/composer.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    expect($composer['require-dev'])
        ->toBe([
            'fakerphp/faker' => '^1.24',
        ])
        ->and($composer['scripts']['existing'])->toBe('php artisan about')
        ->and($composer['scripts'])->toHaveKeys([
            'lint',
            'lint:check',
            'test:type-coverage',
            'test:unit',
            'test:lint',
            'test:types',
            'test',
        ]);
});

it('uses Composer require with unversioned development dependencies', function () {
    $binDirectory = $this->applicationPath.'/bin';
    mkdir($binDirectory, 0755, true);

    $composerScript = <<<'PHP'
#!/usr/bin/env php
<?php

file_put_contents(getcwd().'/composer-arguments.json', json_encode(array_slice($argv, 1)));
PHP;

    if (DIRECTORY_SEPARATOR === '\\') {
        file_put_contents($binDirectory.'/composer.php', $composerScript);
        file_put_contents($binDirectory.'/composer.bat', '@php "%~dp0composer.php" %*'.PHP_EOL);
    } else {
        file_put_contents($binDirectory.'/composer', $composerScript);
        chmod($binDirectory.'/composer', 0755);
    }

    $originalPath = getenv('PATH');
    putenv("PATH={$binDirectory}".PATH_SEPARATOR.$originalPath);

    try {
        $this->artisan('ai-dev-quickstart:install')->assertSuccessful();
    } finally {
        putenv("PATH={$originalPath}");
    }

    expect(json_decode(
        (string) file_get_contents($this->applicationPath.'/composer-arguments.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    ))->toBe([
        'require',
        '--dev',
        'driftingly/rector-laravel',
        'larastan/larastan',
        'pestphp/pest',
        'pestphp/pest-plugin-laravel',
        'pestphp/pest-plugin-type-coverage',
        'rector/rector',
        '--with-all-dependencies',
        '--no-interaction',
    ]);
});

it('attaches or replaces customized instruction files without changing composer values', function () {
    file_put_contents($this->applicationPath.'/AGENTS.md', 'custom rules');
    file_put_contents($this->applicationPath.'/CLAUDE.md', 'custom Claude rules');

    $composer = json_decode(
        (string) file_get_contents($this->applicationPath.'/composer.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    );
    $composer['require-dev']['rector/rector'] = '^2.5';
    $composer['scripts']['lint'] = 'custom lint';
    file_put_contents(
        $this->applicationPath.'/composer.json',
        json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL,
    );

    $this->artisan('ai-dev-quickstart:install', ['--no-composer' => true])
        ->expectsChoice(
            'AGENTS.md already exists. How should the AI development quickstart content be installed?',
            'attach',
            [
                'attach' => 'Attach the quickstart content',
                'replace' => 'Replace the existing file',
            ],
        )
        ->expectsChoice(
            'CLAUDE.md already exists. How should the AI development quickstart content be installed?',
            'attach',
            [
                'attach' => 'Attach the quickstart content',
                'replace' => 'Replace the existing file',
            ],
        )
        ->assertSuccessful();

    $composer = json_decode(
        (string) file_get_contents($this->applicationPath.'/composer.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    expect(file_get_contents($this->applicationPath.'/AGENTS.md'))
        ->toBe("custom rules\n\n".file_get_contents(__DIR__.'/../../resources/stubs/AGENTS.md'))
        ->and(file_get_contents($this->applicationPath.'/CLAUDE.md'))
        ->toBe("custom Claude rules\n\n".file_get_contents(__DIR__.'/../../resources/stubs/CLAUDE.md'))
        ->and($composer['require-dev']['rector/rector'])->toBe('^2.5')
        ->and($composer['scripts']['lint'])->toBe('custom lint');

    $this->artisan('ai-dev-quickstart:install', [
        '--force' => true,
        '--no-composer' => true,
    ])->assertSuccessful();

    expect(file_get_contents($this->applicationPath.'/AGENTS.md'))
        ->toBe(file_get_contents(__DIR__.'/../../resources/stubs/AGENTS.md'))
        ->and(file_get_contents($this->applicationPath.'/CLAUDE.md'))
        ->toBe(file_get_contents(__DIR__.'/../../resources/stubs/CLAUDE.md'));
});

it('replaces existing instruction files when selected', function () {
    file_put_contents($this->applicationPath.'/AGENTS.md', 'custom rules');
    file_put_contents($this->applicationPath.'/CLAUDE.md', 'custom Claude rules');

    $this->artisan('ai-dev-quickstart:install', ['--no-composer' => true])
        ->expectsChoice(
            'AGENTS.md already exists. How should the AI development quickstart content be installed?',
            'replace',
            [
                'attach' => 'Attach the quickstart content',
                'replace' => 'Replace the existing file',
            ],
        )
        ->expectsChoice(
            'CLAUDE.md already exists. How should the AI development quickstart content be installed?',
            'replace',
            [
                'attach' => 'Attach the quickstart content',
                'replace' => 'Replace the existing file',
            ],
        )
        ->assertSuccessful();

    expect(file_get_contents($this->applicationPath.'/AGENTS.md'))
        ->toBe(file_get_contents(__DIR__.'/../../resources/stubs/AGENTS.md'))
        ->and(file_get_contents($this->applicationPath.'/CLAUDE.md'))
        ->toBe(file_get_contents(__DIR__.'/../../resources/stubs/CLAUDE.md'));
});

it('fails loudly when the application composer manifest is invalid', function () {
    file_put_contents($this->applicationPath.'/composer.json', '{invalid');

    $this->artisan('ai-dev-quickstart:install', ['--no-composer' => true])
        ->expectsOutputToContain('Unable to update composer.json')
        ->assertFailed();
});
