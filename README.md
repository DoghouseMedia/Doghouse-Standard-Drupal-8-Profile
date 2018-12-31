# Doghouse Standard Profile

Doghouse Standard Profile is a Drupal 8 distribution that contains common contrib modules and settings used by 
Doghouse Agency for Drupal 8 projects.

## How to install

Ensure you have the Doghouse packages repo:
```
composer config repositories.doghouse composer http://packages.doghouse.agency/
```

Install with composer:
```
composer require doghouse/doghouse-standard-profile:8.6.x
```

## How to test/update this profile

```
PROJ_NAME="d8test" && TEST_BRANCH="MY-TEST-BRANCH" && \
composer create-project drupal-composer/drupal-project:8.x-dev "$PROJ_NAME" --stability dev --no-interaction && \
cd "$PROJ_NAME" && \
mv web docroot && \
sed -i -e 's/web\//docroot\//g' composer.json && \
composer config repositories.doghouse composer http://packages.doghouse.agency/ && \
composer config bin-dir bin && \
composer config secure-http false && \
composer config extra.enable-patching true && \
composer dump-autoload && \
composer require doghouse/doghouse-standard-profile:dev-"$TEST_BRANCH" && \
drush site-install doghouse_standard;
```

## Adding/Editing configurations

One of the benefits of an install profile is we can tweak the config to our liking. This does get a bit tricky sometimes 
due to the dependency stack. The best process is to start by copying the `core/profile/config` folder into this profile 
and then making the required changes.

New changes/configs that are added should go into `config/optional` dir so they only get installed when ready. See 
[this](https://www.drupal.org/node/2453919) for more detail.

If you add exported configs added to `config/optional` check `dependencies > config` for each as they may prevent other 
modules installing.

## What configs have been added/updated?

* Greatly improved media entity browser and supporting views
* Path auto
* Jumbotron block type
* Bartik theme disabled, stark enabled in its place which is a much better starting point for a starter theme

## What's installed by default?

Drupal standard profile is installed first, then the following modules are enabled.

### Core

  - node
  - history
  - block
  - breakpoint
  - ckeditor
  - color
  - config
  - comment
  - contextual
  - menu_link_content
  - datetime
  - block_content
  - quickedit
  - editor
  - help
  - image
  - menu_ui
  - options
  - path
  - page_cache
  - dynamic_page_cache
  - taxonomy
  - dblog
  - search
  - shortcut
  - toolbar
  - field_ui
  - file
  - rdf
  - views
  - views_ui
  - tour
  - automated_cron

### Contrib

 - admin_toolbar
  - admin_toolbar_tools
  - ctools
  - config_filter
  - config_split
  - webform
  - webform_ui
  - token
  - pathauto
  - xmlsitemap
  - ds
  - ds_extras
  - google_tag
  - google_analytics
  - metatag
  - config_readonly
  - redirect
  - panels
  - panels_ipe
  - entity_reference_revisions
  - paragraphs
  - page_manager
  - page_manager_ui
  - config_update
  - features
  - features_ui
  - dropzonejs
  - entity
  - entity_browser
  - media_entity_browser
  - inline_entity_form
  - embed
  - entity_embed
  - media
  - field_group
  - context
  - context_ui
  - panelizer
  - custom_markup_block
