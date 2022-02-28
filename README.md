# proxmox-admin
![logo de l'app proxmox-pannel](https://github.com/Nathannicolle/proxmox-admin/blob/main/public/assets/img/Proxmox_pannel_V7.2_full.png)
<br> Proxmox-pannel est une application qui permet la gestion simplifiée et à distance de serveurs, groupes et VMs proxmox. Elle se base pour l'instant sur des données présentes en Base De Données, elle devrait prochainement se baser sur les données récuppérées via l'API de proxmox. L'application gère également différents niveaux d'accès (via les ACLs) au dashboard en fonction du rôle attribué à l'utilisateur. Le contenu du dashboard diffère en fonction du rôle de l'utilisateur.

## Prerequisites

You will need the following things properly installed on your computer.

* php >=7.4
* [Git](https://git-scm.com/)
* [Composer](https://getcomposer.org)
* [Ubiquity devtools](https://ubiquity.kobject.net/)

## Resources
* Database : [proxmoxadmin.sql](https://github.com/Nathannicolle/proxmox-admin/blob/main/database/proxmoxadmin.sql)
* Logo : [logo de proxmox-pannel](https://github.com/Nathannicolle/proxmox-admin/blob/main/public/assets/img/Proxmox_pannel_V7.2_full.png)
* Version réduite du logo : [logo minimaliste de proxmox-pannel](https://github.com/Nathannicolle/proxmox-admin/blob/main/public/assets/img/Proxmox_pannel_V7.2_light.png)
* Exemple de schéma de gestion des VM : [schéma](https://github.com/Nathannicolle/proxmox-admin/blob/main/public/assets/img/Schema.png)

## Installation

* `git clone <repository-url>` this repository
* `cd proxmox-admin`
* `composer install`

## Running / Development

* `Ubiquity serve`
* Visit the app at [http://127.0.0.1:8090](http://127.0.0.1:8090).
* You can access to dashboard from home page or at [http://127.0.0.1:8090/dasboard/](http://127.0.0.1:8090/dasboard/)

### devtools

Make use of the many generators for code, try `Ubiquity help` for more details

### Optimization for production

Run:
`composer install --optimize --no-dev --classmap-authoritative`

### Deploying

Specify what it takes to deploy your app.

## Further Reading / Useful Links

* [Ubiquity website](https://ubiquity.kobject.net/)
* [Guide](http://micro-framework.readthedocs.io/en/latest/?badge=latest)
* [Doc API](https://api.kobject.net/ubiquity/)
* [Twig documentation](https://twig.symfony.com)
* [Semantic-UI](https://semantic-ui.com)
