<?PHP

namespace mysql;
	
	class EntityException extends \Exception {}


	class Entity {
	
		public function __construct($attrs=[]) {
			$this->set($attrs);
			return $this;
		}
	
		public function save() {
			if(isset($this->id)) {
				return $this->update();
			} else {
				return $this->create();
			}
		}
	
		public function set($key, $val=null) {
		
			if( is_object($key) || is_array($key) ) {
			
				foreach($key as $k => $v) {
					$this->set($k, $v);
				}
			
			} else {
			
				if( method_exists($this, 'set'.$key) ) {
					$this->{$key} = call_user_func_array([$this, 'set'.$key], [$val]);
				} else {
					$this->{$key} = $val;
				}
			
			}
		
		}
	
		public function destroy() {
			if( isset($this->id) ) {
			
				return \mysql::DeleteQuery('DELETE FROM `Account` WHERE `id` = :id;', ['id'=>$this->id])
				->on('done', function(){
					$this->reset();
				});
			
			}
		}
	
		final public function reset() {
			foreach($this as $key => $val) {
				//$this->{$key} = null;
				unset($this->{$key});
			}
		}
	
	}
	
	
	



?>