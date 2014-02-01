#Neurons#
My favorite PHP-Framework. =)

##Install##

- **Bower:** `bower install neurons --save`
- **Git:** `git clone https://github.com/platdesign/Neurons`
- **Download:** [ZIP](https://github.com/platdesign/Neurons/archive/master.zip)


##Getting started##
Create an `index.php` for a litte 'Hello World'-Example.
	
	require 'neurons/neurons.php';
	
	$app = nrns::module('app', []);
	
	$app->service('hello', function(){
		return 'Hello World!';
	});
	
	$app->run(function($response, $hello){
		$response->setBody( $hello );
	});




##Modules##
###[router](https://github.com/platdesign/neurons-router)###
Ein Modul mit dem URL-Routen mit Closure-Funktionen belegt werden können.

###[fs](https://github.com/platdesign/neurons-fs)###
Mit diesem Modul sind Datei-Operationen ein Kinderspiel. Dateien, Ordner, schreiben lesen, durchgehen...

###[pages](https://github.com/platdesign/neurons-pages)###
Schnell und einfach ein Grundgerüst für eine Content-Seite bauen. Mit pages kein Problem. Über Ordner und Dateien werden automatisch Routen erstellt.


###[snippets](https://github.com/platdesign/neurons-snippets)###
Snippets-Container. Mehr dazu auf den Modulseiten.


###[sql](https://github.com/platdesign/neurons-sql)###
SQL-Query wrapper.


###[language](https://github.com/platdesign/neurons-language)###
Dieses Modul ermöglicht multi-linguale Auftritte.

###[express](https://github.com/platdesign/neurons-express)###
Unkompliziert eine JSON-API erstellen.

###[jappi](https://github.com/platdesign/neurons-jappi)###

###[account](https://github.com/platdesign/neurons-account)###


##Contact##

- [mail@platdesign.de](mailto:mail@platdesign.de)
- [platdesign](https://twitter.com/platdesign) on Twitter

![qwe](http://vizcard.bedit.de/plati/as.svg)


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/platdesign/neurons/trend.png)](https://bitdeli.com/free "Bitdeli Badge")


