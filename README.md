# Prestashop Favorite Products Module

_Favorite Products provides opportunity to save products into favorites list._

_It works for unregistered users as well as for logged-in. It keeps saved products in cookies if you are "guest" and copy them to database when you you log in._

## Demo
https://alysum.promokit.eu/en/3-women

## Installation

* Open terminal and go to "modules" folder of your Prestashop
* Run command: 
```bash
git clone https://github.com/promokit/pkfavorites.git
```
* Go to Prestashop Back-office -> Modules Catalog page, find "Promokit Favorites" module and click "Install" button
* Visit module configuration page to adjust settings

## Add product to favorites logic

Action:
  |__ADD:
     |__Add ID to cookies
     |__Save ID into DB If Logged In
  |__REMOVE:
  |__LOGIN:
     |__Copy IDs from cookies into DB

## Submit an issue

For submitting an issue, please create one in the issues tab. Remember to provide a detailed explanation of your case and a way to reproduce it.

Enjoy!