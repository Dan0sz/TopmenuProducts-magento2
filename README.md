# Topmenu Products for Magento 2

## Overview

By default Magento 2 only allows you to add categories to its topmenu. This module makes it possible to also add products to it.

## Features

- Easily add products to your store's topmenu, by checking the 'Add product to Topmenu?' attribute.
- Replace the product's name with a custom label.
- Decide the display order of your Topmenu Products by adding a sort order.
- Replace the product's URL with the stores Base Url. This might be useful if e.g. you have your store's *Default Web URL* set to a product's url (e.g. `catalog/product/view/id/1`) instead of `cms`.
- Choose if products should be added before or after the existing categories. 

## Installation

### Using Composer

Installation using Composer is easy and recommended. From a terminal, just run:

`composer require dan0sz/topmenu-products-magento2`

### Manually

If you can't or dont want to use Composer, you can download the `master`-branch of this repository and copy the contents to `app/code/Dan0sz/TopmenuProducts`.

### After installation

#### Developer Mode

- Run `bin/magento setup:upgrade`
- Done!

#### Production Mode

- Run `bin/magento setup:upgrade`
- Run `bin/magento setup:di:compile`
- Run `bin/magento setup:static-content:deploy [locales e.g. en_US nl_NL`
- Done!

## Configuration

After installation a new tab is added to *Stores > Configuration > Catalog* and *Catalog > Products > Edit*, called *Topmenu Products*.