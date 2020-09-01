$(document).ready(function(){
			$('.btn-saldo-unitario').click(function(){

				var api_key =  '3YlbObNjztsFR7xFIa3CHRUTG3CuG3t7';
				var tipo = $(this).attr('tipo');
				var valor = $("#valor_uni").val();
				var moeda = $("#moeda_uni").val();
				

				dados = 'api_key='+api_key+'&valor='+valor+'&moeda='+moeda;
				console.log(dados);
				$.ajax({
					url:'ajax/getValor-moeda.php',
					data:dados,
					type:'POST',
					dataType:'json',
					success: function(result){

						html =  '<h5 class="saldo">R$: '+result+'</h5>';
						console.log(result);
						$('.box_uni').html(html);
					},
					error: function(result){
						console.log(result);
					}

				});
			});
});