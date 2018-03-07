#!/usr/bin/env bash

# TODO share this between script (in an include)
if [ -f .env ]
then
    source .env
else
    echo "Please run this script from project root, and check .env file as it is mandatory"
    echo "If it is missing a quick solution is :"
    echo "ln -s .env.travis .env"
    exit 42
fi

if [ -z "${DBHOST}" ]
then
    echo "Please add DBHOST in .env file as it is mandatory"
    exit 42
fi

psql -w -h ${DBHOST}  -U ${DBROOTUSER} -d ${DBAPPNAME} <<EOF
DROP TABLE IF EXISTS public.test_final_table;

CREATE TABLE public.test_final_table
(
  id integer NOT NULL,
  final_parent_id integer,
  parent_id integer,
  name character varying(255) DEFAULT NULL::character varying,
  code character varying(15) DEFAULT NULL::character varying,
  CONSTRAINT test_final_table_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.test_final_table
  OWNER TO ${DBAPPUSER};
EOF



psql -w -h ${DBHOST}  -U ${DBROOTUSER} -d ${DBAPPNAME} <<EOF
DROP TABLE IF EXISTS public.test_parent_table;

CREATE TABLE public.test_parent_table
(
  id integer NOT NULL,
  second_id integer,
  parent_parent_id integer,
  name character varying(255) DEFAULT NULL::character varying,
  code character varying(15) DEFAULT NULL::character varying,
  CONSTRAINT test_parent_table_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.test_parent_table
  OWNER TO ${DBAPPUSER};

EOF


psql -w -h ${DBHOST}  -U ${DBROOTUSER} -d ${DBAPPNAME} <<EOF
DROP TABLE IF EXISTS public.test_second_table;

CREATE TABLE public.test_second_table
(
  id integer NOT NULL,
  simple_id integer,
  name character varying(255) DEFAULT NULL::character varying,
  code character varying(15) DEFAULT NULL::character varying,
  CONSTRAINT test_second_table_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.test_second_table
  OWNER TO ${DBAPPUSER};

EOF

psql -w -h ${DBHOST}  -U ${DBROOTUSER} -d ${DBAPPNAME} <<EOF
DROP TABLE IF EXISTS public.test_simple_table;

CREATE TABLE public.test_simple_table
(
  id integer NOT NULL,
  name character varying(255) DEFAULT NULL::character varying,
  code character varying(15) DEFAULT NULL::character varying,
  CONSTRAINT test_simple_table_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.test_simple_table
  OWNER TO ${DBAPPUSER};

EOF
