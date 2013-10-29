#events#


Events erweitern eine Klasse um die Methoden `on` und `trigger`. Als erster Parameter beider Methoden wird dabei ein Schlüsselwort übergeben.

Events sind als `trait` definiert und werden deshalb über `use` eingebunden.

	class Car {
		use events;
		
		/**
		* Speichert das Objekt
		*/
		function drive() {
			
			$this->trigger("startDriving");
			
			/* Fahren */
			
			$this->trigger("stopDriving");
			
			
		}
		
	}

TODO: Beschreibe diese Klasse


	$birne = new Car();
	
	$birne->on("startDriving", function(){
		echo 'Auto fährt los!';
	});
	
	$birne->on("startDriving", function(){
		echo 'Auto ist angekommen!';
	});


	$birne->drive();




TODO: Arguments für trigger!