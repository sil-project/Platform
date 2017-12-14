#!/usr/bin/env sh

psql -U postgres -d travis <<EOF


-- Table: public.blast_session

-- DROP TABLE public.blast_session;

CREATE TABLE public.blast_session
(
  id integer NOT NULL,
  session_id character varying(255) NOT NULL,
  data bytea,
  created_at timestamp(0) without time zone NOT NULL,
  expires_at timestamp(0) without time zone NOT NULL,
  CONSTRAINT blast_session_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.blast_session
  OWNER TO sil_user;

-- Index: public.blast_session_session_id_index

-- DROP INDEX public.blast_session_session_id_index;

CREATE INDEX blast_session_session_id_index
  ON public.blast_session
  USING btree
  (session_id COLLATE pg_catalog."default");

EOF
