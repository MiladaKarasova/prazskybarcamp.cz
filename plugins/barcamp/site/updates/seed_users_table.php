<?php namespace Barcamp\Site\Updates;

use File;
use RainLab\User\Models\User;
use Barcamp\Site\Updates\Classes\Seeder;
use System\Models\File as DiskFile;
use Yaml;

class SeedUsersTable extends Seeder
{
    protected $seedFileName = '/users.yaml';

    protected $seedDirPath = '/sources';

    public function run()
    {
        $defaultSeed = __DIR__ . $this->seedDirPath . $this->seedFileName;
        $seedFile = $this->getSeedFile($defaultSeed);
        $items = Yaml::parse(File::get($seedFile));

        foreach ($items as $item)
        {
            // create user
            $item['password_confirmation'] = $item['password'];
            $item['is_activated'] = true;
            $user = new User();
            $user->fill($item);

            // add avatar
            $avatar = $this->getAvatar();
            if ($avatar) {
                $user->avatar = $avatar;
            }

            // save user
            $user->save();
        }
    }

    /**
     * Get avatar File instance.
     *
     * @return string|null
     */
    private function getAvatar()
    {
        $file = new DiskFile();
        $file->is_public = true;
        $file->fromFile(__DIR__ . '/sources/profile.jpg');

        return $file;
    }
}
