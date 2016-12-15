<?php
	class pointLocation {
		private $pointOnVertex = true; 
		private $polygon = array(); 
		function __construct($polygon, $pointOnVertex = true) {
			$this->pointOnVertex = $pointOnVertex;
			// Transformer chaque couple de coordonnées en un tableau de 2 valeurs (x et y)
			if(is_array($polygon) && count($polygon) >0){
				foreach ($polygon as $vertex) 
					$this->polygon[] = $this->pointStringToCoordinates($vertex); 
				$this->polygon[] =$this->polygon[0];
			}
			else
				return false;
		}
		// Vérifier si le point est exactement sur un sommet ?
		public function pointInPolygon($point) {
			log::add('motion','debug','Points polygon : '.json_encode($this->polygon));
			// Vérfier si le point est exactement sur un sommet
			if ($this->pointOnVertex == true && $this->pointOnVertex($point, $this->polygon) == true) 
				return "vertex";
			// Vérifier si le point est dans le polygone ou sur le bord
			$intersections = 0; 
			$polygon_count = count($this->polygon);
			for ($i=1; $i < $polygon_count; $i++) {
				$vertex1 = $this->polygon[$i-1]; 
				$vertex2 = $this->polygon[$i];
				// Vérifier si le point est sur un bord horizontal
				if ($vertex1['y'] == $vertex2['y'] && $vertex1['y'] == $point['y'] && $point['x'] > min($vertex1['x'], $vertex2['x']) && $point['x'] < max($vertex1['x'], $vertex2['x']))  
					return "boundary";
				if ($point['y'] > min($vertex1['y'], $vertex2['y']) && $point['y'] <= max($vertex1['y'], $vertex2['y']) && $point['x'] <= max($vertex1['x'], $vertex2['x']) && $vertex1['y'] != $vertex2['y']) { 
					$xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
					// Vérifier si le point est sur un bord (autre qu'horizontal)
					if ($xinters == $point['x'])  
						return "boundary";
					if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) 
						$intersections++; 
				} 
			} 
			
			log::add('motion','debug','Nb intersections : '.$intersections);
			// Si le nombre de bords par lesquels on passe est impair, le point est dans le polygone. 
			if ($intersections % 2 != 0) 
				return "inside";
			else 
				return "outside";
		}
		public function pointOnVertex($point, $vertices) {
			foreach($vertices as $vertex) {
				if ($point == $vertex) 
					return true;
			}
		}
		public function pointStringToCoordinates($coordinates) {
			//$coordinates = explode(" ", $pointString);
			return array("x" => $coordinates[0], "y" => $coordinates[1]);
		}
	}
?>
