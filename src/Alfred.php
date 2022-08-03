<?php

namespace Alfred\Workflows;

class Alfred
{
    protected $envPrefix = 'alfred_';

    public function preferences()
    {
        return $this->getEnv('preferences');
    }

    public function preferencesLocalhash()
    {
        return $this->getEnv('preferences_localhash');
    }

    public function theme()
    {
        return $this->getEnv('theme');
    }

    public function themeBackground()
    {
        return $this->getEnv('theme_background');
    }

    public function themeSelectionBackground()
    {
        return $this->getEnv('theme_selection_background');
    }

    public function themeSubtext()
    {
        return $this->getEnv('theme_subtext');
    }

    public function version()
    {
        return $this->getEnv('version');
    }

    public function versionBuild()
    {
        return $this->getEnv('version_build');
    }

    public function workflowBundleid()
    {
        return $this->getEnv('workflow_bundleid');
    }

    public function workflowCache()
    {
        return $this->getEnv('workflow_cache');
    }

    public function workflowData()
    {
        return $this->getEnv('workflow_data');
    }

    public function workflowName()
    {
        return $this->getEnv('workflow_name');
    }

    public function workflowUid()
    {
        return $this->getEnv('workflow_uid');
    }

    public function workflowVersion()
    {
        return $this->getEnv('workflow_version');
    }

    public function debug()
    {
        return $this->getEnv('debug');
    }

    public function getAllEnv()
    {
        return array_filter($_SERVER, function ($value, $key) {
            return strpos($key, $this->envPrefix, 0) === 0;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function getEnv(string $name = null)
    {
        if (!$name) {
            return $this->getAllEnv();
        }

        // Replace the prefix and add it back on in case they are passing it in
        $name = $this->envPrefix . str_replace($this->envPrefix, '', $name);

        if (array_key_exists($name, $_SERVER)) {
            return $_SERVER[$name];
        }

        return null;
    }
}
