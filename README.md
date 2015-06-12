# Fuelphp Two authentification with TOTP
### A fuelphp package for create two authentification with TOTP system.

---

This package can create a QRCode as Google, Microsoft two authentification factor using Google authenticator on **[IOS](https://itunes.apple.com/fr/app/google-authenticator/id388497605?mt=8)** and **[Android](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=fr)**, or Microsoft Authenticator for **[Windows Phone](http://www.windowsphone.com/fr-fr/store/app/authenticator/e7994dbc-2336-4950-91ba-ca22d653759b)**. 

## About

- Version: 1.0 alpha
- License: MIT License
- Author: **[Dryusdan](http://www.dryusdan.fr)**
- Author of OTP: **[Christian Riesen] (https://github.com/ChristianRiesen/otp)**
- Author of Base32: **[Christian Riesen] (https://github.com/ChristianRiesen/base32)**

## Warning
This is an alpha test, it's stable but comments are missing and some small functions.
Sorry

## Installation

You can simply download the package and extract it into `fuel/packages/totp` folder or you can go under your `fuel/packages` folder and run this command:
```shell
  $ git clone https://github.com/Dryusdan/fuelphp-TOTP
```

## Configuration
You need to create an member area. When it's ok, you need create database for this package. Run command
```shell
php oil refine migrate --packages=totp
```
for create table.

## Common Methods
For create member, you need: id of member, email of member.

### Create user key and QRCode
```php

<?php
    $addUsers = Totp::addUser('1', 'some@email.ex');
    $QRCode = Totp::generateQrCode('some@email.ex', $addUsers);
?>
<img src="<?php echo $QRCode; ?>" />
```

### Check token
```php
<?php
    $query = Totp::checkToken('exemple.ex', '303011');
    if($query == '1'){
        echo 'access';
    }
    else{
        echo 'wrong key';
    }
?>
```

This is an alpha test andlater comes later. :)