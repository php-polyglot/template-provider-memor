<?php

declare(strict_types=1);

namespace TestUnits\Polyglot\MemoryTemplateProvider;

use PHPUnit\Framework\TestCase;
use Polyglot\Contract\TemplateProvider\Exception\TemplateNotFound;
use Polyglot\MemoryTemplateProvider\MemoryTemplateProvider;

final class MemoryTemplateProviderTest extends TestCase
{
    /**
     * @param string $domain
     * @param string $key
     * @param string $locale
     * @param string $expected
     * @return void
     * @throws TemplateNotFound
     * @dataProvider provideGet
     */
    public function testGet(string $domain, string $key, string $locale, string $expected): void
    {
        $provider = $this->getProvider();
        $this->assertSame($expected, $provider->get($domain, $key, $locale));
    }

    /**
     * @param string $domain
     * @param string $key
     * @param string $locale
     * @return void
     * @throws TemplateNotFound
     * @dataProvider provideGetTemplateNotFound
     */
    public function testGetTemplateNotFound(string $domain, string $key, string $locale): void
    {
        $provider = $this->getProvider();
        $this->expectException(TemplateNotFound::class);
        $provider->get($domain, $key, $locale);
    }

    public function testIterator(): void
    {
        $provider = $this->getProvider();

        $actualList = '';
        foreach ($provider->getDomains() as $domain) {
            foreach ($provider->getLocales($domain) as $locale) {
                $actualList .= 'list of ' . $domain . ' (' . $locale . '):' . PHP_EOL;
                foreach ($provider->getTemplates($domain, $locale) as $key => $template) {
                    $actualList .= '  - ' . $key . ' => ' . $template . PHP_EOL;
                }
            }
        }

        $expectedList = <<<TEXT
        list of domain_1 (en_US):
          - no_deep => no deep template domain 1 / en_US
          - foo.bar => foo bar domain 1 / en_US
          - foo.baz => foo baz domain 1 / en_US
          - h.e.l.l.o => deep template domain 1 / en_US
        list of domain_1 (ru_RU):
          - no_deep => no deep template domain 1 / ru_RU
          - foo.bar => foo bar domain 1 / ru_RU
          - foo.baz => foo baz domain 1 / ru_RU
          - h.e.l.l.o => deep template domain 1 / ru_RU
        list of domain_2 (en_US):
          - no_deep => no deep template domain 2 / en_US
          - foo.bar => foo bar domain 2 / en_US
          - foo.baz => foo baz domain 2 / en_US
          - h.e.l.l.o => deep template domain 2 / en_US
        list of domain_2 (ru_RU):
          - no_deep => no deep template domain 2 / ru_RU
          - foo.bar => foo bar domain 2 / ru_RU
          - foo.baz => foo baz domain 2 / ru_RU
          - h.e.l.l.o => deep template domain 2 / ru_RU

        TEXT;
        $this->assertSame($expectedList, $actualList);
    }

    public function provideGet(): iterable
    {
        return [
            ['domain_1', 'no_deep', 'en_US', 'no deep template domain 1 / en_US'],
            ['domain_1', 'foo.bar', 'en_US', 'foo bar domain 1 / en_US'],
            ['domain_1', 'foo.baz', 'en_US', 'foo baz domain 1 / en_US'],
            ['domain_1', 'h.e.l.l.o', 'en_US', 'deep template domain 1 / en_US'],
            ['domain_1', 'no_deep', 'ru_RU', 'no deep template domain 1 / ru_RU'],
            ['domain_1', 'foo.bar', 'ru_RU', 'foo bar domain 1 / ru_RU'],
            ['domain_1', 'foo.baz', 'ru_RU', 'foo baz domain 1 / ru_RU'],
            ['domain_1', 'h.e.l.l.o', 'ru_RU', 'deep template domain 1 / ru_RU'],
            ['domain_2', 'no_deep', 'en_US', 'no deep template domain 2 / en_US'],
            ['domain_2', 'foo.bar', 'en_US', 'foo bar domain 2 / en_US'],
            ['domain_2', 'foo.baz', 'en_US', 'foo baz domain 2 / en_US'],
            ['domain_2', 'h.e.l.l.o', 'en_US', 'deep template domain 2 / en_US'],
            ['domain_2', 'no_deep', 'ru_RU', 'no deep template domain 2 / ru_RU'],
            ['domain_2', 'foo.bar', 'ru_RU', 'foo bar domain 2 / ru_RU'],
            ['domain_2', 'foo.baz', 'ru_RU', 'foo baz domain 2 / ru_RU'],
            ['domain_2', 'h.e.l.l.o', 'ru_RU', 'deep template domain 2 / ru_RU'],
        ];
    }

    public function provideGetTemplateNotFound(): iterable
    {
        return [
            ['unknown_domain', 'no_deep', 'en_US'],
            ['domain_1', 'unknown_key', 'en_US'],
            ['domain_1', 'foo.baz', 'unknown_locale'],
            ['unknown_domain', 'unknown_key', 'unknown_locale'],
        ];
    }

    private function getProvider(): MemoryTemplateProvider
    {
        $provider = new MemoryTemplateProvider();
        $provider
            ->set('domain_1', 'no_deep', 'en_US', 'no deep template domain 1 / en_US')
            ->set('domain_1', 'foo.bar', 'en_US', 'foo bar domain 1 / en_US')
            ->set('domain_1', 'foo.baz', 'en_US', 'foo baz domain 1 / en_US')
            ->set('domain_1', 'h.e.l.l.o', 'en_US', 'deep template domain 1 / en_US')
            ->set('domain_2', 'no_deep', 'en_US', 'no deep template domain 2 / en_US')
            ->set('domain_2', 'foo.bar', 'en_US', 'foo bar domain 2 / en_US')
            ->set('domain_2', 'foo.baz', 'en_US', 'foo baz domain 2 / en_US')
            ->set('domain_2', 'h.e.l.l.o', 'en_US', 'deep template domain 2 / en_US')
            ->set('domain_1', 'no_deep', 'ru_RU', 'no deep template domain 1 / ru_RU')
            ->set('domain_1', 'foo.bar', 'ru_RU', 'foo bar domain 1 / ru_RU')
            ->set('domain_1', 'foo.baz', 'ru_RU', 'foo baz domain 1 / ru_RU')
            ->set('domain_1', 'h.e.l.l.o', 'ru_RU', 'deep template domain 1 / ru_RU')
            ->set('domain_2', 'no_deep', 'ru_RU', 'no deep template domain 2 / ru_RU')
            ->set('domain_2', 'foo.bar', 'ru_RU', 'foo bar domain 2 / ru_RU')
            ->set('domain_2', 'foo.baz', 'ru_RU', 'foo baz domain 2 / ru_RU')
            ->set('domain_2', 'h.e.l.l.o', 'ru_RU', 'deep template domain 2 / ru_RU')
        ;
        return $provider;
    }
}
