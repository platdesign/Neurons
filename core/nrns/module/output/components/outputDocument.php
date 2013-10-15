<?PHP
namespace nrns\module\output\components;

class outputDocument implements I_outputDocument {
	
	public function render(){
		
	}
	
	public function __tostring(){
		return (string) $this->render();
	}
	
}
?>