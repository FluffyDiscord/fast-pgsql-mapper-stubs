<?php

if (!function_exists('pg_fast_query')) {
    /**
     * Maps PostgreSQL result rows straight into typed PHP objects.
     *
     * Needs the `pgsql` and `pgsql_mapper` extensions.
     *
     * **Columns match properties by name**, any visibility. Fastest DTOs have no constructor,
     * or a constructor with only promoted params that all have a column (it is skipped).
     *
     * **Types:** `int2/4/8` → `int`, `float4/8` → `float`, `bool` → `bool`,
     * `timestamp(tz)` → `DateTimeImmutable`, `NULL` → `null`, anything else → `string`.
     *
     * **By property type** (a property that also accepts `string` keeps the raw value):
     * - `array` ← `json`/`jsonb` (same result as `json_decode($value, true)`)
     * - `array` ← PG arrays (`int4[]` → `list<int>`, `text[]` → `list<string>`, nested → nested lists)
     * - `DateTimeImmutable` ← `date` (midnight UTC)
     * - `DateTimeImmutable` subclass (e.g. `CarbonImmutable`) ← `timestamp(tz)` / `date`
     * - backed enum ← matching text/integer column (`Enum::from()` semantics)
     *
     * **Nested objects:** alias columns with a dot path (`SELECT price AS "nested.price"`).
     * A column named after a class-typed property fills that class's first property.
     *
     * ```php
     * readonly class PriceInfo {
     *     public int $amount;
     *     public ?\DateTimeImmutable $updatedAt;
     * }
     *
     * readonly class ProductDto {
     *     public int $id;
     *     public string $name;
     *     public array $tags;        // text[] or jsonb
     *     public PriceInfo $price;
     * }
     *
     * $result = pg_query($conn, 'SELECT id, name, tags, price_amount AS "price.amount", updated_at AS "price.updatedAt" FROM products');
     * $products = pg_fast_query($result, ProductDto::class); // list<ProductDto>
     * ```
     *
     * @template T of object
     *
     * @param \PgSql\Result   $result Result of `pg_query()` / `pg_query_params()` / `pg_execute()`.
     * @param class-string<T> $class  Class to map each row to.
     *
     * @return list<T>
     *
     * @throws \TypeError  Unknown class, or a value that doesn't fit its property type.
     * @throws \ValueError Unknown backed-enum value.
     * @throws \Exception  Result has no rows to map (e.g. an `INSERT` without `RETURNING`).
     */
    function pg_fast_query(\PgSql\Result $result, string $class): array {
        throw new \RuntimeException('The pgsql_mapper extension is not loaded.');
    }
}
