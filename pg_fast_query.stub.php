<?php

if (!function_exists('pg_fast_query')) {
    /**
     * Maps PostgreSQL rows directly to PHP objects for high-performance hydration.
     *
     * **Requirements:**
     * - The core `pgsql` PHP extension must be loaded (relies on `\PgSql\Result`).
     * - The custom `pg_fast_query` C extension must be installed and enabled.
     *
     * **Optimal Setup (Fast Path):**
     * For maximum performance (~3.39x faster than manual PHP), design your DTOs with these rules:
     * 1. **No constructors:** Define properties directly without a `__construct()` method.
     * 2. **No inheritance:** Do not extend other classes.
     * 3. **Visibility:** Properties can be `private` or `public` (setters are not required).
     *
     * **Mapping Nested Objects:**
     * - **Standard nesting:** Use dot notation in your SQL aliases (e.g., `SELECT price AS "nested.price"`).
     * - **Single-value objects:** For objects with only one property, use the property name directly
     * as the alias (e.g., `SELECT email AS "nested"`). The extension auto-assigns it.
     *
     * **Example Usage:**
     * ```php
     *
     * readonly class PriceInfo {
     *     public int $amount;
     *     public ?\DateTimeImmutable $updatedAt;
     * }
     *
     * readonly class ProductDto {
     *     public int $id;
     *     public string $name;
     *     public PriceInfo $price;
     * }
     *
     * // Use dot-notation in SQL to map the nested PriceInfo object
     * $sql = 'SELECT id, name, price_amount AS "price.amount", updated_at AS "price.updatedAt" FROM products';
     * $result = pg_query($conn, $sql);
     * // $products will be typed as ProductDto[] in your IDE
     * $products = pg_fast_query($result, ProductDto::class);
     * ```
     *
     * @template T of object
     * @param \PgSql\Result $result The PostgreSQL result instance.
     * @param class-string<T> $class The fully qualified class name to map the results to.
     * @return T[] An array of populated objects.
     */
    function pg_fast_query(\PgSql\Result $result, string $class): array {
        throw new \RuntimeException('The pg_fast_query C extension is not loaded.');
    }
}