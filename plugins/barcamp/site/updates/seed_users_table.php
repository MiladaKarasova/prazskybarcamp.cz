<?php namespace Barcamp\Site\Updates;

use File;
use RainLab\User\Models\User;
use RainLab\User\Models\UserGroup;
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
        $group = $this->createGroup();

        foreach ($items as $item)
        {
            // create user
            $item['password_confirmation'] = $item['password'];
            $item['is_activated'] = true;
            $team = isset($item['team']) && $item['team'];
            unset($item['team']);

            $user = new User();
            $user->fill($item);

            // add avatar
            $avatar = $this->getAvatar();
            if ($avatar) {
                $user->avatar = $avatar;
            }

            // save user
            $user->save();

            // add to team group
            if ($team) {
                $user->addGroup($group);
            }
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

    /**
     * Create Team user group.
     *
     * @return UserGroup
     */
    private function createGroup()
    {
        $userGroup = new UserGroup();
        $userGroup->name = "Team";
        $userGroup->code = "team";
        $userGroup->description = "Barcamp Team Members";
        $userGroup->save();

        return $userGroup;
    }
}
