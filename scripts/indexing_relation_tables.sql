CREATE INDEX ocorrencia_id_ocorrencia_idx ON public.ocorrencia USING btree (id_ocorrencia);
reindex table public.ocorrencia;

CREATE INDEX tipo_evento_id_ocorrencia_idx ON public.ocorrencia_tipo_evento USING btree (id_ocorrencia);
reindex table public.ocorrencia_tipo_evento;

CREATE INDEX tipo_dano_identificado_id_ocorrencia_idx ON public.ocorrencia_tipo_dano_identificado USING btree (id_ocorrencia);
reindex table public.ocorrencia_tipo_dano_identificado;

CREATE INDEX tipo_fonte_informacao_id_ocorrencia_idx ON public.ocorrencia_tipo_fonte_informacao USING btree (id_ocorrencia);
reindex table public.ocorrencia_tipo_fonte_informacao;

CREATE INDEX instituicao_atuando_local_id_ocorrencia_idx ON public.ocorrencia_instituicao_atuando_local USING btree (id_ocorrencia);
reindex table public.ocorrencia_instituicao_atuando_local;

CREATE INDEX tipo_localizacao_id_ocorrencia_idx ON public.ocorrencia_tipo_localizacao USING btree (id_ocorrencia);
reindex table public.ocorrencia_tipo_localizacao;