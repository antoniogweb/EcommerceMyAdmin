update pages set crea_cache = 0 where tipo_pagina in ('COOKIE', 'ACCOUNT_ELIMINATO', 'FORM_FEEDBACK', 'CONF_CONT_SCADUTO', 'LISTA_REGALO');
