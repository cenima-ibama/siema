update ocorrencia set
id_municipio = (select municipio.cod_ibge from municipio where municipio.id_municipio=ocorrencia.id_municipio)
