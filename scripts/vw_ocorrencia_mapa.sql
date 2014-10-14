DROP VIEW public.vw_ocorrencia_mapa;

CREATE OR REPLACE VIEW public.vw_ocorrencia_mapa AS 
 SELECT o.id_ocorrencia,
    m.nome AS municipio,
    uf.estado,
    to_char(o.dt_ocorrencia::timestamp with time zone, 'DD/MM/YYYY'::text) AS data_acidente,
    o.legado,
    o.validado,
    p.shape,
    ARRAY( SELECT t1.des_tipo_localizacao
           FROM tipo_localizacao t1,
            ocorrencia_tipo_localizacao r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_tipo_localizacao = t1.id_tipo_localizacao) AS origem_acidente,
    ARRAY( SELECT t1.nome
           FROM tipo_evento t1,
            ocorrencia_tipo_evento r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_tipo_evento = t1.id_tipo_evento) AS tipo_eventos,
    ARRAY( SELECT (((t1.nome::text || ' - '::text) || t1.num_onu::text) || ' - '::text) || t1.classe_risco::text AS temp
           FROM produto_onu t1,
            ocorrencia_produto r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_produto_onu = t1.id_produto_onu) AS produtos
   FROM ocorrencia o
   LEFT JOIN uf ON o.id_uf = uf.id_uf
   LEFT JOIN municipio m ON m.id_municipio = o.id_municipio
   LEFT JOIN ocorrencia_pon p ON p.id_ocorrencia = o.id_ocorrencia
  WHERE p.shape IS NOT NULL
  ORDER BY o.id_ocorrencia;

ALTER TABLE public.vw_ocorrencia_mapa
  OWNER TO emergencias;
GRANT ALL ON TABLE public.vw_ocorrencia_mapa TO emergencias;
GRANT ALL ON TABLE public.vw_ocorrencia_mapa TO public;