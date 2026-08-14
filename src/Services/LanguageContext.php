<?php namespace Seiger\sMailer\Services;

/** Resolve the active site language without requiring the optional sLang package. */
class LanguageContext
{
    public function current(): string
    {
        return trim((string) evo()->getLocale()) ?: 'base';
    }
}
