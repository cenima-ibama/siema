ALTER TABLE tmp_ocorrencia_produto ALTER COLUMN quantidade TYPE character(10);

DROP VIEW public.vw_ocorrencia_consulta;

ALTER TABLE ocorrencia_produto ALTER COLUMN quantidade TYPE character(10);

CREATE OR REPLACE VIEW public.vw_ocorrencia_consulta AS 
 SELECT oc.id_ocorrencia,
    oc.nro_ocorrencia,
    oc.dt_ocorrencia,
    oc.hr_ocorrencia,
    oc.dt_primeira_obs,
    oc.hr_primeira_obs,
    oc.dt_registro,
        CASE oc.periodo_ocorrencia
            WHEN 'M'::bpchar THEN 'Matutino'::text
            WHEN 'V'::bpchar THEN 'Vespertino'::text
            WHEN 'N'::bpchar THEN 'Noturno'::text
            WHEN 'S'::bpchar THEN 'Madrugada'::text
            ELSE ''::text
        END AS periodo_ocorrencia,
        CASE oc.periodo_primeira_obs
            WHEN 'M'::bpchar THEN 'Matutino'::text
            WHEN 'V'::bpchar THEN 'Vespertino'::text
            WHEN 'N'::bpchar THEN 'Noturno'::text
            WHEN 'S'::bpchar THEN 'Madrugada'::text
            ELSE ''::text
        END AS periodo_primeira_obs,
    mu.nome AS municipio,
    uf.sigla AS uf,
    bs.nome AS bacia_sedimentar,
    ARRAY( SELECT tp.des_tipo_localizacao
           FROM tipo_localizacao tp,
            ocorrencia_tipo_localizacao ot
          WHERE tp.id_tipo_localizacao = ot.id_tipo_localizacao AND ot.id_ocorrencia = oc.id_ocorrencia) AS origem,
    ARRAY( SELECT ev.nome
           FROM tipo_evento ev,
            ocorrencia_tipo_evento ov
          WHERE ev.id_tipo_evento = ov.id_tipo_evento AND ov.id_ocorrencia = oc.id_ocorrencia) AS tipo_evento,
        CASE oc.dt_ocorrencia_feriado
            WHEN true THEN 'Sim'::text
            WHEN false THEN 'Não'::text
            ELSE ''::text
        END AS dt_ocorrencia_feriado,
        CASE date_part('dow'::text, oc.dt_ocorrencia)
            WHEN 0 THEN 'Domingo'::text
            WHEN 1 THEN 'Segunda'::text
            WHEN 2 THEN 'Terça'::text
            WHEN 3 THEN 'Quarta'::text
            WHEN 4 THEN 'Quinta'::text
            WHEN 5 THEN 'Sexta'::text
            WHEN 6 THEN 'Sábado'::text
            ELSE ''::text
        END AS dia_semana,
        CASE date_part('dow'::text, oc.dt_primeira_obs)
            WHEN 0 THEN 'Domingo'::text
            WHEN 1 THEN 'Segunda'::text
            WHEN 2 THEN 'Terça'::text
            WHEN 3 THEN 'Quarta'::text
            WHEN 4 THEN 'Quinta'::text
            WHEN 5 THEN 'Sexta'::text
            WHEN 6 THEN 'Sábado'::text
            ELSE ''::text
        END AS dia_semana_primeira_obs,
        CASE date_part('dow'::text, oc.dt_registro)
            WHEN 0 THEN 'Domingo'::text
            WHEN 1 THEN 'Segunda'::text
            WHEN 2 THEN 'Terça'::text
            WHEN 3 THEN 'Quarta'::text
            WHEN 4 THEN 'Quinta'::text
            WHEN 5 THEN 'Sexta'::text
            WHEN 6 THEN 'Sábado'::text
            ELSE ''::text
        END AS dia_semana_registro,
    ARRAY( SELECT t1.nome
           FROM instituicao_atuando_local t1,
            ocorrencia_instituicao_atuando_local r_temp1
          WHERE r_temp1.id_ocorrencia = oc.id_ocorrencia AND r_temp1.id_instituicao_atuando_local = t1.id_instituicao_atuando_local) AS institiuicoes_atuando_local,
    ARRAY( SELECT t1.nome
           FROM tipo_fonte_informacao t1,
            ocorrencia_tipo_fonte_informacao r_temp1
          WHERE r_temp1.id_ocorrencia = oc.id_ocorrencia AND r_temp1.id_tipo_fonte_informacao = t1.id_tipo_fonte_informacao) AS tipos_fontes_informacoes,
    ARRAY( SELECT t1.nome
           FROM tipo_dano_identificado t1,
            ocorrencia_tipo_dano_identificado r_temp1
          WHERE r_temp1.id_ocorrencia = oc.id_ocorrencia AND r_temp1.id_tipo_dano_identificado = t1.id_tipo_dano_identificado) AS tipos_danos_identificados,
    ARRAY( SELECT concat(btrim(t1.nome::text), ' (', r_temp1.quantidade, ' ', btrim(r_temp1.unidade_medida::text), ')') AS concat
           FROM produto_onu t1,
            ocorrencia_produto r_temp1
          WHERE r_temp1.id_ocorrencia = oc.id_ocorrencia AND r_temp1.id_produto_onu = t1.id_produto_onu) AS produtos_onu,
    ARRAY( SELECT concat(btrim(t1.nome::text), ' (', r_temp1.quantidade, ' ', btrim(r_temp1.unidade_medida::text), ')') AS concat
           FROM produto_outro t1,
            ocorrencia_produto r_temp1
          WHERE r_temp1.id_ocorrencia = oc.id_ocorrencia AND r_temp1.id_produto_outro = t1.id_produto_outro) AS produtos_outro,
    oc.legado,
    oc.validado
   FROM ocorrencia oc
     LEFT JOIN municipio mu ON oc.id_municipio = mu.cod_ibge
     LEFT JOIN uf ON oc.id_uf = uf.id_uf
     LEFT JOIN bacia_sedimentar bs ON oc.id_bacia_sedimentar = bs.id_bacia_sedimentar;

ALTER TABLE public.vw_ocorrencia_consulta
  OWNER TO emergencias;
GRANT ALL ON TABLE public.vw_ocorrencia_consulta TO emergencias;
GRANT ALL ON TABLE public.vw_ocorrencia_consulta TO public;
