<?php

declare(strict_types=1);

namespace Polyglot\MemoryTemplateProvider;

use Polyglot\Contract\TemplateProvider\Exception\TemplateNotFound;
use Polyglot\Contract\TemplateProvider\TemplateProvider;

final class MemoryTemplateProvider implements TemplateProvider
{
    private array $templates = [];

    public function set(string $domain, string $key, string $locale, string $template): self
    {
        $this->templates[$domain][$locale][$key] = $template;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function need(string $domain, string $key, string $locale): void
    {
    }

    /**
     * @inheritdoc
     */
    public function get(string $domain, string $key, string $locale): string
    {
        $template = $this->templates[$domain][$locale][$key] ?? null;
        if (!is_string($template)) {
            throw new TemplateNotFound($key);
        }

        return $template;
    }

    /**
     * @inheritdoc
     */
    public function flush(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getDomains(): iterable
    {
        return array_keys($this->templates);
    }

    /**
     * @inheritDoc
     */
    public function getLocales(string $domain): iterable
    {
        if (!array_key_exists($domain, $this->templates)) {
            return [];
        }
        return array_keys($this->templates[$domain]);
    }

    /**
     * @inheritDoc
     */
    public function getTemplates(string $domain, string $locale): iterable
    {
        if (!array_key_exists($domain, $this->templates)) {
            return [];
        }
        if (!array_key_exists($locale, $this->templates[$domain])) {
            return [];
        }

        return $this->templates[$domain][$locale];
    }
}
