INSERT INTO `eventi_retargeting_gruppi` (`id_gruppo_retargeting`, `titolo`, `model`, `attivo`, `id_order`, `condizioni`, `clausola_where`, `blocca_reinvio_mail_stesso`) VALUES ('8', 'Manda email quando la spedizione è stata inviata al corriere', 'SpedizioninegozioeventiModel', '1', '8', 'attiva_gestione_spedizioni=1', 'codice = \'I\'', 'EVENTO_ELEMENTO')