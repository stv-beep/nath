# Aplicació per a fitxatges Nath v0.9.8
#### Aleix Algueró, 2022
## Sobre l'aplicació
App per a realitzar fitxatges d'inici i final de jornades i torns a l'empresa, a més de fitxatges de certes tasques concretes de magatzem. Aquests fitxatges (torns, jornades senceres i tasques) podran ser llistats pel propi treballador des de dins de l'aplicació. I l'administrador podrà llistar totes les jornades de tothom i fer consultes des d'una taula dinàmica. A més, l'administrador podrà canviar certs camps dels usuaris, crear-ne de nous i eliminar-los.
Ara mateix, en versió 0.9.8, sembla que té una certa estabilitat.

### Update 0.9.8
- Corregit error de momentjs.
- Corregit error de traduccions.

### Update 0.9.7
- Correcció de traduccions.
- Afegit SoftDelete als usuaris. Ara els usuaris no es borraran del tot, per a mantenir les seves jornades i tasques guardades.

### Update 0.9.6
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


### Errors i bugs (versió 0.9.8)
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
npm install
```
```
cp .env.example .env
```
```
php artisan key:generate
```
#### A l'arixiu '.env', canviar a les dades correctes de connexió a la BBDD.

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
#### Si el composer funciona, fer un migrate de la BBDD, sinó, crear-la utilitzant l'arxiu 'nath.sql'. En aquest arxiu estan totes les taules i tipus de tasques i tasques ja preparades.

## Algunes respostes a possibles preguntes
- No es poden afegir noves tasques a l'aplicació de manera automàtica. Si s'ha d'afegir-ne una, s'hauran de crear els mètodes de backend i frontend adients, a més de crear la tasca a la base de dades.
- Quan es borra un usuari, el que permet que continuï a la base de dades però no es mostri a l'aplicació és la columna 'deleted_at' de la taula 'users', si es torna a posar el camp NULL, tornarà al seu estat anterior. Si es vol eliminar un usuari del tot de la BBDD, s'haurà d'eliminar totes les seves jornades, torns i tasques i després al propi usuari, des del gestor de BBDD.
- Si a un servidor nou no funciona el composer, s'haurà de crear la base de dades a partir del 'nath.sql' d'aquest repositori, o si es vol mantenir les dades ja guardades, exportar aquella base de dades i copiar el script a la nova.

### Si hi ha algun dubte, només cal contactar-me (aleixalguero0@gmail.com)