<?php namespace Barcamp\Partners\Updates;

use Barcamp\Partners\Models\Category;
use Barcamp\Partners\Updates\Classes\Seeder;
use File;
use October\Rain\Support\Str;
use Yaml;

class SeedCategoriesTable extends Seeder
{
    protected $seedFileName = '/categories.yaml';

    protected $seedDirPath = '/sources';

    public function run()
    {
        $defaultSeed = __DIR__ . $this->seedDirPath . $this->seedFileName;
        $seedFile = $this->getSeedFile($defaultSeed);
        $items = Yaml::parse(File::get($seedFile));

        foreach ($items as $item)
        {
            $item['enabled'] = true;
            $item['slug'] = Str::slug($item['name']);
            Category::create($item);
        }
    }
}
