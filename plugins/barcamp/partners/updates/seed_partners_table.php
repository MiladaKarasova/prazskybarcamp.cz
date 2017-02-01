<?php namespace Barcamp\Site\Updates;

use File;
use Barcamp\Partners\Models\Partner;
use Barcamp\Partners\Updates\Classes\Seeder;
use System\Models\File as DiskFile;
use October\Rain\Support\Str;
use Yaml;

class SeedPartnersTable extends Seeder
{
    protected $seedFileName = '/partners.yaml';

    protected $seedDirPath = '/sources';

    public function run()
    {
        $defaultSeed = __DIR__ . $this->seedDirPath . $this->seedFileName;
        $seedFile = $this->getSeedFile($defaultSeed);
        $items = Yaml::parse(File::get($seedFile));

        foreach ($items as $item)
        {
            // create partner
            $item['enabled'] = true;
            $item['slug'] = Str::slug($item['name']);

            $partner = new Partner();
            $partner->fill($item);

            // add logo
            $logo = $this->getLogo();
            if ($logo) {
                $partner->logo = $logo;
            }

            // save partner
            $partner->save();
        }
    }

    /**
     * Get avatar File instance.
     *
     * @return string|null
     */
    private function getLogo()
    {
        $file = new DiskFile();
        $file->is_public = true;
        $file->fromFile(__DIR__ . '/sources/logo.svg');

        return $file;
    }
}
