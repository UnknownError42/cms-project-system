<?php
namespace Project;

use App\Models\Project;
use Core\Di;
use Core\Directory;

class Manager {

    public function getPath($dirName, $projectName) {
        return $this->getDocRoot() . '/' . $dirName . '/' . $projectName;
    }

    public function getDocRoot() {
        return Di::getConfig()->get('doc-root');
    }

    public function getCmsPath() {
        return Di::getConfig()->get('cms-path');
    }

    protected function getCoreConfig($name) {
        $config = Di::getConfig()->get('core');
        if ($config->has($name) && $config->get($name)->has('path')) {
            return $config->get($name);
        }
        throw new \Exception("Invalid core config");
    }

    /**
     *
     *
     * @param null $exclude
     * @return Group[]
     */
    public function getGroups($exclude = null) {
        $groups = array();
        foreach (new \DirectoryIterator($this->getDocRoot()) as $fileInfo) {
            if ($fileInfo->isDir() && false === $fileInfo->isDot()) {
                $sort = str_replace('user', '', $fileInfo->getFilename());
                $groups[$sort] = new Group($fileInfo->getFilename());
            }
        }
        ksort($groups);
        if ($exclude !== null) {
            /** @var Group $group */
            foreach ($groups as $i => $group) {
                if ($exclude === $group->getName()) {
                    unset ($groups[$i]);
                    break;
                }
            }
        }
        return $groups;
    }

    public function getAlphaKeys() {
        return range('A', 'Z');
    }

    /**
     *
     *
     * @param $data
     * @param $sortKey
     * @param bool $isArray
     * @param int $sort_flags
     * @return array
     */
    public function SortByKeyValue($data, $sortKey, $isArray = false, $sort_flags=SORT_ASC) {
        if (empty($data) || empty($sortKey)) {
            return $data;
        }
        $ordered = array();
        foreach ($data as $key => $value) {
            if ($isArray) {
                $ordered[$value[$sortKey]] = $value;
            } else {
                $ordered[$value->$sortKey] = $value;
            }
        }
        ksort($ordered, $sort_flags);
        return array_values($ordered);
    }

    /**
     *
     *
     * @param $dir
     * @return Project[]
     */
    public function getProjects($dir) {
        $path = $this->getDocRoot() . '/' . $dir;
        if (is_dir($path)) {
            $rows = array();
            foreach (new \DirectoryIterator($path) as $fileInfo) {
                if ($fileInfo->isDir() && false === $fileInfo->isDot()) {
                    if (($project = Project::get($fileInfo->getFilename()))) {
                        $rows[$project->id] = $project;
                    }
                }
            }
            $rows = $this->SortByKeyValue($rows, 'name');
            return $rows;
        }
        return false;
    }

    /**
     *
     *
     * @param $dirName
     * @param $coreKey
     * @param $projectName
     * @return bool
     * @throws \Exception
     */
    public function createProject($dirName, $coreKey, $projectName) {
        $config     = $this->getCoreConfig($coreKey);
        $coreDir    = $config->get('path');
        $writable   = $config->get('writable')->asArray();

        $projectPath = $this->getPath($dirName, $projectName);
        if (is_dir($projectPath)) {
            throw new \Exception(
                sprintf("Project %s already exists in %s", $projectName, $projectPath)
            );
        }

        $user = Di::getAuth()->getUser();

        $directory = new Directory();
        $directory->copyRecursive($coreDir, $projectPath, $dirName);
        $directory->setPathPermissions($projectPath, $writable);

        $configFile = $projectPath . '/data/config.php';
        $configData = file_get_contents($configFile);
        $replaceVar = array(
            '{DOMAIN-NAME}'     => $projectName,
            '{DOMAIN-EMAIL}'    => 'info@' . $projectName,
            '{DEVELOPER-NAME}'  => $user->fullname,
            '{DEVELOPER-EMAIL}' => $user->email,
            '{TESTER-NAME}'     => $config->get('tester-name'),
            '{TESTER-EMAIL}'    => $config->get('tester-email')
        );
        $configData = str_replace(array_keys($replaceVar), array_values($replaceVar), $configData);
        file_put_contents($configFile, $configData);

        $newProject = new Project();
        $newProject->setData(array(
            'name'          => $projectName,
            'domain'        => $projectName . '.udev',
            'docroot'       => $projectPath . '/public',
            'created'       => time(),
            'created_by'    => $user->getId()
        ));
        return $newProject->save();
    }

    /**
     *
     *
     * @param $projectName
     * @param $dirName
     * @return bool
     * @throws \Exception
     */
    public function moveProject($projectName, $dirName) {
        $project = Project::get($projectName);
        if (false === $project) {
            throw new \Exception(sprintf("Projekt %s nicht gefunden", $projectName));
        }

        $oldPath = str_replace("/public", "", $project->docroot);
        $newPath = $this->getPath($dirName, $projectName);

        if (is_dir($newPath)) {
            throw new \Exception(sprintf("Projekt %s existiert bereits in %s", $projectName, $dirName));
        }

        if (false === is_dir($oldPath)) {
            throw new \Exception(sprintf("Projekt %s existiert nicht", $projectName));
        }

        $config    = $this->getCoreConfig($project->core);
        $writable  = $config->get('writable')->asArray();

        $directory = new Directory();
        $directory->copyRecursive($oldPath, $newPath);
        $directory->setPathPermissions($newPath, $writable);
        $directory->deleteRecursive($oldPath);

        $newDocRoot = $newPath . '/public';
        $project->setData('docroot', $newDocRoot);
        return $project->save();
    }

}