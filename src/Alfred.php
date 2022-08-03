<?php

namespace Alfred\Workflows;

class Alfred
{
    protected $envPrefix = 'alfred_';

    public function preferences()
    {
        return $this->get('preferences');
    }

    public function preferencesLocalhash()
    {
        return $this->get('preferences_localhash');
    }

    public function theme()
    {
        return $this->get('theme');
    }

    public function themeBackground()
    {
        return $this->get('theme_background');
    }

    public function themeSelectionBackground()
    {
        return $this->get('theme_selection_background');
    }

    public function themeSubtext()
    {
        return $this->get('theme_subtext');
    }

    public function version()
    {
        return $this->get('version');
    }

    public function versionBuild()
    {
        return $this->get('version_build');
    }

    public function workflowBundleid()
    {
        return $this->get('workflow_bundleid');
    }

    public function workflowCache()
    {
        return $this->get('workflow_cache');
    }

    public function workflowData()
    {
        return $this->get('workflow_data');
    }

    public function workflowName()
    {
        return $this->get('workflow_name');
    }

    public function workflowUid()
    {
        return $this->get('workflow_uid');
    }

    public function workflowVersion()
    {
        return $this->get('workflow_version');
    }

    public function debug()
    {
        return $this->get('debug');
    }

    public function debugging()
    {
        return !!$this->debug();
    }

    public function all()
    {
        return array_filter($_SERVER, function ($value, $key) {
            return strpos($key, $this->envPrefix, 0) === 0;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function get(string $name = null)
    {
        if (!$name) {
            return $this->all();
        }

        // Replace the prefix and add it back on in case they are passing it in
        $name = $this->envPrefix . str_replace($this->envPrefix, '', $name);

        if (array_key_exists($name, $_SERVER)) {
            return $_SERVER[$name];
        }

        return null;
    }
}
