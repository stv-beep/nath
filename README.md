# Aplicació per a fitxatges Nath v0.8.4
### Autor
#### Aleix Algueró, 2022

## Sobre l'aplicació

És una aplicació per a realitzar fitxatges d'inici i final de jornades i torns a l'empresa, a més de fitxatges de certes tasques concretes de magatzem. Aquests fitxatges (torns, jornades senceres i tasques) podran ser llistats pel propi treballador des de dins de l'aplicació.
Ara mateix, en versió 0.8.4, sembla que té una certa estabilitat.

### Development setup
##### Compte amb això ja que l'aplicació està desenvolupada en Laravel 8
#### A dins del directori del projecte
```
composer install
```
```
cp .env.example .env
```
```
php artisan key:generate
```
```
php artisan serve
```
```
Comprovar fitxer 'vendorstocats.txt' ja que per al login sense contrasenya s'han tingut que manipular alguns arxius allí indicats.
```
#### Migrar BBDD
```
php artisan migrate && php artisan db:seed
```

### Deploy setup
#### Per a desplegar l'aplicació hi ha dos opcions: ip local o desplegar-lo a un servidor
##### Si és per ip local, només caldria descomprimir l'aplicació al servidor i iniciar el php artisan:
```
php artisan serve --host=<ip del servidor> --port=8000
```

##### Per a desplegar-lo serà millor mirar documentació
- [Documentació útil] (https://www.nigmacode.com/laravel/subir-proyecto-laravel-a-hosting)
- [Documentation](https://platzi.com/tutoriales/2182-intro-laravel/9744-como-desplegar-una-app-hecha-en-laravel)
- [Apache deploy Documentation](https://help.clouding.io/hc/es/articles/4406607535634-C%C3%B3mo-Desplegar-Laravel-8-con-Apache-y-Let-s-Encrypt-SSL-en-Ubuntu-20-04)
- [Official Documentation](https://laravel.com/docs/8.x/deployment)
#### Al fitxer .env
```
APP_ENV=production
APP_DEBUG=false
```
#### Al directori
```
npm run production
composer dumpautoload
```
```
Comprimir App
```
```
Descomprimir l'app al sistema d'arxius del servidor
```
```
Moure els arxius de la carpeta 'public' a la 'public_html' i borrar la 'public' buida
```
#### Al /public_html/index.php afegir
```
$app->bind('path.public', function() {
    return __DIR__;
});
```
#### Crear la base de dades

### Errors i bugs (versió 0.8.4)
- L'error principal és el canvi de dia mentre hi ha un torn obert (corrent el temps).
Quan s'acaba el torn, es compta com si la jornada sencera fos del dia en que s'acaba el torn, i, per tant, només se suma al temps de jornada total el temps del segon dia.
- Si s'inicia una tasca i es finalitza molt ràpid (cosa antinatural), es pot arribar a buguejar i deixar penjada una tasca inacabada mentres s'ha començat una de nova.
- Probablement, si d'alguna forma es desactiva 'la desactivació dels botons' és possible que es puguen iniciar varies tasques alhora i que el programa perdi la seva funcionalitat de sèrie.





# Readme de Laravel

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[CMS Max](https://www.cmsmax.com/)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**
- **[Romega Software](https://romegasoftware.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
