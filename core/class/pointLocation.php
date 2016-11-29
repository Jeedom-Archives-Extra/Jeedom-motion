<?php
	class pointLocation {
		private $pointOnVertex = true; // Vérifier si le point est exactement sur un sommet ?
		public function pointLocation() {
		}
		public function pointInPolygon($point, $polygon, $pointOnVertex = true) {
			$this->pointOnVertex = $pointOnVertex;
			// Transformer chaque couple de coordonnées en un tableau de 2 valeurs (x et y)
			$point = $this->pointStringToCoordinates($point);
			$vertices = array(); 
			foreach ($polygon as $vertex) 
			$vertices[] = $this->pointStringToCoordinates($vertex); 
			// Vérfier si le point est exactement sur un sommet
			if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) 
			return "vertex";
			// Vérifier si le point est dans le polygone ou sur le bord
			$intersections = 0; 
			$vertices_count = count($vertices);
			for ($i=1; $i < $vertices_count; $i++) {
				$vertex1 = $vertices[$i-1]; 
				$vertex2 = $vertices[$i];
				if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x']))  // Vérifier si le point est sur un bord horizontal
					return "boundary";
				if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
					$xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
					if ($xinters == $point['x'])  // Vérifier si le point est sur un bord (autre qu'horizontal)
						return "boundary";
					if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) 
						$intersections++; 
				} 
			} 
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
		public function pointStringToCoordinates($pointString) {
			$coordinates = explode(" ", $pointString);
			return array("x" => $coordinates[0], "y" => $coordinates[1]);
		}
	}
?>
