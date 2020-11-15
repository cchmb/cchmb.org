# cchmb.org

This repo contains the (old) contents of cchmb.org, including the WordPress
installation, and all plugins and themes.  This repo was used when the site was
self-hosted, but since it was migrated to WordPress.com in Nov 2020, this repo
is mostly obsolete.  It's still somewhat helpful for creating a development
environment for the site, or just for reference.

This repo uses git-lfs for the contents of the public/content/uploads directory,
as well as nested submodules for some themes and plugins.  Some of those
submodules are in private repositories.

To setup:

- get access to the private repos
- install git-lfs (https://git-lfs.github.com/)
- `git clone https://github.com/cchmb/cchmb.org --recurse-submodules`
- setup WordPress database and database config (instructions not included here)
