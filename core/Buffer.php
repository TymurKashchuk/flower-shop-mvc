<?php

namespace core;

use core\Response;
class Buffer
{
    private static string $cacheKey = '';

    public static function start(string $cacheKey): void
    {
        self::$cacheKey = $cacheKey;

        $cached = self::getFromCache($cacheKey);
        if($cached !==null){
            echo $cached;
            exit;
        }

        ob_start();
    }

    public static function end(): void
    {
        $content = ob_get_contents();
        $statusCode = Response::getStatus();

        if($statusCode === 200 && !empty(self::$cacheKey)){
            self::saveToCache(self::$cacheKey, $content);
        }

        ob_end_clean();
        echo $content;
    }

    public static function clearCache(string $key = ''): void{
        if($key){
            $file = CACHE_PATH . '/' .md5($key) . '.cache';
            if(file_exists($file)){
                unlink($file);
            }
        }else{
            $files = glob(CACHE_PATH . '/*.cache') ?: [];
            array_map('unlink', $files);
        }
    }

    private static function getFromCache(string $key): ?string{
        $file = CACHE_PATH . '/' .md5($key) . '.cache';

        if(!file_exists($file)){
            return null;
        }

        $data = unserialize(file_get_contents($file));

        if(time() > $data['expires']){
            unlink($file);
            return null;
        }

        return $data['content'];
    }

    private static function saveToCache(string $key, string $content): void{
        if(!is_dir(CACHE_PATH)){
            mkdir(CACHE_PATH,0755,true);
        }

        $file = CACHE_PATH . '/' .md5($key) . '.cache';

        file_put_contents($file, serialize([
            'content' => $content,
            'expires' => time() + CACHE_TTL,
        ]));
    }
}