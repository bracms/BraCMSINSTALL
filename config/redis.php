<?php

return [
    /**
     *   * Example DSN:
     *   - redis://localhost
     *   - redis://example.com:1234
     *   - redis://secret@example.com/13
     *   - redis:///var/run/redis.sock
     *   - redis://secret@/var/run/redis.sock/13
     */
    'cache_dsn' => env('REDIS_URL' , 'redis://localhost/1'),
    'cache_prefix' => env('REDIS_PREFIX' , ''),
];
