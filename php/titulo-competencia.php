		<legend align="center" class="rotulo">
			<span class="peque">
			<?php 
			$nueva_pagina=isset($nueva_pagina)?$nueva_pagina:NULL;
			if ($nueva_pagina)
				$ruta="../img/fotos/";
			else
				$ruta="img/fotos/";
			if ($logo) 
				echo "<img src='".$ruta.$logo."' width='10%' />";
			echo "&nbsp;&nbsp;".utf8_decode($competencia); 
			if ($logo2) 
				echo "&nbsp;&nbsp;<a href='https://www.fluidra.com'><img  src='".$ruta.$logo2,"' width='15%'></a>";
			?>
			</span>
			
		</legend>

