<?php

/*
  Plugin Name: Prefix replacement
  Plugin URI: https://github.com/VolksmissionFreudenstadt/vmfds-yourls-prefix
  Description: Replace and expand prefixed urls
  Version: 0.1
  Author: Christoph Fischer <christoph.fischer@volksmission.de>
  Author URI: http://open.vmfds.de
 */

// No direct call
if (!defined('YOURLS_ABSPATH'))
    die();
error_reporting(E_ALL);

yourls_add_filter('shunt_get_keyword_info', 'vmfds_prefix_get_keyword_info');

/**
 * Check if this is a short url for a series
 * @param string $existingValue Value from previous filters
 * @param string $keyword Url Keyword
 * @param string $field Keyword field
 * @param string $notfound Filter
 * @return string Url or previous value
 */
function vmfds_prefix_get_keyword_info($existingValue, $keyword, $field, $notfound)
{
    // Important! Safeguard existing value
    $result = $existingValue;

    $configurationFile = YOURLS_ABSPATH.'/user/plugins/vmfds-prefix/plugin.yaml';
    if (file_exists($configurationFile)) 
        $configuration = yaml_parse_file($configurationFile);
    
    // we only treat url fields
    if ($field == 'url') {
        
        // Step 1: Replacements
        if ((isset($configuration['replacements'])) && (is_array($configuration['replacements']))) {
            foreach ($configuration['replacements'] as $replacement) {
                if ((isset($replacement['find'])) && (isset($replacement['replace'])))
                    $keyword = str_replace($replacement['find'], $replacement['replace'], $keyword);
            }
        }
        
        // Step 2: Try prefixed keywords
        foreach ($configuration['prefixes'] as $p) {
            $prefix = $p['prefix'];
        if (substr($keyword, 0, strlen($prefix))==$prefix) {
                $keyword = substr($keyword, strlen($prefix));
                if (is_array($p['subparts'])) {
                    $subParts = [];
                    foreach ($p['subparts'] as $b => $c) {
                        $subParts[] = substr($keyword, $b, $c);
                    }
                    $result = vsprintf($p['url'], $subParts);
                } elseif (isset($p['split'])) {
                    $result = vsprintf($p['url'], explode($p['split'], $keyword));
                } else {
                    $result = sprintf($p['url'], $keyword);
                }
                continue;
            }
        }
    }
    return $result;
}
