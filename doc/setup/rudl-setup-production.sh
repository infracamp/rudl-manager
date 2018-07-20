#!/bin/bash


sudo apt-get install pwgen curl docker.io

# Generate the secret for syncing SSL-Certs between Cloudfront and Manager
pwgen -s -1 1024 | docker secrets create CONF_RUDL_SECRET_CLOUDFRONT

# Generate the secret for internal encryption (inside manager node)
pwgen -s -1 1024 | docker secrets create CONF_RUDL_SECRET_INTERNAL







