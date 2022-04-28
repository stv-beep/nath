# Aplicació per a fitxatges Nath v0.9.6
#### Aleix Algueró, 2022
## Sobre l'aplicació
App per a realitzar fitxatges d'inici i final de jornades i torns a l'empresa, a més de fitxatges de certes tasques concretes de magatzem. Aquests fitxatges (torns, jornades senceres i tasques) podran ser llistats pel propi treballador des de dins de l'aplicació. I l'administrador podrà llistar totes les jornades de tothom i fer consultes des d'una taula dinàmica. A més, l'administrador podrà canviar certs camps dels usuaris.
Ara mateix, en versió 0.9.6, sembla que té una certa estabilitat.

### Update 0.9.5
- Afegida la creació d'usuaris des d'administració d'usuaris.
- Afegit l'eliminació d'usuaris des d'administració d'usuaris.
- A l'edició d'usuaris, afegida l'opció per editar el DNI.
- Correcció d'alguns errors.

### Update 0.9.5
- Afegits botons per a exportar a PDF, .xls i CSV a cadascuna de les consultes de reporting (a algunes consultes no està disponible el PDF).
- Fix d'algunes traduccions.
- Afegit un spinner loader a l'apartat de gestió d'usuaris i al de reports.
- Correccions al backend, sobretot en logs.

### Update 0.9.4
- Afegits dos nous camps als usuaris per a vincular-los a l'Odoo: ID d'Odoo de Nath i ID d'Odoo de TucTuc.
- Afegits botons de copiar, descarregar amb csv, excel, pdf o imprmir per al reporting d'hores.
- Afegit un nou apartat anomenat "Usuaris" on els administradors podran editar certs aspectes dels usuaris com el seu ID d'Odoo de Nath, de TucTuc, si són administradors o no o si estan a magatzem o no.
- Corregit un error al mostrar les hores d'inici i de final al reporting on les hores es mostraven en format 12h sense indicar si eren abans o després del migdia. Ara es mostra en format 24h.

### Update 0.9.3
- Afegit "recepcions", "reoperacions" i "inventari" i les seves activitats.
- Canvis en detalls de la navbar.
- Si no hi ha tasques d'un tipus concret, mostra un avís.

### Update 0.9.2
- Fix al display de les consultes de torns i tasques al reporting des de mòbil, que no cabien a la pantalla.
- Ara als teus torns es mostra l'inici i el final de cada un.
- Si una taula està buida mostra un avís.

### Update 0.9.1
- Solucionat l'error de canvi de dia mentre hi ha un torn obert (corrent el temps), pel que fa a torns i jornades.
- Corregit un error d'accés al reporting.
- Corregit un error al reporting de torns, on si es repetia una consulta, o una de nova, sense haver refrescat o sortit de la pàgina, apareixia, a part de la nova consulta, l'anterior, inclús si estava repetida.
- Nova consulta de tasques en una jornada afegida al reporting.
- Canvis al backend de tasques.
- Corregit un error en el que s'iniciava i parava el torn molt ràpid, de manera que el torn havia durat 0 i llavors apareixia l'icona de torn inacabat encara.
- Canvis de disseny de taules i navbar.
- Canvis als botons de reporting en versió mòbil.
- Afegit el nom de la tasca que encara està oberta a l'avís quan intentes acabar torn/jornada.
- Afegit un avís amb el nom de la tasca que encara està oberta quan s'accedeix a una altra activitat.
- Afegida nova activitat "recepcions" com a test.
- Icones d'idioma.


### Errors i bugs (versió 0.9.6)
- La geolocalització no funciona sense HTTPS.
- Si s'inicia una tasca i es finalitza molt ràpid (cosa antinatural), es pot arribar a buguejar i deixar penjada una tasca inacabada mentres s'ha començat una de nova.
- Desactivant el JS segurament es pot trencar el funcionament dels botons. És possible que es puguen iniciar varies tasques alhora i que el programa perdi la seva funcionalitat de sèrie.
- La suma automàtica d'hores al reporting en versió MÒBIL no funciona.
- El scroll al reporting en versió mòbil no funciona bé i es queda bloquejat (sembla que això només passa amb algun navegador concret).
- Algunes consultes al report no tenen l'opció d'exportar a PDF ja que el resultat no és l'esperat.
- Degut a un problema amb l'actualització de la taula d'usuaris per a editar-los. Quan es fa una modificació, s'actualitza la pàgina directament.


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

#### Migrar BBDD
```
php artisan migrate && php artisan db:seed
```

### Deploy setup

##### Per a desplegar-lo serà millor mirar documentació
- [Documentació molt útil sobre deplegament a servidor](https://www.nigmacode.com/laravel/subir-proyecto-laravel-a-hosting)
- [Més desplegament](https://platzi.com/tutoriales/2182-intro-laravel/9744-como-desplegar-una-app-hecha-en-laravel)
- [Official Documentation](https://laravel.com/docs/8.x/deployment)
#### Al fitxer .env
```
APP_ENV=production
APP_DEBUG=false
```
#### Al directori (és possible que això doni error; si dóna, saltem aquest pas)
```
npm run production
```
```
composer dumpautoload
```

##### Comprimir App
##### Descomprimir l'app al sistema d'arxius del servidor.
##### Moure els arxius de la carpeta 'public' a l'arrel del subdomini i borrar la 'public' buida.
##### Al fitxer 'index.php' canviar les rutes dels __DIR__ per les que hi hauran ara al moure els arxius de /public. Per exemple:
```
'/../vendor/autoload.php'
```
###### per
```
'/vendor/autoload.php'
```
###### ja que 'index.php' i els arxius que es referencien aquí estaran al mateix nivell al directori.

##### A la function 'register' a 'app/Providers/AppServiceProvider.php' afegir el vhost del servidor:
```
    $this->app->bind('path.public',function(){
		return'/ruta/absoluta/vhost/web_url';
		});
```
#### Si el composer funciona, fer un migrate de la BBDD, sinó, crear-la utilitzant l'arxiu 'nath.sql'.

##### Informació de Laravel
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