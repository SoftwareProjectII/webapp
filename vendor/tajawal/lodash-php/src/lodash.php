<?php
/*                                                 *\
 *                   lodash-php                    *
 *                                                 *
 ___________________________________________________
 ***************************************************

 ** Arrays                                       [6]
 ** Chaining                                     [3]
 ** Collections                                  [9]
 ** Functions                                    [0]
 ** Objects                                      [7]
 ** Utilities                                    [0]
\***************************************************/

if (version_compare(PHP_VERSION, '5.4.0', '=<')) {
    throw new \Exception('Your PHP installation is too old. lodash-php requires at least PHP 5.4.0', 1);
}

/**
 * @method static append(array $array = [], $value = null)
 * @method static compact(array $array = [])
 * @method static flatten(array $array, $shallow = false, $strict = true, $startIndex = 0)
 * @method static patch($arr, $patches, $parent = '')
 * @method static prepend(array $array = [], $value = null)
 * @method static range($start = null, $stop = null, $step = 1)
 * @method static repeat($object = '', $times = null)
 * @method static filter(array $array = [], \Closure $closure)
 * @method static first($array, $take = null)
 * @method static get($collection = [], $key = '', $default = null)
 * @method static last($array, $take = null)
 * @method static map(array $array = [], \Closure $closure)
 * @method static max(array $array = [])
 * @method static min(array $array = [])
 * @method static pluck($collection = [], $property = '')
 * @method static where(array $array = [], array $key = [])
 * @method static slug($str, $options = [])
 * @method static truncate($text, $limit = 40)
 * @method static urlify($string)
 * @method static isArray($value = null)
 * @method static isFunction($value = null)
 * @method static isNull($value = null)
 * @method static isNumber($value = null)
 * @method static isObject($value = null)
 * @method static isString($value = null)
 * @method static isEmail($value = null)
 * @method static now()
 * @method static stringContains($needle, $haystack, $offset = 0)
 *
 */
class __
{
    static $modules
        = [
            'arrays',
            'chaining',
            'collections',
            'functions',
            'objects',
            'utilities',
        ];

    static $functions = [];

    public static function __callStatic($name, $arguments)
    {
        return self::__loader($name, $arguments);
    }

    public function __call($name, $arguments)
    {
        return self::__loader($name, $arguments);
    }

    /**
     * auto-loading function
     *
     * @param $name
     * @param $arguments
     *
     * @return null|mixed
     *
     */
    static public function __loader($name, $arguments)
    {
        if (empty(self::$functions)) {
            foreach (self::$modules as $key => $value) {
                foreach (glob(__DIR__ . '/' . $value . '/*.php', GLOB_BRACE) as $function) {
                    $path  = explode('.', str_replace(__DIR__ . '/', '', $function));
                    $alias = str_replace('/', '\\', array_shift($path));

                    if (!function_exists($alias)) {
                        self::$functions[] = $alias;
                        require_once $function;
                    }
                }
            }
        }

        foreach (self::$functions as $key => $value) {
            if (strpos($value, $name)) {
                return call_user_func_array($value, $arguments);
            }
        }

        return null;
    }
}
