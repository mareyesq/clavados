SELECT * 
FROM planillad 
WHERE juez1=1378
	OR juez2=884
    OR juez3=884
    OR juez4=884
    OR juez5=884
    OR juez6=884
    OR juez7=884
    OR juez8=884
    OR juez9=884
    OR juez10=884
    OR juez11=884;

update planillad set juez1=1382 WHERE juez1=1331;
update planillad set juez2=1382 WHERE juez2=1331;
update planillad set juez3=1382 WHERE juez3=1331;    
update planillad set juez4=1382 WHERE juez4=1331;    
update planillad set juez5=1382 WHERE juez5=1331;    
update planillad set juez6=1382 WHERE juez6=1331;    
update planillad set juez7=1382 WHERE juez7=1331;    
update planillad set juez8=1382 WHERE juez8=1331;    
update planillad set juez9=1382 WHERE juez9=1331;    
update planillad set juez10=1382 WHERE juez10=1331;    
update planillad set juez11=1382 WHERE juez11=1331;    



DELETE FROM usuarios
WHERE cod_usuario=1378
