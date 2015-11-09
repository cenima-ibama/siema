-- View: public.vw_ocorrencia

DROP VIEW public.vw_ocorrencia;

CREATE OR REPLACE VIEW public.vw_ocorrencia AS
 SELECT o.id_ocorrencia,
    o.dt_registro,
    o.dt_ocorrencia,
    o.periodo_ocorrencia,
    uf.regiao,
    uf.sigla,
    ARRAY( SELECT t1.nome
           FROM tipo_evento t1,
            ocorrencia_tipo_evento r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_tipo_evento = t1.id_tipo_evento) AS eventos,
    ARRAY( SELECT t1.des_tipo_localizacao
           FROM tipo_localizacao t1,
            ocorrencia_tipo_localizacao r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_tipo_localizacao = t1.id_tipo_localizacao) AS origem,
    ARRAY( SELECT t1.nome
           FROM tipo_dano_identificado t1,
            ocorrencia_tipo_dano_identificado r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_tipo_dano_identificado = t1.id_tipo_dano_identificado) AS tipos_danos_identificados,
    ARRAY( SELECT t1.nome
           FROM instituicao_atuando_local t1,
            ocorrencia_instituicao_atuando_local r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_instituicao_atuando_local = t1.id_instituicao_atuando_local) AS institiuicoes_atuando_local,
    ARRAY( SELECT t1.nome
           FROM tipo_fonte_informacao t1,
            ocorrencia_tipo_fonte_informacao r_temp1
          WHERE r_temp1.id_ocorrencia = o.id_ocorrencia AND r_temp1.id_tipo_fonte_informacao = t1.id_tipo_fonte_informacao) AS tipos_fontes_informacoes
   FROM ocorrencia o
     LEFT JOIN uf ON o.id_uf = uf.id_uf
  ORDER BY o.id_ocorrencia;

ALTER TABLE public.vw_ocorrencia
  OWNER TO emergencias;
GRANT ALL ON TABLE public.vw_ocorrencia TO emergencias;
GRANT ALL ON TABLE public.vw_ocorrencia TO public;
