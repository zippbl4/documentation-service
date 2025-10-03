<?php

namespace App\Config\Repositories;

use App\Config\Casts\ValueCast;
use App\Config\Entities\Settings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

final class SettingsEloquentStorage implements SettingsInterface
{
    /**
     * Cache key.
     *
     * @var string
     */
    protected string $settingsCacheKey = 'app_settings';

    /**
     * {@inheritdoc}
     */
    public function all($fresh = false)
    {
        return $this
            ->getSettingModel()
            ->withCasts(['val' => ValueCast::class])
            ->get(['val', 'name'])
            ->pluck('val', 'name');

//        if ($fresh) {
//            return $this->getSettingModel()->pluck('val', 'name');
//        }
//
//        return Cache::rememberForever($this->settingsCacheKey, function () {
//            return $this->getSettingModel()->pluck('val', 'name');
//        });
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null, $fresh = false)
    {
        return $this->all($fresh)->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $val = null)
    {
        // if its an array, batch save settings
        if (is_array($key)) {
            foreach ($key as $name => $value) {
                $this->set($name, $value);
            }

            return true;
        }

        $setting = $this->getSettingModel()->firstOrNew(['name' => $key]);
        $setting->val = $val;
        $setting->save();

        $this->flushCache();

        return $val;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->all()->has($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $deleted = $this->getSettingModel()->where('name', $key)->delete();

        $this->flushCache();

        return $deleted;
    }

    /**
     * {@inheritdoc}
     */
    public function flushCache()
    {
        return Cache::forget($this->settingsCacheKey);
    }

    /**
     * Get settings eloquent model.
     */
    protected function getSettingModel(): Builder
    {
        return (new Settings())->newQuery();
    }
}
