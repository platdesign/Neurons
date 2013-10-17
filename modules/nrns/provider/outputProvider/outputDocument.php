<?PHP
namespace nrns\provider\outputProvider;

class outputDocument {
	
	public function render(){
		
	}
	
	public function __tostring(){
		return (string) $this->render();
	}
	
}
?>