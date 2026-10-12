<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Trava de segurança: o RefreshDatabase apaga o banco inteiro, então
     * só deixa rodar em banco cujo nome termina com "_test".
     */
    protected function setUpTraits()
    {
        $banco = (string) config('database.connections.' . config('database.default') . '.database');

        if (! str_ends_with($banco, '_test')) {
            throw new \RuntimeException(
                "Testes abortados: o banco '{$banco}' não é de teste (o nome precisa terminar com _test). "
                . 'Confira o phpunit.xml e rode `php artisan config:clear`.'
            );
        }

        return parent::setUpTraits();
    }
}
