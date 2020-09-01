<?php
error_reporting(0);
function getOrdemExata($lista){

 // = array([9.5334234 , 878766],[8.784234 , 878766],[2.234234 , 878766],[8.784234 , 878766],[8784234 , 878766]);

// print_r($lista);

	try{

		$count_bids = count($lista);

		for($y=0;$y<$count_bids;$y++){

			$s = implode(explode('.',$lista[$y][0]));

				for($i=0;$i<2;$i++){

					if($i == 0):
						$array[$y][$i] = substr($s,0,3);
						$valores[] = substr($s,0,3);;
					else:
						$array[$y][$i] = $y;
					endif;

					// echo $array[$y][$i].'<br>';

				}	

			}	

			$result = array_count_values($valores);

			// foreach ($result as $value) {
			// 	echo $value . '<br>';
			// }

			// print_r($result);z

			foreach($result as $key => $value){
				
			   // if($value >= 5){			        
			   //      $chave = $key;

			   //      // echo $chave;
			   //      // echo '<b>5 pessoas Estão negociando a esse preço:</b> ';

			   //      break;
			   //  }

			    if($value >= 6){
			        
			        $chave = $key;

			        $totalOrdens = $value;

			        // echo '<b>4 pessoas Estão negociando a esse preço:</b> ';
			        // $valor = 1;
			        break;
			    }
			    // if($value == 1){
			        
			    //     $chave = $key;		        

			    //     // echo '<b>4 pessoas Estão negociando a esse preço:</b> ';
			    //     break;

			    // }

			}

			        // $chave = $key;
			if(!empty($chave)):		

				for($i=0;$i<count($array);$i++){

					if (in_array($chave, $array[$i])):
						$chave2 = $array[$i][1];
						break;
					endif;
				}						
							
			endif;

			// var_dump($teste);
			

			// if(isset($chave2)):
					return compact('chave2','totalOrdens');
			// endif;

		}catch(Exception $e){
			return $e->getMessage();
		}

}




