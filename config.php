<?php
/**
 * This file is copied to config.php by kick
 *
 * Placeholders (\%CONF_ENVNAME\%) are replaced by the values found in environment
 */

define("CONF_REPO_URI",                 "https://github.com/infracamp/rudl-manager.git");
define("CONF_REPO_SSH_PRIVATEKEY",      "");
define("CONF_REPO_CONF_FILE",           "/config_demo/rudl-manager.yml");
define("CONF_REPO_STACK_FILE_FOLDER",   "/config_demo/stacks/");

/**
 * Secret the CloudFront polls CloudConfig and SSL-Certs from Hypervisor
 */
define("CONF_CLOUDFRONT_SECRET",        "NO_VALID_SECRET123");

define("CONF_REPO_TARGET", "/srv/repo");
define("CONF_STORAGE_PATH", "/srv");