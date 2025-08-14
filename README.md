# hh_powermail_mosparo
hh_powermail_mosparo is a TYPO3 extension which extends the EXT:powermail.
Integrates the mosparo spam protection system into TYPO3 Powermail extension, enabling easy and effective spam protection for Powermail forms.

Thanks to [SICOR Dev Team](https://github.com/SicorDev "GitHub profile: SicorDev")

### Installation
... like any other TYPO3 extension [extensions.typo3.org](https://extensions.typo3.org/ "TYPO3 Extension Repository")
Don't forget to include PageTS / look at features section

### Configuration
Default / fallback configuration is done via TYPO3 constants-editor.
You can overwrite the settings of the constant-editor within the powermail field.

You get the "privatekey", "publickey", "host", "uid" from your mosparo installation.
If you enable the "debug" option, then you will receive more information from various locations in a separate log file located next to the typo3 log file.

Do not change "354"! :)

TypoScript:
```
plugin.tx_powermail {
    settings {
        setup {
            spamshield {
                methods {
                    354 {
                        configuration {
                            # Mosparo host
                            host = {$plugin.tx_hhpowermailmosparo.host}

                            # Private key
                            privatekey = {$plugin.tx_hhpowermailmosparo.privatekey}

                            # Public key
                            publickey = {$plugin.tx_hhpowermailmosparo.publickey}

                            # Unique identification number
                            uid = {$plugin.tx_hhpowermailmosparo.uid}

                            # debug = true
                        }
                    }
                }
            }
        }
    }
}
```

#### Main-view
![example picture from frontend](.github/images/mosparo-fe.jpg?raw=true "Frontend")

![example picture from backend](.github/images/mosparo-be.jpg?raw=true "Backend")

##### Copyright notice

This repository is part of the TYPO3 project. The TYPO3 project is
free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

The GNU General Public License can be found at
http://www.gnu.org/copyleft/gpl.html.

This repository is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

This copyright notice MUST APPEAR in all copies of the repository!

##### License
----
GNU GENERAL PUBLIC LICENSE Version 3
